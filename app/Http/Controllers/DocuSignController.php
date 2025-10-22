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


class DocuSignController extends Controller
{
    public function connect(DocuSignService $docuSign)
    {
        return redirect($docuSign->getAuthUrl());
    }

    public function callback(Request $request)
    {
        $code = $request->query('code');

        $response = Http::asForm()->post('https://account-d.docusign.com/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => config('docusign.client_id'),
            'client_secret' => config('docusign.client_secret'),
            'redirect_uri' => config('docusign.redirect_uri'),
        ]);

        session(['docusign_token' => $response['access_token']]);

        return redirect('/dashboard')->with('success', 'Connected to DocuSign!');
    }

    public function sendDocument(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'document' => 'required|mimes:pdf'
        ]);

        $token = session('docusign_token');
        if (!$token) {
            return redirect('/docusign/connect')->with('error', 'Please connect to DocuSign first.');
        }

        $fileContent = base64_encode(file_get_contents($request->file('document')->getPathName()));

        $config = new \DocuSign\eSign\Configuration();
        $config->setHost(config('docusign.base_uri'));
        $config->addDefaultHeader('Authorization', 'Bearer ' . $token);
        $apiClient = new \DocuSign\eSign\Client\ApiClient($config);

        $document = new Document([
            'document_base64' => $fileContent,
            'name' => 'Document to Sign',
            'file_extension' => 'pdf',
            'document_id' => '1'
        ]);

        $signer = new Signer([
            'email' => $request->email,
            'name' => $request->name,
            'recipient_id' => '1',
            'routing_order' => '1'
        ]);

        $signHere = new SignHere([
            'anchor_string' => '/signature/',
            'anchor_units' => 'pixels',
            'anchor_y_offset' => '10',
            'anchor_x_offset' => '20'
        ]);

        $tabs = new Tabs(['sign_here_tabs' => [$signHere]]);
        $signer->setTabs($tabs);

        $envelopeDefinition = new EnvelopeDefinition([
            'email_subject' => 'Please sign this document',
            'documents' => [$document],
            'recipients' => new Recipients(['signers' => [$signer]]),
            'status' => 'sent'
        ]);

        $envelopesApi = new EnvelopesApi($apiClient);
        $result = $envelopesApi->createEnvelope(config('docusign.account_id'), $envelopeDefinition);

        return back()->with('success', 'Document sent! Envelope ID: ' . $result->getEnvelopeId());
    }

    public function sendToDocuSign(Request $request)
    {
        $data = $request->validate([
            'signature_entity_id_hidden' => 'required',
        ]);

        $token = session('docusign_token');
        if (!$token) {
            return redirect('/docusign/connect')->with('error', 'Please connect to DocuSign first.');
        }
        
        $representant = Representants_legaux::where('id', $data["signature_entity_id_hidden"]);
        $societeId = $representant->value('societe_id');
        
        // Then get the emails of users with that societe_id
        $representantEmail = User::where('societe_id', $societeId)->value('email');
        
        // Static PDF path (example: public folder)
        $pdfPath = public_path('test.pdf');
        
        if (!file_exists($pdfPath)) {
            return back()->with('error', 'Le fichier PDF est introuvable.');
        }
        //dd($representant);

        $fileContent = base64_encode(file_get_contents($pdfPath));

        // DocuSign API config
        $config = new \DocuSign\eSign\Configuration();
        $config->setHost(config('docusign.base_uri'));
        $config->addDefaultHeader('Authorization', 'Bearer ' . $token);
        $apiClient = new \DocuSign\eSign\Client\ApiClient($config);

        // Prepare document
        $document = new Document([
            'document_base64' => $fileContent,
            'name' => 'Contrat ' . $representantEmail,
            'file_extension' => 'pdf',
            'document_id' => '1'
        ]);

        // Signer setup
        $signer = new Signer([
            'email' => $representantEmail,
            'name' => "akram",
            'recipient_id' => '1',
            'routing_order' => '1'
        ]);

        $signHere = new SignHere([
            'anchor_string' => '/signature/',
            'anchor_units' => 'pixels',
            'anchor_y_offset' => '10',
            'anchor_x_offset' => '20'
        ]);

        $tabs = new Tabs(['sign_here_tabs' => [$signHere]]);
        $signer->setTabs($tabs);

        // Envelope
        $envelopeDefinition = new EnvelopeDefinition([
            'email_subject' => 'Veuillez signer le contrat #' . $representantEmail,
            'documents' => [$document],
            'recipients' => new Recipients(['signers' => [$signer]]),
            'status' => 'sent'
        ]);

        $envelopesApi = new EnvelopesApi($apiClient);
        $result = $envelopesApi->createEnvelope(config('docusign.account_id'), $envelopeDefinition);

        return back()->with('success', 'Document envoyé via DocuSign ! Envelope ID: ' . $result->getEnvelopeId());
    }
}
