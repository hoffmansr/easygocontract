<?php
namespace App\Http\Controllers;

use App\Models\Annees_fiscale;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\Request;
use App\Models\Modeles_contrat;  
use App\Models\Clausier;            
use App\Models\Contractant;
use App\Models\Types_contractant;
use App\Models\Types_contrat;
use App\Models\Workflow;
use App\Models\Contrat;
use App\Models\Clauses_contrat;
use App\Models\Representants_legaux;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\ContratWorkflowEtape;
use Illuminate\Support\Facades\Log;

use App\Mail\ContratEnApprobationMail;
use App\Notifications\ContratEnApprobation;

use Spatie\Permission\Commands\Show;

class ContratController extends Controller



  
{


 // ContratController.php
    public function index(Request $request)
    {
        $user = auth()->user();
        $societeId = $user->societe_id ?? null;

        // --- Requête de base ---
        $query = Contrat::query()->with('typesContrat');

        // Si l'utilisateur n'est pas Super Admin → restreindre aux contrats de sa société
        if (!$user->hasRole('Super Admin') && $societeId) {
            $query->where('societe_id', $societeId);
        }

        // --- Filtres de recherche ---
        // Recherche par titre
        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        // Filtre statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre type de contrat
        if ($request->filled('type')) {
            $query->where('type_contrat_id', $request->type);
        }

         $query->orderBy('created_at', 'desc');

        // --- Résultats ---
        $contrats = $query->paginate(10);

        // Types de contrats (filtrés par société sauf Super Admin)
        $typesContrat = Types_contrat::when(!$user->hasRole('Super Admin') && $societeId, function ($q) use ($societeId) {
            $q->where('societe_id', $societeId);
        })->get();

        return view('contrats.index', compact('contrats', 'typesContrat'));
    }

// creation de connection_aborted
        public function storeDraft()
    {
        $user = auth()->user();

        // 1 Créer le brouillon de contrat
        $contrat = Contrat::create([
            'societe_id' => $user->societe_id,
            'statut' => 'initie',
            'titre' => 'Nouveau contrat',
            'workflow_id' => null,
            'date_debut' => null, 
            'date_fin' => null, 
            'notif_contractant' => false,
            'type_contrat_id' => null,
            'annee_fiscale_id' => null,
            'description' => '',
            'notes_generales' => null,
            'model_contrat_id' => null,
            'type_renouvelement' => '',
            'duree_auto_renouvellement' => null,
            'date_notification' => null,
            'jours_preavis_resiliation' => null,
            'signature_entity_id' => $user->representantLegal?->id, // représentant légal si disponible
        ]);

        // 2 Créer une clause vide liée au contrat (optionnel)
        Clauses_contrat::create([
            'contrat_id' => $contrat->id,
            'clausier_id' => null, // ou un clausier par défaut si tu veux
            'contenu' => '', // champ vide
        ]);

        // 3 Redirection vers l'édition avec message flash
        return redirect()->route('contrats.edit', $contrat)
                        ->with('success', 'Brouillon créé avec succès');
    }



     public function ebauche(Request $request, $id)
    {
        $contrat = Contrat::findOrFail($id);

        // 1. Met à jour les infos générales (sauf variables, clauses, contractants, workflow_id)
        $contrat->fill($request->except(['contenu_document', 'contractants', 'clauses', 'workflow_id', 'variables']));

        // 2. Récupère le contenu HTML et les variables (JSON)
        $contenu = $request->contenu_document;
        $variables = [];
        if ($request->has('variables')) {
            // Si variables transmises en JSON, les décoder
            if (is_string($request->variables)) {
                $variables = json_decode($request->variables, true) ?? [];
            } elseif (is_array($request->variables)) {
                $variables = $request->variables;
            }
        }

        // 3. Remplace les variables dans le contenu (si besoin, mais normalement déjà fait JS côté front)
        foreach ($variables as $key => $value) {
            // Ici, au cas où il reste des [nom] ou nom dans le contenu
            $contenu = str_replace("[$key]", $value, $contenu);
            $contenu = str_replace($key, $value, $contenu);
        }
        $contrat->contenu_document = $contenu;

        // 4. Met à jour le workflow si sélectionné
        if ($request->has('workflow_id')) {
            $contrat->workflow_id = $request->workflow_id;
        }

        if ($request->has('modele_contrat_id')) {
            $contrat->model_contrat_id = $request->modele_contrat_id;
        }

     // Vérifier si le contrat peut être révisé
        if (in_array($contrat->statut, ['signé', 'actif', 'approuvé', 'en_approbation', 'annulé', 'expiré', 'renouvelé'])) {
            return redirect()->route('contrats.edit', $contrat->id)
                ->with('error', "Impossible de réviser un contrat déjà {$contrat->statut}.");
        }
        // 5. Passe le statut à 'ebauche'
        $contrat->statut = 'ebauche';
        $contrat->save();

        // 6. Met à jour les contractants
        if ($request->has('contractants')) {
            $contrat->contractants()->sync($request->contractants);
        }

        // 7. Met à jour les clauses associées
        if ($request->has('clauses')) {
            $contrat->clauses()->sync($request->clauses);
        }

        return redirect()->route('contrats.index', $contrat->id)
            ->with('success', 'Contrat généré et enregistré en ébauche !');
    }

