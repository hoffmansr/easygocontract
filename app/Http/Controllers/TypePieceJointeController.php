<?php
namespace App\Http\Controllers;
use App\Models\Types_pieces_jointe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TypePieceJointeController extends Controller
{
    // public function index()
    // {
    //     $types = Types_pieces_jointe::where('societe_id', Auth::user()->societe_id)->get();
    //     return view('pieces.index', compact('types'));
    // }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        $data['societe_id'] = Auth::user()->societe_id;
        Types_pieces_jointe::create($data);

        return redirect()->back()->with('success', 'Type de pièce jointe ajouté avec succès.');
    }


            public function update(Request $request, Types_pieces_jointe $piece)
    {
        if ($piece->societe_id != Auth::user()->societe_id) abort(403);

        $data = $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        $piece->update($data);

        return redirect()->back()->with('success', 'Type mis à jour.');
    }


        public function destroy(Types_pieces_jointe $piece)
        { 
            if ($piece->societe_id != Auth::user()->societe_id) abort(403);

            $piece->delete();

            return redirect()->back()->with('success', 'Type de pièce jointe supprimé.');
        }
}
