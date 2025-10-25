<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use PhpOffice\PhpWord\Settings;
use App\Models\Contrat;
use PhpOffice\PhpWord\IOFactory;


class SignaturePadController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'signature' => 'required',
            'modele' => 'required',
            'alignment_value' => 'required|in:left,center,right',
        ]);

        $contrat = Contrat::findOrFail($id);

        // Extract base64 image
        $signatureData = $request->input('signature');
        $signatureData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $signatureData);
        $signatureImage = base64_decode($signatureData);

        // Save temporary signature image
        $signaturePath = storage_path('app/public/signature.png');
        file_put_contents($signaturePath, $signatureImage);

        // Path to model file (ensure correct relative path)
        $relativePath = ltrim($contrat->modele->fichier_modele, '/'); // e.g. modeles_contrats/abc.pdf
        $filePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Le fichier du modèle de contrat est introuvable : ' . $filePath);
        }

        // Convert DOCX to PDF if needed
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($extension === 'docx') {
            try {
                $pdfPath = str_replace('.docx', '.pdf', $filePath);

                // Define DomPDF as the PDF renderer
                Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
                Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

                $phpWord = IOFactory::load($filePath);
                $phpWord->save($pdfPath, 'PDF');

                $filePath = $pdfPath;
            } catch (\Throwable $e) {
                return back()->with('error', 'Erreur lors de la conversion du fichier DOCX en PDF : ' . $e->getMessage());
            }
        }

        // ✅ FIX: Use correct file path directly
        $pdfPath = $filePath;
        $outputPath = storage_path('app/public/signed_document.pdf');

        // Load and prepare PDF
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->setSourceFile($pdfPath);
        $tplId = $pdf->importPage(1);
        $pdf->useTemplate($tplId, 0, 0, 210);

        // Default signature position
        $y = 250;
        $width = 50;
        $height = 25;

        // Adjust X based on alignment
        switch ($request->alignment_value) {
            case 'left':
                $x = 20;
                break;
            case 'center':
                $x = (210 - $width) / 2;
                break;
            case 'right':
                $x = 210 - $width - 20;
                break;
            default:
                $x = 20;
        }

        // Add signature image to PDF
        $pdf->Image($signaturePath, $x, $y, $width, $height);

        // Save final PDF
        $pdf->Output($outputPath, 'F');

        return response()->download($outputPath);
    }

}

