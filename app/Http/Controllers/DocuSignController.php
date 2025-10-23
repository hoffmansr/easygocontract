<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\Tabs;
use DocuSign\eSign\Model\Recipients;
use Illuminate\Support\Facades\Http;
use App\Services\DocusignService;
use App\Models\Representants_legaux;
use App\Models\User;
use App\Models\Contrat;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;


class DocuSignController extends Controller
{
    public function connect(DocuSignService $docuSign, Request $request)
    {
        $contratId = $request->query('contrat_id');
        $authUrl = $docuSign->getAuthUrl();

        return redirect($authUrl . '&state=' . $contratId);
    }


    public function callback(Request $request)
    {
        $code = $request->query('code');
        $contratId = $request->query('state'); // retrieve contract ID here

        $response = Http::asForm()->post('https://account-d.docusign.com/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => config('docusign.client_id'),
            'client_secret' => config('docusign.client_secret'),
            'redirect_uri' => config('docusign.redirect_uri'),
        ]);

        session(['docusign_token' => $response['access_token']]);

        // Continue pending action if exists
        if (session()->has('pending_docusign_data') && $contratId) {
            $data = session()->pull('pending_docusign_data');
            $contrat = Contrat::findOrFail($contratId);

            return $this->processSendToDocuSign($data, $contrat, $response['access_token']);
        }

        return redirect('/dashboard')->with('success', 'Connexion DocuSign réussie.');
    }

    public function sendToDocuSign(Request $request, $id)
    {
        $contrat = Contrat::findOrFail($id);

        // Validate input
        $data = $request->validate([
            'signature_id' => 'required',
        ]);

        $token = session('docusign_token');

        // If no token, redirect to connect and save pending data
        if (!$token) {
            session(['pending_docusign_data' => $data]);
            return redirect('/docusign/connect?contrat_id=' . $contrat->id)
                ->with('info', 'Connectez-vous à DocuSign pour envoyer le document.');
        }

        // Proceed to send document
        return $this->processSendToDocuSign($data, $contrat, $token);
    }

    protected function processSendToDocuSign(array $data, Contrat $contrat, string $token)
    {
        try {
            // Get representative and related email
            $representant = Representants_legaux::findOrFail($data["signature_id"]);
            $societeId = $representant->societe_id;
            $representantEmail = User::where('societe_id', $societeId)->value('email');

            $filePath = storage_path('app/public/' . ltrim($contrat->modele->fichier_modele, '/'));

            if (!file_exists($filePath)) {
                return back()->with('error', 'Le fichier du modèle de contrat est introuvable : ' . $filePath);
            }

            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if ($extension === 'docx') {
                try {
                    $pdfPath = str_replace('.docx', '.pdf', $filePath);

                    // ✅ Define DomPDF as the PDF renderer
                    Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
                    Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

                    $phpWord = IOFactory::load($filePath);
                    $phpWord->save($pdfPath, 'PDF');

                    $filePath = $pdfPath; // use the new PDF
                } catch (\Throwable $e) {
                    return back()->with('error', 'Erreur lors de la conversion du fichier DOCX en PDF : ' . $e->getMessage());
                }
            }

            $fileContent = base64_encode(file_get_contents($filePath));
            if (!$fileContent) {
                return back()->with('error', 'Impossible de lire le contenu du fichier.');
            }

            $config = new \DocuSign\eSign\Configuration();
            $config->setHost(config('docusign.base_uri'));
            $config->addDefaultHeader('Authorization', 'Bearer ' . $token);
            $apiClient = new \DocuSign\eSign\Client\ApiClient($config);

            // ✅ Prepare document (detect actual extension)
            $document = new \DocuSign\eSign\Model\Document([
                'document_base64' => $fileContent,
                'name' => 'Contrat ' . $contrat->titre,
                'file_extension' => pathinfo($filePath, PATHINFO_EXTENSION),
                'document_id' => '1',
            ]);

            // ✅ Prepare signer
            $signer = new \DocuSign\eSign\Model\Signer([
                'email' => $representantEmail,
                'name' => $representant->nom ?? 'Signataire',
                'recipient_id' => '1',
                'routing_order' => '1',
            ]);

            // ✅ Define signature position (no anchor)
            $signHere = new \DocuSign\eSign\Model\SignHere([
                'x_position' => '200',
                'y_position' => '600',
                'document_id' => '1',
                'page_number' => '1',
            ]);

            $tabs = new \DocuSign\eSign\Model\Tabs(['sign_here_tabs' => [$signHere]]);
            $signer->setTabs($tabs);

            // ✅ Envelope definition
            $envelopeDefinition = new \DocuSign\eSign\Model\EnvelopeDefinition([
                'email_subject' => 'Veuillez signer le contrat "' . $contrat->titre . '"',
                'documents' => [$document],
                'recipients' => new \DocuSign\eSign\Model\Recipients(['signers' => [$signer]]),
                'status' => 'sent',
            ]);

            // ✅ Send
            $envelopesApi = new \DocuSign\eSign\Api\EnvelopesApi($apiClient);
            $result = $envelopesApi->createEnvelope(config('docusign.account_id'), $envelopeDefinition);

            return back()->with('success', 'Document envoyé via DocuSign, verifiez votre email');
        }

        // 🔥 DocuSign errors
        catch (\DocuSign\eSign\ApiException $e) {
            $body = json_decode($e->getResponseBody(), true);
            $message = $body['message'] ?? $e->getMessage();

            if (strpos($e->getMessage(), '401') !== false) {
                session()->forget('docusign_token');
                session(['pending_docusign_data' => $data]);
                return redirect('/docusign/connect')->with('error', 'Votre session DocuSign a expiré. Veuillez vous reconnecter.');
            }

            \Log::error('DocuSign API Error', [
                'message' => $e->getMessage(),
                'body' => $e->getResponseBody()
            ]);

            return back()->with('error', 'Erreur DocuSign : ' . $message);
        }

        // 🔥 Generic errors
        catch (\Exception $e) {
            \Log::error('Unexpected Error in DocuSign send', ['message' => $e->getMessage()]);
            return back()->with('error', $e->getMessage());
        }
    }

}
