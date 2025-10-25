<?php

namespace App\Http\Controllers;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\Contrat;
use App\Models\Societe;
use App\Models\Abonnement;
use App\Models\Paiement;


use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
        {       $user = Auth::user(); 
                if ($user->hasRole('super_admin')) {
                    
                    // Dashboard super admin
                    // Total sociétés
        $totalSocietes = Societe::count();

        // Nouvelles sociétés ce mois-ci
        $newSocietesMois = Societe::whereMonth('created_at', now()->month)->count();

        // Abonnements actifs
        $abonnementsActifs = Abonnement::where('statut', 'actif')->count();

        // Nouveaux abonnements actifs ce mois
        $nouveauxActifs = Abonnement::where('statut', 'actif')
            ->whereMonth('created_at', now()->month)
            ->count();

        // Abonnements expirés
        $abonnementsExpirés = Abonnement::where('statut', 'expiré')->count();

        // Revenus totaux (ex: somme de tous les paiements)
        // $revenusTotaux = Paiement::sum('montant');

        // Graphiques
        $mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

        $societesParMois = [];
        foreach(range(1,12) as $m){
            $societesParMois[] = Societe::whereMonth('created_at', $m)->count();
        }

        $abonnementsRepartition = [
            Abonnement::where('plan', 'Free')->count(),
            Abonnement::where('plan', 'Pro')->count(),
            Abonnement::where('plan', 'Premium')->count(),
        ];

        // Sociétés récentes
        $recentSocietes = Societe::latest()->take(5)->get();
     return view('dashboard.super_admin', compact('user','totalSocietes',
            'newSocietesMois',
            'abonnementsActifs',
            'nouveauxActifs',
            'abonnementsExpirés',
            // 'revenusTotaux',
            'mois',
            'societesParMois',
            'abonnementsRepartition',
            'recentSocietes'));
                }
                // Dashboard abonné
               
                $societeId = $user->societe_id ?? null;
                $query = Contrat::query();
                // Filtrer par société si ce n'est pas un Super Admin
            if (!$user->hasRole('Super Admin') && $societeId) {
                $query->where('societe_id', $societeId);
            }

                // --- Statistiques dynamiques ---
                $totalContrats = (clone $query)->count();
                $contratsActifs = (clone $query)->where('statut', 'actif')->count();
                $contratsPreparation = (clone $query)->where('statut', 'approbation')->count();
                $contratsValides = (clone $query)->where('statut', 'approuvé')->count();

                // Pour le graphique 1 (répartition des statuts)
                $contratsParStatut = (clone $query)
                    ->selectRaw('statut, COUNT(*) as total')
                    ->groupBy('statut')
                    ->pluck('total', 'statut');

                //  Pour le graphique 2 (contrats qui expirent bientôt)
                $contratsEcheance = (clone $query)
                    ->whereNotNull('date_fin')
                    ->get()
                    ->groupBy(function ($contrat) {
                        if (now()->diffInDays($contrat->date_fin, false) < 0) {
                            return 'Expiré';
                        } elseif (now()->diffInDays($contrat->date_fin) <= 30) {
                            return 'Moins de 30 jours';
                        } else {
                            return 'Plus de 30 jours';
                        }
                    })
                    ->map->count();
                    // Remplir le tableau pour les contats recents.
                     $recentContrats = Contrat::with(['typesContrat', 'workflow.etapes.user'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

                     $totalContratsMois = Contrat::whereMonth('created_at', now()->month)->count();
            return view('abonne', compact('user',
                'totalContrats',
                'contratsActifs',
                'contratsPreparation',
                'contratsValides',
                'contratsParStatut',
                'contratsEcheance',
                'recentContrats',
                'totalContratsMois'));
}
    
}
