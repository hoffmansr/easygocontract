<?php

namespace App\Http\Controllers;

use App\Models\Modeles_contrat;
use App\Models\Types_contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;

class ModeleContratController extends Controller
{
    /**
     * Liste des modèles de contrat de la société
     */
    public function index()
    {
        $modeles = Modeles_contrat::where('societe_id', Auth::user()->societe_id)->get();
        return view('modeles_contrats.index', compact('modeles'));
    }

    /**
     * Formulaire de création d’un modèle
     */
    public function create()
    {
        $typesContrats = Types_contrat::where('societe_id', Auth::user()->societe_id)->get();
        return view('modeles_contrats.create', compact('typesContrats'));
    }

    /**
     * Enregistrement d’un nouveau modèle
     */
public function store(Request $request)
{
    $data = $request->validate([
        'langue'         => 'required|string|max:50',
        'titre'          => 'required|string|max:255',
        'description'    => 'required|string',
        'type_contrat'   => 'nullable|exists:types_contrats,id',
        'fichier_modele' => 'required|file|mimes:doc,docx,pdf',
        'actif'          => 'nullable|boolean',
    ]);

    $data['societe_id'] = Auth::user()->societe_id;
    $data['actif']      = $request->has('actif') ? 1 : 0;

    if ($request->hasFile('fichier_modele')) {
        $path = $request->file('fichier_modele')->store('modeles_contrats', 'public');
        $data['fichier_modele'] = $path;

        $ext = strtolower($request->file('fichier_modele')->getClientOriginalExtension());

        // Si c’est un fichier Word → on le convertit en HTML
        if (in_array($ext, ['doc', 'docx'])) {
            $fullPath = storage_path('app/public/' . $path);

            $phpWord = IOFactory::load($fullPath);
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
            $htmlContent = '';
            ob_start();
            $htmlWriter->save('php://output');
            $htmlContent = ob_get_clean();

            // on nettoie un peu le HTML et on le met dans une colonne "html_contenu"
            $data['html_contenu'] = $htmlContent;
        }
    }

    Modeles_contrat::create($data);

    return redirect()->route('modeles_contrats.index')->with('success', 'Modèle ajouté.');
}


    /**
     * Affichage d’un modèle
     */
    public function show(Modeles_contrat $modeles_contrat)
    {
        return view('modeles_contrats.show', compact('modeles_contrat'));
    }

    /**
     * Formulaire d’édition
     */
    public function edit(Modeles_contrat $modeles_contrat)
    {
        $typesContrats = Types_contrat::all();
        return view('modeles_contrats.edit', compact('modeles_contrat', 'typesContrats'));
    }

    /**
     * Mise à jour d’un modèle
     */
    public function update(Request $request, Modeles_contrat $modeles_contrat)
    {
        $data = $request->validate([
            'langue'         => 'required|string|max:50',
            'titre'          => 'required|string|max:255',
            'description'    => 'required|string',
            'type_contrat'   => 'nullable|exists:types_contrats,id',
            'fichier_modele' => 'nullable|file|mimes:pdf,doc,docx',
            'actif'          => 'nullable|boolean',
        ]);

        $data['actif'] = $request->has('actif') ? 1 : 0;

        if ($request->hasFile('fichier_modele')) {
            // Supprimer l'ancien fichier
            if ($modeles_contrat->fichier_modele && Storage::disk('public')->exists($modeles_contrat->fichier_modele)) {
                Storage::disk('public')->delete($modeles_contrat->fichier_modele);
            }

            $path = $request->file('fichier_modele')->store('modeles_contrats', 'public');
            $data['fichier_modele'] = $path;

            // Re-détection des variables si nouveau fichier
            $ext = $request->file('fichier_modele')->getClientOriginalExtension();
            if (strtolower($ext) === 'docx') {
                $fullPath = storage_path('app/public/' . $path);
                $variables = $this->extractVariablesFromDocx($fullPath);
                $data['variables'] = json_encode($variables);
            }
        }

        $modeles_contrat->update($data);

        return redirect()->route('modeles_contrats.index')->with('success', 'Modèle de contrat mis à jour.');
    }

    /**
     * Suppression d’un modèle
     */
    public function destroy(Modeles_contrat $modeles_contrat)
    {
        if ($modeles_contrat->fichier_modele && Storage::disk('public')->exists($modeles_contrat->fichier_modele)) {
            Storage::disk('public')->delete($modeles_contrat->fichier_modele);
        }

        $modeles_contrat->delete();

        return redirect()->route('modeles_contrats.index')->with('success', 'Modèle de contrat supprimé.');
    }

    /**
     * Récupérer les infos d’un modèle pour les clauses
     */
    public function getClauses($id)
    {
        $modele = Modeles_contrat::findOrFail($id);

        return response()->json([
            [
                'titre'          => $modele->titre,
                'contenu'        => $modele->description ?? 'Pas de contenu',
                'fichier_modele' => $modele->fichier_modele ? asset('storage/' . $modele->fichier_modele) : null,
                'variables'      => $modele->variables ? json_decode($modele->variables, true) : []
            ]
        ]);
    }

    /**
     * Extraction des variables depuis un DOCX
     */
    private function extractVariablesFromDocx($filePath)
    {
        $zip = new \ZipArchive();
        $variables = [];

        if ($zip->open($filePath) === true) {
            $content = $zip->getFromName("word/document.xml");
            $zip->close();

            // Cherche toutes les variables au format {{variable}}
            preg_match_all('/\{\{(.*?)\}\}/', $content, $matches);
            $variables = $matches[1] ?? [];
        }

        return $variables;
    }
    
}