    // méthode pour reviser un contrat
    public function reviser(Contrat $contrat)
    {
        // Vérifier si le contrat peut être révisé
        if (in_array($contrat->statut, ['signé', 'actif', 'approuve', 'en_approbation', 'annulé', 'expiré', 'renouvelé'])) {
            return redirect()->route('contrats.edit', $contrat->id)
                ->with('error', "Impossible de réviser un contrat déjà {$contrat->statut}.");
        }

        // Mettre à jour le statut
        $contrat->update([
            'statut' => 'revisé'
        ]);

        return redirect()->route('contrats.edit', $contrat->id)
            ->with('success', 'Le contrat a été marqué comme révisé, vous pouvez maintenant le modifier.');
    }



// méthode pour assigner un workflow à un contrat et notifier les approbateurs
    public function assignWorkflow(Request $request, $id)
    {
        $contrat = Contrat::findOrFail($id);

        $request->validate([
            'workflow_id' => 'required|exists:workflows,id',
        ]);
        if (in_array($contrat->statut, ['signé', 'actif', 'approuvé', 'annulé', 'expiré', 'renouvelé'])) {
                return redirect()->route('contrats.edit', $contrat->id)
                    ->with('error', "Impossible de réviser un contrat déjà {$contrat->statut}.");
            }

        // Mise à jour du contrat
        $contrat->update([
            'workflow_id' => $request->workflow_id,
            'statut' => 'en_approbation',
            'notif_contractant' => $request->has('notif_contractant'),
        ]);

        // Créer les étapes du workflow pour ce contrat
        $workflow = Workflow::with('etapes')->findOrFail($request->workflow_id);

        foreach ($workflow->etapes as $etape) {
            ContratWorkflowEtape::create([
                'contrat_id' => $contrat->id,
                'workflow_etape_id' => $etape->id,
                'approver_id' => $etape->user_id ?? null,
                'statut' => 'en_attente',
            ]);
        }

        // Notifier uniquement l'approbateur de la première étape
        $premiereEtape = $contrat->workflowEtapes()->orderBy('workflow_etape_id')->first();
        if ($premiereEtape && $premiereEtape->approver) {
            $premiereEtape->approver->notify(
                (new \App\Notifications\ContratEnApprobation($contrat))->delay(now()->addSeconds(5))
            );
        }

        // Notifications aux contractants (optionnel)
        if ($contrat->notif_contractant) {
            foreach ($contrat->contractants as $contractant) {
                if ($contractant->email) {
                    $contractant->notify(
                        (new \App\Notifications\ContratEnApprobation($contrat, true))->delay(now()->addSeconds(5))
                    );
                }
            }
        }

        return redirect()->route('contrats.index')
            ->with('success', 'Workflow attribué, étapes générées et notifications envoyées.');
    }

