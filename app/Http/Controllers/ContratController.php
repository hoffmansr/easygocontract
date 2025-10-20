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
            'statut' => 'ebauche',
            'titre' => 'Nouveau contrat',
            'workflow_id' => null,
            'date_debut' => now(), 
            'date_fin' => now()->addYear(), // par défaut 1 an plus tard
            'notif_contractant' => false,
            'type_contrat_id' => null,
            'annee_fiscale_id' => null,
            'description' => '',
            'notes_generales' => null,
            'type_renouvelement' => 'type',
            'duree_auto_renouvellement' => null,

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




    // public function create()
    // {
    //     $societeId = auth()->user()->societe_id ?? null;

    // $modelesContrat = Modeles_contrat::when($societeId, fn($q) => $q->where('societe_id', $societeId))->get();
    // $bibliothequeClauses = Clausier::when($societeId, fn($q) => $q->where('societe_id', $societeId))->get();
    //  $clausesContrat = $contrat->clausesContrats()->with(['modeleContrat'])->get();
    // $typesContrats = Types_contrat::when($societeId, fn($q) => $q->where('societe_id', $societeId))->get();
    // $contractants = Contractant::when($societeId, fn($q) => $q->where('societe_id', $societeId))->get();
    // $typesContractants = Types_contractant::when($societeId, fn($q) => $q->where('societe_id', $societeId))->get();
    // $workflows = Workflow::when($societeId, fn($q) => $q->where('societe_id', $societeId))->get();
    // $representants = Representants_legaux::when($societeId, fn($q) => $q->where('societe_id', $societeId))->get();
    // $anneesFiscales = Annees_fiscale::where('societe_id', $societeId)->get();
    
    

    // return view('contrats.create', compact(
    //     'modelesContrat',
    //     'bibliothequeClauses',
    //     'typesContrats',
    //     'contractants',
    //     'typesContractants',
    //     'workflows',
    //     'representants',
    //     'anneesFiscales'
    // ));
    // }
 public function assignWorkflow(Request $request, $id)
{
    $contrat = Contrat::findOrFail($id);

    $request->validate([
        'workflow_id' => 'required|exists:workflows,id',
    ]);

    //  Mise à jour du contrat
    $contrat->update([
        'workflow_id' => $request->workflow_id,
        'statut' => 'en_approbation',
        'notif_contractant' => $request->has('notif_contractant'),
    ]);

    //  Créer les étapes du workflow pour ce contrat
    $workflow = Workflow::with('etapes')->findOrFail($request->workflow_id);

    foreach ($workflow->etapes as $etape) {
        ContratWorkflowEtape::create([
            'contrat_id' => $contrat->id,
            'workflow_etape_id' => $etape->id,
            'approver_id' => $etape->user_id ?? null,
            'statut' => 'en_attente',
        ]);
    }

    //  Notifications aux approbateurs
    foreach ($contrat->workflowEtapes as $etapeContrat) {
        if ($etapeContrat->approver) {
            $etapeContrat->approver->notify(
                (new \App\Notifications\ContratEnApprobation($contrat))->delay(now()->addSeconds(5))
            );
        }
    }

    //  Notifications aux contractants (optionnel)
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




//     public function store(Request $request)
//     {
//         $data = $request->validate([
//             'titre' => 'required|string|max:255',
//             'type_contrat_id' => 'required|exists:types_contrats,id',
//             'annee_fiscale_id' => 'required|exists:annees_fiscales,id',
//             'date_debut' => 'required|date',
//             'description' => 'nullable|string',
//             'notes_generales' => 'nullable|string',
//             'type_renouvelement' => 'nullable|string',
//             'duree_auto_renouvellement' => 'nullable|string',
//             'jours_preavis_resiliation' => 'nullable|integer',
//             'contractants' => 'nullable|array', // contractants sélectionnés
//             'clauses' => 'nullable|array',      // clauses sélectionnées
//         ]);

//         // Création du contrat
//         $contrat = Contrat::create([
//             'titre' => $data['titre'],
//             'type_contrat_id' => $data['type_contrat_id'],
//             'annee_fiscale_id' => $data['annee_fiscale_id'],
//             'date_debut' => $data['date_debut'],
//             'date_fin' => $data['date_fin'] ?? $data['date_debut'], // ou null si tu rends nullable
//             'description' => $data['description'] ?? '', // <--- valeur vide par défaut
//             'notes_generales' => $data['notes_generales'] ?? null,
//             'type_renouvelement' => $data['type_renouvelement'] ?? null,
//             'duree_auto_renouvellement' => $data['duree_auto_renouvellement'] ?? null,
//             'jours_preavis_resiliation' => $data['jours_preavis_resiliation'] ?? null,
//             'statut' => 'ebauche',
//             'societe_id' => auth()->user()->societe_id,
//             'notif_contractant' => $data['notif_contractant'] ?? false, // ← important !
//         ]);

//         // Vérification contractants
//         if (!empty($data['contractants'])) {
//             $contrat->contractants()->sync($data['contractants']);
//         }

//         // Vérification clauses
//         if (!empty($data['clauses'])) {
//             foreach ($data['clauses'] as $clauseId) {
//                 $contrat->clausesContrat()->create([
//                     'clause_id' => $clauseId,
//                     'variables' => json_encode([]),
//                 ]);
//             }
//         }

//         // Vérif dans le log
//         \Log::info('Contrat créé', [
//         'id' => $contrat->id,
//         'contractants' => $contrat->contractants()->pluck('contractants.id'), // <-- précisé
//         'clauses' => $contrat->clausesContrat()->pluck('id')
//     ]);


//         return redirect()->route('contrats.index')
//             ->with('success', 'Contrat enregistré en ébauche avec succès.');
// }



// Formulaire édition
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

            // Convertir le fichier Word en HTML
            if (file_exists($path)) {
                $phpWord = IOFactory::load($path, 'Word2007');
                $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
                ob_start();
                $htmlWriter->save('php://output');
                $html = ob_get_clean();

                // Remplacer les variables [nomVariable] par des <input>
                $pattern = '/\[(.*?)\]/';
                $html = preg_replace_callback($pattern, function ($matches) {
                    $name = $matches[1];
                    return "<input type='text' name='$name' class='variable-field' placeholder='$name'>";
                }, $html);

                return response()->json([
                    'type' => 'html',
                    'html' => $html
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

        public function reviser(Contrat $contrat)
    {
        $contrat->update([
            'statut' => 'revisé'
        ]);

        return redirect()->route('contrats.edit', $contrat->id)
            ->with('success', 'Le contrat a été marqué comme révisé, vous pouvez maintenant le modifier.');
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



//recupere les etapes pour utilisateur(approbateur)
// public static function forApprover($userId)
// {
//     return self::whereHas('workflowEtapes', function($q) use ($userId) {
//         $q->where('approver_id', $userId)
//           ->where('statut', 'en_attente');
//     });
// }


//approuver une etape
public function approuver(Request $request, $id)
{
    $contrat = Contrat::with('workflowEtapes')->findOrFail($id);
    $userId = auth()->id();

    //  Chercher l’étape en attente de cet utilisateur
    $etapeContrat = $contrat->workflowEtapes()
        ->where('approver_id', $userId)
        ->where('statut', 'en_attente')
        ->first();

    if (!$etapeContrat) {
        return back()->with('error', "Aucune étape en attente pour vous.");
    }

    //  Valider l’étape
    $etapeContrat->update(['statut' => 'approuvé']);

    //  Vérifier s’il reste encore des étapes en attente
    $reste = $contrat->workflowEtapes()->where('statut', 'en_attente')->count();

    if ($reste == 0) {
        // toutes les étapes validées -> contrat validé
        $contrat->update(['statut' => 'approuvé']);
    }

     return redirect()->route('contrats.index')
    ->with('success', "Étape validée avec succès !");
}

// //singer un contrat (marquer comme signé)
// public function marquerSignature(Request $request, $id)
// {
//     $contrat = Contrat::findOrFail($id);

//     $request->validate([
//         'signature_entity_id' => 'required|exists:representants_legaux,id',
//     ]);

//     // Marquer signé
//     $contrat->signature_entity_id = $request->input('signature_entity_id');
//     $contrat->statut = 'signé';
//     $contrat->save();

//     // Vérifier si toutes les étapes du workflow sont approuvées
//     $allEtapesValidees = $contrat->workflowEtapes()->where('statut', '!=', 'approuvé')->count() === 0;

//     if ($allEtapesValidees) {
//         $contrat->statut = 'actif';
//         $contrat->save();
//     }

//     return redirect()->route('contrats.edit', $contrat->id)
//         ->with('success', 'Contrat marqué signé.' . ($allEtapesValidees ? ' Statut maintenant actif.' : ''));
// }





public function show($id)
{
    $contrat = Contrat::findOrFail($id);

    // Marquer la notification comme lue si elle existe dans la requête
 

if ($notificationId = request('notification_id')) {
    $notification = auth()->user()->notifications()->where('id', $notificationId)->first();

    if ($notification) {
        $notification->markAsRead();
        Log::info('Notification marquée comme lue : '.$notification->id);
    } else {
        Log::warning('Notification introuvable : '.$notificationId);
    }
}


    return view('contrats.show', compact('contrat'));
}

}
