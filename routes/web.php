<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    HomeController,
    SocieteController,
    RoleController,
    UserController,
    ConfigurationController,
    AnneeFiscaleController,
    TypePieceJointeController,
    TypeClauseContratController,
    TypeContractantController,
    ContractantController,
    TypeContratController,
    ClausierController,
    RepresentantsLegauxController,
    ModeleContratController,
    WorkflowController,
    WorkflowEtapeController,
    ContratController,
    SignatureController
};

// Page d’accueil redirige vers le tableau de bord avec la langue par défaut
Route::get('/', function () {
    return redirect()->route('dashboard', ['locale' => config('app.locale')]);
});
Route::get('/onepage', function () {
    return view('onePage');
});
// Changement de langue
Route::get('/lang/{locale}', function ($locale) {
    $previousUrl = url()->previous();
    $parsed = parse_url($previousUrl, PHP_URL_PATH);
    $segments = explode('/', trim($parsed, '/'));

    if (count($segments) > 0) {
        $segments[0] = $locale;
    }

    $newUrl = url(implode('/', $segments));
    return redirect($newUrl);
})->name('lang.switch');

// Groupe principal avec localisation
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'setlocale']
], function () {

    // Auth Laravel (login, register, etc.)
    Auth::routes();

    /**
     * 🚀 GROUPE DE ROUTES PROTÉGÉES PAR AUTHENTIFICATION
     */
    Route::middleware(['auth'])->group(function () {

        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

        // Sociétés
        Route::resource('societes', SocieteController::class);

        // Rôles et permissions
        Route::resource('roles', RoleController::class);

        // Utilisateurs
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

        // Années fiscales
        Route::get('/annees_fiscales/create', [AnneeFiscaleController::class, 'create'])->name('annees.creatje');
        Route::get('/parametrage/annees_fiscales/create', [AnneeFiscaleController::class, 'index'])->name('annees.create');
        Route::post('/annees_fiscales', [AnneeFiscaleController::class, 'store'])->name('annees.store');
        Route::get('/annees_fiscales/{anneeFiscale}/edit', [AnneeFiscaleController::class, 'edit'])->name('annees.edit');
        Route::put('/annees_fiscales/{anneeFiscale}', [AnneeFiscaleController::class, 'update'])->name('annees.update');
        Route::delete('/annees_fiscales/{anneeFiscale}', [AnneeFiscaleController::class, 'destroy'])->name('annees.destroy');

        // Types pièces jointes
        Route::prefix('types_pieces_jointes')->group(function () {
            Route::get('/', [TypePieceJointeController::class, 'index'])->name('pieces.index');
            Route::post('/', [TypePieceJointeController::class, 'store'])->name('pieces.store');
            Route::put('/{piece}', [TypePieceJointeController::class, 'update'])->name('pieces.update');
            Route::delete('/{piece}', [TypePieceJointeController::class, 'destroy'])->name('pieces.destroy');
        });

        // Paramétrage global
        Route::get('/parametrage', [ConfigurationController::class, 'index'])->name('parametrage');

        // Types de clauses
        Route::resource('types_clauses_contrats', TypeClauseContratController::class)
            ->names('clauses')
            ->except(['create', 'edit', 'show']);

        // Représentants légaux
        Route::resource('representants', RepresentantsLegauxController::class)->except(['create', 'show', 'edit']);

        // Types de contractants
        Route::resource('types_contractants', TypeContractantController::class)->except(['create', 'show', 'edit']);

        // Contractants
        Route::resource('contractants', ContractantController::class);

        // Types de contrats
        Route::resource('types_contrats', TypeContratController::class);

        // Clausiers
        Route::resource('clausiers', ClausierController::class);

        // Modèles de contrats
        Route::resource('modeles_contrats', ModeleContratController::class);
        Route::get('/modeles_contrats/{id}/clauses', [ModeleContratController::class, 'getClauses'])
            ->name('modeles_contrats.clauses');

        // Workflows et etape
         Route::get('/workflows/{workflow}/etapes/ajax', [WorkflowEtapeController::class, 'getEtapes'])
            ->name('workflows.etapes.ajax');
        Route::resource('workflows', WorkflowController::class);
        // Route::resource('workflows.etapes', WorkflowEtapeController::class)->shallow();
        Route::resource('workflows.etapes', WorkflowEtapeController::class)->except(['index']);;
       

        // Contrats
        
        Route::get('contrats/approbation', [ContratController::class, 'approbationList'])->name('contrats.approbation');
        Route::get('/contrats/{contrat}/voir', [ContratController::class, 'voir'])->name('contrats.voir');
        Route::post('contrats/{contrat}/approuver', [ContratController::class, 'approuver'])->name('contrats.approuver');
        Route::post('/contrats/{contrat}/assign-workflow', [ContratController::class, 'assignWorkflow'])->name('contrats.assignWorkflow');
        Route::get('/contrats/modele-content/{id}', [ContratController::class, 'getModeleContratContent']);
        Route::get('/clausiers/contenu/{id}', [ContratController::class, 'getContenuClause']);
        Route::post('/contrats/{id}/signatures', [SignatureController::class, 'marquerSignature'])->name('contrats.signatures.store');
        Route::post('contrats/store-draft', [ContratController::class, 'storeDraft'])->name('contrats.storeDraft');

        Route::resource('contrats', ContratController::class)->only([
            'index', 'show', 'edit', 'update', 'destroy'
        ]);

        Route::patch('/contrats/{contrat}/reviser', [ContratController::class, 'reviser'])->name('contrats.reviser');
        Route::patch('/contrats/{contrat}/ebauche', [ContratController::class, 'ebauche'])->name('contrats.ebauche');
    });
});