    // méthode pour approuver une étape de workflow
    public function approuver(Request $request, $id)
    {
        $contrat = Contrat::with('workflowEtapes')->findOrFail($id);
        $userId = auth()->id();

        // Chercher l’étape en attente de cet utilisateur
        $etapeContrat = $contrat->workflowEtapes()
            ->where('approver_id', $userId)
            ->where('statut', 'en_attente')
            ->first();

        if (!$etapeContrat) {
            return back()->with('error', "Aucune étape en attente pour vous.");
        }

        // Valider l’étape
        $etapeContrat->update(['statut' => 'approuvé']);

        // Cherche l'étape suivante en attente (id > étape courante)
        $etapeSuivante = $contrat->workflowEtapes()
            ->where('id', '>', $etapeContrat->id)
            ->where('statut', 'en_attente')
            ->orderBy('id')
            ->first();

        if ($etapeSuivante && $etapeSuivante->approver) {
            $etapeSuivante->approver->notify(
                (new \App\Notifications\ContratEnApprobation($contrat))->delay(now()->addSeconds(5))
            );
        }

        // Vérifier s’il reste encore des étapes en attente
        $reste = $contrat->workflowEtapes()->where('statut', 'en_attente')->count();

        if ($reste == 0) {
            // toutes les étapes validées -> contrat validé
            if (in_array($contrat->statut, ['signé','actif','annulé','expiré','renouvelé'])) {
                return back()->with('error', "Impossible d'approuver un contrat déjà {$contrat->statut}.");
            }

            $contrat->update(['statut' => 'approuvé']);
        }

        return redirect()->route('contrats.index')
            ->with('success', "Étape validée avec succès !");
    }
    
    public function edit(Contrat $contrat)
    { 

        $societeId = auth()->user()->societe_id ?? null;
        $modelesContrat = Modeles_contrat::where('societe_id', auth()->user()->societe_id)->get();
        $bibliothequeClauses = Clausier::where('societe_id', auth()->user()->societe_id)->get();
        $clausesContrat = $contrat->clausesContrat()->with(['modeleContrat'])->get();
        $typesContrats = Types_contrat::where('societe_id', auth()->user()->societe_id)->get();
        $contractants = Contractant::where('societe_id', auth()->user()->societe_id)->get();
        $typesContractants = Types_contractant::where('societe_id', auth()->user()->societe_id)->get();
        $workflows = Workflow::where('societe_id', auth()->user()->societe_id)->get();
        $representants = Representants_legaux::where('societe_id', auth()->user()->societe_id)->get();
        $anneesFiscales = Annees_fiscale::where('societe_id', auth()->user()->societe_id)->get();



        return view('contrats.edit', compact(
            'contrat',
            'modelesContrat',
            'bibliothequeClauses',
            'clausesContrat',
            'typesContrats',
            'contractants',
            'typesContractants',
            'workflows',
            'representants',
            'anneesFiscales'
        ));
    }

    //  méthode pour obtenir le contenu du modèle de contrat et le retourner en HTML avec les variables remplacées par des champs input
public function getModeleContratContent($id)
{
    $modele = Modeles_contrat::find($id);

    if (!$modele) {
        return response()->json([
            'type' => 'error',
            'message' => 'Modèle non trouvé'
        ], 404);
    }

    if ($modele->fichier_modele) {
        $path = storage_path('app/public/' . $modele->fichier_modele);

        if (file_exists($path)) {
            $phpWord = IOFactory::load($path, 'Word2007');
            
            // Fonction pour extraire le texte avec les styles
            $extractStyledText = function($element) use (&$extractStyledText) {
                $html = '';
                
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text = htmlspecialchars($textElement->getText());
                            $fontStyle = $textElement->getFontStyle();
                            
                            $styles = [];
                            $openTags = [];
                            $closeTags = [];
                            
                            if ($fontStyle) {
                                // Gras
                                if ($fontStyle->isBold()) {
                                    $openTags[] = '<strong>';
                                    $closeTags[] = '</strong>';
                                }
                                
                                // Italique
                                if ($fontStyle->isItalic()) {
                                    $openTags[] = '<em>';
                                    $closeTags[] = '</em>';
                                }
                                
                                // Souligné
                                if ($fontStyle->getUnderline() && $fontStyle->getUnderline() !== 'none') {
                                    $openTags[] = '<u>';
                                    $closeTags[] = '</u>';
                                }
                                
                                // Taille de police
                                if ($fontStyle->getSize()) {
                                    $styles[] = 'font-size: ' . $fontStyle->getSize() . 'pt';
                                }
                                
                                // Couleur
                                if ($fontStyle->getColor()) {
                                    $styles[] = 'color: #' . $fontStyle->getColor();
                                }
                                
                                // Police
                                if ($fontStyle->getName()) {
                                    $styles[] = 'font-family: "' . $fontStyle->getName() . '"';
                                }
                            }
                            
                            // Construire le HTML
                            if (!empty($styles)) {
                                $html .= '<span style="' . implode('; ', $styles) . '">';
                                $html .= implode('', $openTags) . $text . implode('', array_reverse($closeTags));
                                $html .= '</span>';
                            } else {
                                $html .= implode('', $openTags) . $text . implode('', array_reverse($closeTags));
                            }
                        }
                    }
                } else if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                    $text = htmlspecialchars($element->getText());
                    $fontStyle = $element->getFontStyle();
                    
                    $styles = [];
                    $openTags = [];
                    $closeTags = [];
                    
                    if ($fontStyle) {
                        if ($fontStyle->isBold()) {
                            $openTags[] = '<strong>';
                            $closeTags[] = '</strong>';
                        }
                        
                        if ($fontStyle->isItalic()) {
                            $openTags[] = '<em>';
                            $closeTags[] = '</em>';
                        }
                        
                        if ($fontStyle->getUnderline() && $fontStyle->getUnderline() !== 'none') {
                            $openTags[] = '<u>';
                            $closeTags[] = '</u>';
                        }
                        
                        if ($fontStyle->getSize()) {
                            $styles[] = 'font-size: ' . $fontStyle->getSize() . 'pt';
                        }
                        
                        if ($fontStyle->getColor()) {
                            $styles[] = 'color: #' . $fontStyle->getColor();
                        }
                        
                        if ($fontStyle->getName()) {
                            $styles[] = 'font-family: "' . $fontStyle->getName() . '"';
                        }
                    }
                    
                    if (!empty($styles)) {
                        $html .= '<span style="' . implode('; ', $styles) . '">';
                        $html .= implode('', $openTags) . $text . implode('', array_reverse($closeTags));
                        $html .= '</span>';
                    } else {
                        $html .= implode('', $openTags) . $text . implode('', array_reverse($closeTags));
                    }
                }
                
