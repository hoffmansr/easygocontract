<?php
namespace App\Http\Controllers;
use App\Models\Workflow;
use App\Models\Workflow_etape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    public function index()
    {
        $workflows = Workflow::with('etapes.user')
            ->where('societe_id', Auth::user()->societe_id)
            ->get();

        return view('workflows.index', compact('workflows'));
    }

    public function create()
    {
        return view('workflows.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'statut'  => 'required|in:actif,inactif',
        ]);
        $data['societe_id'] = Auth::user()->societe_id;
        Workflow::create($data);
        return redirect()->route('workflows.index')->with('success','Workflow créé.');
    }

    public function show(Workflow $workflow)
    {
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);
        $workflow->load('etapes.user');
        return view('workflows.show', compact('workflow'));
    }

    public function edit(Workflow $workflow)
    {
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);
        return view('workflows.edit', compact('workflow'));
    }

    public function update(Request $request, Workflow $workflow)
    {
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'statut'  => 'required|in:actif,inactif',
        ]);
        $workflow->update($data);
        return redirect()->route('workflows.index')->with('success','Workflow mis à jour.');
    }

    public function destroy(Workflow $workflow)
    {
        if ($workflow->societe_id != Auth::user()->societe_id) abort(403);
            // Supprimer les étapes d’abord
            $workflow->etapes()->delete();

            // Puis supprimer le workflow
            $workflow->delete();
        return redirect()->route('workflows.index')->with('success','Workflow supprimé.');
    }

// public function getEtapes($id)
// {
//     $workflow = Workflow::with(['etapes.user' => function($q){
//         $q->where('user_type', 'collaborateur'); // ou admin selon ton besoin
//     }])->findOrFail($id);

//     return response()->json($workflow->etapes);
// }







}
