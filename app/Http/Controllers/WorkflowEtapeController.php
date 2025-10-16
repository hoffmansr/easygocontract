<?php
namespace App\Http\Controllers;
use App\Models\Workflow;
use App\Models\Workflow_etape;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WorkflowEtapeController extends Controller

{
    public function create(Workflow $workflow)
    {
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);
        // Liste des users de la même société pouvant être approbateurs
        $users = User::where('societe_id', Auth::user()->societe_id)
                     ->whereIn('user_type', ['admin','collaborateur']) // filtre optionnel
                     ->get();
        return view('workflows.etapes.create', compact('workflow','users'));
    }

    public function store(Request $request, Workflow $workflow)
    {
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);

        $data = $request->validate([
            'niveau'  => 'required|integer|min:1',
            'libelle' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Si user_id donné, vérifier qu'il appartient à la société
        if (!empty($data['user_id'])) {
            $user = User::find($data['user_id']);
            if (!$user || $user->societe_id != Auth::user()->societe_id) {
                return redirect()->back()->withErrors(['user_id' => 'Utilisateur invalide.']);
            }
        }

        $workflow->etapes()->create($data);

        return redirect()->route('workflows.show', $workflow->id)->with('success','Étape ajoutée.');
    }

    public function edit(Workflow $workflow, Workflow_etape $etape)
    {
       
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);
        $users = User::where('societe_id', Auth::user()->societe_id)
                     ->whereIn('user_type', ['admin','collaborateur'])
                     ->get();
        return view('workflows.etapes.edit', compact('etape','users','workflow'));
    }

    public function update(Request $request, Workflow $workflow, Workflow_etape $etape)
    {
        
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);

        $data = $request->validate([
            'niveau'  => 'required|integer|min:1',
            'libelle' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($data['user_id'])) {
            $user = User::find($data['user_id']);
            if (!$user || $user->societe_id != Auth::user()->societe_id) {
                return redirect()->back()->withErrors(['user_id' => 'Utilisateur invalide.']);
            }
        }

        $etape->update($data);
        return redirect()->route('workflows.show', $workflow->id)->with('success','Étape mise à jour.');
    }

    public function show(Workflow $workflow, Workflow_etape $etape)
    {
        
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);
        return view('workflows.etapes.show', compact('etape','workflow'));
    }

    public function destroy(Workflow $workflow, Workflow_etape $etape)
    {
       
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);
        $etape->delete();
        return redirect()->route('workflows.show', $workflow->id)->with('success','Étape supprimée.');
    }

    // show/index non nécessaires ici si gérés depuis workflows.show

    public function index(Workflow $workflow)
    {
        $workflow = Workflow::with(['etapes.user'])->findOrFail($workflow);

        return response()->json($workflow->etapes);
    }

    public function getEtapes($workflowId)
{
    $workflow = Workflow::with('etapes.user')->findOrFail($workflowId);
    return response()->json($workflow->etapes);
}



}