                return $html;
            };
            
            // Construire le HTML
            $html = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof \PhpOffice\PhpWord\Element\TextRun || 
                        $element instanceof \PhpOffice\PhpWord\Element\Text) {
                        $html .= '<p>' . $extractStyledText($element) . '</p>';
                    } else if (method_exists($element, 'getElements')) {
                        $html .= '<p>';
                        foreach ($element->getElements() as $childElement) {
                            $html .= $extractStyledText($childElement);
                        }
                        $html .= '</p>';
                    } else if (method_exists($element, 'getText')) {
                        $html .= '<p>' . htmlspecialchars($element->getText()) . '</p>';
                    }
                }
            }
            
            // Remplacer [variable] par des inputs
            $html = preg_replace_callback('/\[([^\]]+)\]/', function ($matches) {
                $name = trim($matches[1]);
                return '<input type="text" name="' . $name . '" class="variable-field" placeholder="' . $name . '" value="">';
            }, $html);
            
            // Envelopper dans un HTML complet avec styles
            $finalHtml = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
            font-family: "Calibri", "Times New Roman", Arial, sans-serif;
            line-height: 1.6;
            background: white;
        }
        
        p {
            margin: 8px 0;
            text-align: justify;
        }
        
        .variable-field {
            border: none;
            border-bottom: 2px dashed #007bff;
            padding: 3px 10px;
            margin: 0 3px;
            min-width: 200px;
            background: rgba(0, 123, 255, 0.05);
            color: #007bff;
            font-family: inherit;
            font-size: inherit;
            font-weight: inherit;
            font-style: inherit;
            text-decoration: inherit;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .variable-field:focus {
            outline: none;
            border-bottom: 2px solid #0056b3;
            background: rgba(0, 123, 255, 0.1);
            box-shadow: 0 2px 5px rgba(0, 123, 255, 0.2);
        }
        
        .variable-field::placeholder {
            color: #6c757d;
            opacity: 0.6;
        }
        
        /* Préserver les styles dans les inputs */
        strong .variable-field,
        p strong .variable-field {
            font-weight: bold !important;
        }
        
        em .variable-field,
        p em .variable-field {
            font-style: italic !important;
        }
        
        u .variable-field,
        p u .variable-field {
            text-decoration: underline !important;
        }
        
        /* Styles de base pour le formatage */
        strong, b {
            font-weight: bold;
        }
        
        em, i {
            font-style: italic;
        }
        
        u {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    ' . $html . '
</body>
</html>';

            return response()->json([
                'type' => 'html',
                'html' => $finalHtml
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Fichier non trouvé sur le serveur'
            ], 404);
        }
    } else {
        return response()->json([
            'type' => 'error',
            'message' => 'Aucun fichier Word pour ce modèle'
        ], 400);
    }
}

    // méthode pour obtenir le contenu d'une clause et le retourner en HTML avec les variables remplacées par des champs input
   public function getContenuClause($id)
{
    $clause = Clausier::find($id);
    if (!$clause) {
        return response()->json([
            'type' => 'error',
            'message' => 'Clause non trouvée'
        ], 404);
    }

    // Remplacement des variables [nomVariable] par des <input>
    $pattern = '/\[(.*?)\]/';
    $html = nl2br(preg_replace_callback($pattern, function ($matches) {
        $name = $matches[1];
        return "<input type='text' name='$name' class='variable-field' placeholder='$name'>";
    }, $clause->contenu));

    return response()->json([
        'type' => 'html',
        'html' => $html
    ]);
}

    public function update(Request $request, Contrat $contrat)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'type_contrat_id' => 'required|exists:types_contrats,id',
            'annee_fiscale_id' => 'required|exists:annees_fiscales,id',
            'date_debut' => 'required|date',
            'description' => 'nullable|string',
            'notes_generales' => 'nullable|string',
            'type_renouvelement' => 'nullable|string',
            'duree_auto_renouvellement' => 'nullable|string',
            'jours_preavis_resiliation' => 'nullable|integer',
            'contractants' => 'nullable|array',
            'clauses' => 'nullable|array',
        ]);

        $contrat->update([
            'titre' => $data['titre'],
            'type_contrat_id' => $data['type_contrat_id'],
            'annee_fiscale_id' => $data['annee_fiscale_id'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'] ?? $data['date_debut'],
             'statut' => 'ebauche',
            'description' => $data['description'] ?? '',
            'notes_generales' => $data['notes_generales'] ?? null,
            'type_renouvelement' => $data['type_renouvelement'] ?? null,
            'duree_auto_renouvellement' => $data['duree_auto_renouvellement'] ?? null,
            'jours_preavis_resiliation' => $data['jours_preavis_resiliation'] ?? null,
        ]);

        if (!empty($data['contractants'])) {
            $contrat->contractants()->sync($data['contractants']);
        }

        if (!empty($data['clauses'])) {
            $contrat->clausesContrat()->delete();
            foreach ($data['clauses'] as $clauseId) {
                $contrat->clausesContrat()->create([
                    'clause_id' => $clauseId,
                    'variables' => json_encode([]),
                ]);
            }
        }

        return redirect()->route('contrats.index')
            ->with('success', 'Contrat mis à jour avec succès.');
    }
    

