<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\AnneeFiscaleController;
use App\Http\Controllers\TypePieceJointeController;
use App\Http\Controllers\TypeClauseContratController;
use App\Http\Controllers\TypeContractantController;
use App\Http\Controllers\ContractantController;
use App\Http\Controllers\TypeContratController;
use App\Http\Controllers\ClausierController;
use App\Http\Controllers\RepresentantsLegauxController;
use App\Http\Controllers\ModeleContratController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\WorkflowEtapeController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\SignatureController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', function () {
    return redirect()->route('dashboard', ['locale' => config('app.locale')]);
});

Route::get('/lang/{locale}', function ($locale) {
    $previousUrl = url()->previous();             // ex: http://localhost/fr/home
    $parsed = parse_url($previousUrl, PHP_URL_PATH); // ex: /fr/home

    $segments = explode('/', trim($parsed, '/')); // ["fr", "home"]

    if (count($segments) > 0) {
        $segments[0] = $locale; // remplacer "fr" par "en"
    }

    $newUrl = url(implode('/', $segments)); // ex: http://localhost/en/home

    return redirect($newUrl);
})->name('lang.switch');



Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath','setlocale',  ]
], function () {

    Auth::routes();


Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

//Routes pour les sociétés
Route::resource('societes', SocieteController::class);



//Routes pour les rôles et permissions
 Route::resource('roles', RoleController::class);


 //Routes pour les utilisateurs
 Route::resource('users', UserController::class);
 Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
 Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

 // routes années fiscales
Route::get('/annees_fiscales/create', [AnneeFiscaleController::class, 'create'])->name('annees.creatje');
Route::get('/parametrage/annees_fiscales/create', [AnneeFiscaleController::class, 'index'])->name('annees.create');
Route::post('/annees_fiscales', [AnneeFiscaleController::class, 'store'])->name('annees.store');
Route::get('/annees_fiscales/{anneeFiscale}/edit', [AnneeFiscaleController::class, 'edit'])->name('annees.edit');
Route::put('/annees_fiscales/{anneeFiscale}', [AnneeFiscaleController::class, 'update'])->name('annees.update');
Route::delete('/annees_fiscales/{anneeFiscale}', [AnneeFiscaleController::class, 'destroy'])->name('annees.destroy');


// routes pieces jointes
//Route::get('/parametrage', [TypePieceJointeController::class, 'index'])->name('pieces.index');
Route::prefix('types_pieces_jointes')->group(function () {
Route::get('/', [TypePieceJointeController::class, 'index'])->name('pieces.index');
Route::post('/', [TypePieceJointeController::class, 'store'])->name('pieces.store');
Route::put('/{piece}', [TypePieceJointeController::class, 'update'])->name('pieces.update');
Route::delete('/{piece}', [TypePieceJointeController::class, 'destroy'])->name('pieces.destroy');
});

// routes parametrage
Route::get('/parametrage', [ConfigurationController::class, 'index'])->name('parametrage');

// routes types clauses contrats
Route::resource('types_clauses_contrats', TypeClauseContratController::class)->names('clauses')->except(['create', 'edit', 'show']);

// routes representants legaux
Route::resource('representants',  RepresentantsLegauxController::class)->except(['create', 'show', 'edit']);



// routes contractants
Route::resource('types_contractants', TypeContractantController::class)->except(['create', 'show', 'edit']);


// routes  contractants
Route::resource('contractants', ContractantController::class);

// routes  type contrats
Route::resource('types_contrats', TypeContratController::class);

// routes clausiers
Route::resource('clausiers', ClausierController::class);

// routes modeles contrats
Route::resource('modeles_contrats', ModeleContratController::class);
Route::get('/modeles_contrats/{id}/clauses', [ModeleContratController::class, 'getClauses'])->name('modeles_contrats.clauses');



// routes workflows et etapes
Route::resource('workflows', WorkflowController::class);
Route::resource('workflows.etapes', WorkflowEtapeController::class)->shallow();
Route::resource('workflows.etapes', WorkflowEtapeController::class)->except(['index']);;
// Route dans web.php
//Route::get('/workflow/{id}/etapes', [WorkflowController::class, 'getEtapes']);


Route::post('/contrats/{contrat}/assign-workflow', [ContratController::class, 'assignWorkflow'])
    ->name('contrats.assignWorkflow');

Route::get('/workflows/{workflow}/etapes/ajax', [WorkflowEtapeController::class, 'getEtapes'])
    ->name('workflows.etapes.ajax');








Route::get('contrats/approbation', [ContratController::class, 'approbationList'])->name('contrats.approbation');
Route::get('/contrats/{contrat}/voir', [ContratController::class, 'voir'])->name('contrats.voir');
Route::post('contrats/{contrat}/approuver', [ContratController::class, 'approuver'])->name('contrats.approuver');
Route::post('/contrats/{contrat}/assign-workflow', [ContratController::class, 'assignWorkflow'])->name('contrats.assignWorkflow');
// Route::post('/contrats/{contrat}/marquer-signature', [ContratController::class, 'marquerSignature'])->name('contrats.marquerSignature');
Route::get('/contrats/modele-content/{id}', [ContratController::class, 'getModeleContratContent']);
Route::get('/clausiers/contenu/{id}', [ContratController::class, 'getContenuClause']);
Route::post('/contrats/{id}/signatures', [SignatureController::class, 'marquerSignature'])->name('contrats.signatures.store');
// Route::get('contrats/{contrat}/remplir-modele/{modele}', [ContratController::class, 'remplirModele'])->name('contrats.remplirModele');
// Route::post('contrats/{contrat}/generer-modele/{modele}', [ContratController::class, 'genererModele'])->name('contrats.genererModele');

Route::post('contrats/store-draft', [ContratController::class, 'storeDraft'])->name('contrats.storeDraft');

Route::resource('contrats', ContratController::class)->only([
    'index', 'show', 'edit', 'update', 'destroy'
]);

Route::patch('/contrats/{contrat}/reviser', [ContratController::class, 'reviser'])->name('contrats.reviser');
Route::patch('/contrats/{contrat}/ebauche', [ContratController::class, 'ebauche'])->name('contrats.ebauche');








});
