<?php

namespace App\Http\Controllers;
use App\Models\Annees_fiscale;
use App\Models\Types_pieces_jointe;
use App\Models\Types_clauses_contrat;
use App\Models\Types_contractant;
use App\Models\Representants_legaux;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
       $societeId = Auth::user()->societe_id;

                $annees = Annees_fiscale::where('societe_id', $societeId)->orderBy('annee', 'desc')->get();
                $typesPieces = Types_pieces_jointe::where('societe_id', $societeId)->orderBy('libelle')->get();
                $typesClauses = Types_clauses_contrat::where('societe_id', Auth::user()->societe_id)->get();
                $typesContractants = Types_contractant::where('societe_id', Auth::user()->societe_id)->get();
                $representants = Representants_legaux::where('societe_id', Auth::user()->societe_id)->get();
       

        return view('configuration.parametrage',  compact('annees', 'typesPieces', 'typesClauses', 'typesContractants','representants')); 
    }

   
}