//     function ebauche(Request $request, Contrat $contrat){
//     $contrat->update([
//         'statut' => 'ebauche',
//         'type_contrat_id' => $request->input('type_contrat_id'),
//     ]);


//     return redirect()->route('contrats.index', $contrat->id)
//         ->with('success', 'Le contrat a été remis en ébauche, vous pouvez maintenant le reviser.');
// }

   

   // liste des contrats en attente d'approbation pour l'approbateur
    public function approbationList()
    {
        $userId = auth()->id();
        $contrats = Contrat::forApprover($userId)->with('workflowEtapes.etape')->get();
    

        return view('contrats.approbation', compact('contrats'));
    }

    public function voir($id)
    {
        $contrat = Contrat::with(['workflowEtapes.etape', 'contractants'])->findOrFail($id);

        if (!auth()->user()->hasRole('super_admin') && !$contrat->workflowEtapes->where('approver_id', auth()->id())->count()) {
            abort(403, "Vous n’avez pas accès à ce contrat.");
        }

        // On récupère le contenu généré
        $contenu = $contrat->contenu_document; // ou ->contenu_final selon le nom du champ

        return view('contrats.voir', compact('contrat', 'contenu'));
    }



    public function show($id)
    {
        
        $contrat = Contrat::with(['contractants', 'workflowEtapes'])->findOrFail($id);


        if ($notificationId = request('notification_id')) {
            $notification = auth()->user()->notifications()->where('id', $notificationId)->first();

            if ($notification) {
                $notification->markAsRead();
                \Log::info('Notification marquée comme lue : '.$notification->id);
            } else {
                \Log::warning('Notification introuvable : '.$notificationId);
            }
        }

        // Recalculer les notifications non lues
        $notifications = auth()->user()->notifications()->latest()->take(5)->get();
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('contrats.show', compact('contrat', 'notifications', 'unreadCount'));
    }


}
