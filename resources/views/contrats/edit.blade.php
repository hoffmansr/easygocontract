@extends('layouts.app1')

@section('content')
<div class="container bg-white">
    <div class="d-flex justify-content-between mb-3">
    <h4>Générer un contrat</h4>
    <div id="action-buttons" class="d-flex gap-3 mb-3">
    {{-- Bouton réviser --}}
    @if($contrat->statut !== 'revise')
        <form action="{{ route('contrats.reviser', $contrat->id) }}" method="POST" 
              onsubmit="return confirm('Confirmer la révision du contrat ?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-outline-info d-flex align-items-center gap-2 shadow-sm">
                📝 Marquer comme révisé
            </button>
        </form>
    @else
        <button type="button" class="btn btn-outline-info d-flex align-items-center gap-2 shadow-sm" disabled>
            ✔ Contrat déjà révisé
        </button>
    @endif

    {{-- Bouton annuler --}}
    <form action="" method="POST" 
          onsubmit="return confirm(' Voulez-vous vraiment annuler ce contrat ? Cette action est irréversible.')">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-x-circle-fill"></i> Annuler le contrat
        </button>
    </form>
</div>



</div>
<style>
    .stepper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 30px 0;
        padding: 0;
        list-style: none;
        counter-reset: step;
    }

    .stepper li {
        flex: 1;
        text-align: center;
        position: relative;
        font-size: 13px;
        color: #adb5bd;
        font-weight: 500;
    }

    .stepper li::before {
        content: counter(step);
        counter-increment: step;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border: 2px solid #dee2e6;
        display: block;
        text-align: center;
        margin: 0 auto 10px;
        border-radius: 50%;
        background: #f8f9fa;
        color: #6c757d;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .stepper li::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 3px;
        background: #dee2e6;
        z-index: -1;
    }

    .stepper li:first-child::after {
        left: 50%;
        width: 50%;
    }

    .stepper li:last-child::after {
        display: none;
    }

    /* ✅ Étape terminée */
    .stepper li.completed {
        color: #0d6efd;
    }

    .stepper li.completed::before {
        content: "✔";
        background: linear-gradient(135deg, #0d6efd, #4dabf7);
        border-color: #0d6efd;
        color: white;
    }

    .stepper li.completed::after {
        background: #0d6efd;
    }

    /* 🔥 Étape active */
    .stepper li.active {
        color: #198754;
        font-weight: bold;
    }

    .stepper li.active::before {
        border-color: #198754;
        background: linear-gradient(135deg, #198754, #20c997);
        color: white;
    }
</style>

@php
use Illuminate\Support\Str;

// Ordre des statuts (on garde les clés SANS accents, simples)
$etapes = [
    'ebauche' => 'Ébauche',
    'revise' => 'Révisé',
    'en_approbation' => 'En approbation',
    'approuve' => 'Approuvé',
    'signe' => 'Signé',
    'actif' => 'Actif',
    'annule' => 'Annulé',
    'expire' => 'Expiré',
    'renouvele' => 'Renouvelé',
];

// Normaliser le statut du contrat

$statutNormalise = normalize($contrat->statut);
$statutActuel = array_search($statutNormalise, array_keys($etapes));
@endphp

<ul class="stepper">
    @foreach($etapes as $key => $label)
        @php
            $index = array_search($key, array_keys($etapes));
            $class = '';

            if ($index < $statutActuel) {
                $class = 'completed';
            } elseif ($index == $statutActuel) {
                $class = 'active';
            }
        @endphp

        <li class="{{ $class }}">
            {{-- Icônes dynamiques --}}
            @switch($key)
                @case('ebauche') <i class="bi bi-pencil-square"></i> @break
                @case('revise') <i class="bi bi-search"></i> @break
                @case('en_approbation') <i class="bi bi-people"></i> @break
                @case('approuve') <i class="bi bi-check-circle"></i> @break
                @case('signe') <i class="bi bi-pencil"></i> @break
                @case('actif') <i class="bi bi-play-circle"></i> @break
                @case('annule') <i class="bi bi-x-circle"></i> @break
                @case('expire') <i class="bi bi-hourglass-split"></i> @break
                @case('renouvele') <i class="bi bi-arrow-repeat"></i> @break
            @endswitch
            <br>{{ $label }}
        </li>
    @endforeach
</ul>



    <form id="formContrat" action="{{ route('contrats.ebauche', $contrat->id) }}" method="POST">
        <input type="hidden" name="contenu_document" id="contenu_document">
        <input type="hidden" name="variables" id="hidden_variables">
        @csrf
        @method('PATCH')

        {{-- ONGLET NAVIGATION --}}
        <ul class="nav nav-tabs" id="contratTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#informations">Informations</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#clauses">Clauses</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#workflow">Workflow d'approbation</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#signature">Signature</a></li>

        </ul>

        <div class="tab-content border p-3">
            {{-- ONGLET INFORMATIONS --}}
            <div class="tab-pane fade show active" id="informations">
                
                {{-- Section Contrat --}}
                <fieldset class="border  p-3 mb-3">
                    <legend class="w-auto"><h4>Contrat</h4></legend>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type_contrat_id">Type de contrat</label>
                            <select name="type_contrat_id" id="type_contrat_id" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach($typesContrats as $type)
                                    <option value="{{ $type->id }}" {{ $contrat->type_contrat_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="titre">Titre du contrat</label>
                            <input type="text" name="titre" id="titre" class="form-control" 
                                   value="{{ old('titre', $contrat->titre) }}" required>
                        </div>
                    </div>
                </fieldset>

                {{-- Section Renouvellement --}}
                <fieldset class="border p-3 mb-3">
                    <legend class="w-auto"><h4>Renouvellement</h4></legend>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type_renouvelement">Type de renouvellement</label>
                            <select name="type_renouvelement" id="type_renouvelement" class="form-control">
                                <option value="aucun" {{ $contrat->type_renouvelement == 'aucun' ? 'selected' : '' }}>Aucun</option>
                                <option value="automatique" {{ $contrat->type_renouvelement == 'automatique' ? 'selected' : '' }}>Automatique</option>
                                <option value="unique" {{ $contrat->type_renouvelement == 'unique' ? 'selected' : '' }}>Contrat unique</option>
                                <option value="indeterminee" {{ $contrat->type_renouvelement == 'indeterminee' ? 'selected' : '' }}>Durée indéterminée</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_notification">Date de notification</label>
                            <input type="date" name="date_notification" id="date_notification" 
                                   value="{{ old('date_notification', $contrat->date_notification) }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="duree_auto">Durée auto-renouvellement (mois)</label>
                            <input type="number" name="duree_auto" id="duree_auto" class="form-control"
                                   value="{{ old('duree_auto', $contrat->duree_auto_renouvellement) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jours_annulation">Jours d’avance pour annulation</label>
                            <input type="number" name="jours_annulation" id="jours_annulation" class="form-control"
                                   value="{{ old('jours_annulation', $contrat->jours_preavis_resiliation) }}">
                        </div>
                    </div>
                </fieldset>

                {{-- Section Informations générales --}}
                <fieldset class="border p-3 mb-3">
                    <legend class="w-auto"><h4>Informations générales</h4></legend>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="annee_fiscale_id">Année fiscale</label>
                             <select name="annee_fiscale_id" id="annee_fiscale_id" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach($anneesFiscales as $an)
                                    <option value="{{ $type->id }}" {{ $contrat->annee_fiscale_id == $an->id ? 'selected' : '' }}>
                                        {{ $an->annee }}
                                    </option>
                                @endforeach
                            </select>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_debut">Date début</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control"
                                    value="{{ old('date_debut', $contrat->date_debut ? \Carbon\Carbon::parse($contrat->date_debut)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description', $contrat->description) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="notes_generales">Notes générales</label>
                            <textarea name="notes_generales" id="notes_generales" class="form-control">{{ old('notes_generales', $contrat->notes_generales) }}</textarea>
                        </div>
                    </div>
                </fieldset>

                {{-- Section Contractants --}}
                <fieldset class="border p-3 mb-3">
                    <legend class="w-auto"><h4>Parties contractantes</h4></legend>
                    <div class="d-flex mb-3">
                        <select id="selectContractant" class="form-control me-2">
                            @foreach($contractants as $p)
                                <option value="{{ $p->id }}">{{ $p->nom }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-success" id="addContractant">+ Ajouter</button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="contractantsTable">
                            @foreach($contrat->contractants as $c)
                                <tr>
                                    <td>
                                        <input type="hidden" name="contractants[]" value="{{ $c->id }}">
                                        {{ $c->nom }}
                                    </td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">❌</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </fieldset>
            </div>
            {{-- ONGLET CLAUSES --}}
<div class="tab-pane fade" id="clauses">
    <fieldset class="border p-3 mb-3 position-relative">
        <legend class="w-auto fw-bold">Clauses</legend>

        <button type="button" class="btn bg-gradient-info position-absolute"
                style="top: 20px; right: 15px;" id="btnGenererContrat">
            Générer le contrat
        </button>

        <div class="row text-center mb-3 mt-6">
            <div class="col">
                <label class="form-check">
                    <input type="checkbox" id="modèlesContrat"
                           onchange="toggleModeleContrat()"
                           {{ $contrat->modele_contrat_id ? 'checked' : '' }}>
                    Mes modèles de contrat
                </label>
            </div>
            <div class="col">
                <label>
                    <input type="checkbox" id="bibliothèqueClauses"
                           onchange="toggleBibliothequeClause()"
                           {{ optional($contrat->clauses)->count() ? 'checked' : '' }}>
                    Bibliothèque des clauses
                </label>
            </div>
        </div>

        {{-- Sélecteur de modèle --}}
        <select id="selectModeleContrat" class="form-control me-2"
                style="{{ $contrat->modele_contrat_id ? '' : 'display:none;' }}"
                onchange="afficherContenuModele(this.value)">
            <option value="">Sélectionner un modèle</option>
            @foreach($modelesContrat as $modele)
                <option value="{{ $modele->id }}"
                        {{ $contrat->modele_contrat_id == $modele->id ? 'selected' : '' }}>
                    {{ $modele->titre }}
                </option>
            @endforeach
        </select>

        {{-- Sélecteur de clause --}}
        <select id="selectClause" class="form-control me-2"
                style="{{ optional($contrat->clauses)->count() ? '' : 'display:none;' }}"
"
                onchange="afficherContenuClause(this.value)">
            <option value="">Sélectionner une clause</option>
            @foreach($bibliothequeClauses as $clause)
                <option value="{{ $clause->id }}"
                      {{ optional($contrat->clauses)->contains($clause->id) ? 'selected' : '' }}>

                    {{ $clause->type_clause }}
                </option>
            @endforeach
        </select>

        {{-- Iframe affichage contenu --}}
        <div class="mt-2">
            <iframe id="modele-contrat-frame"
                    style="width:100%; height:600px; border:1px solid #ccc;"></iframe>
        </div>
    </fieldset>
</div>

<script>
function toggleModeleContrat() {
    document.getElementById('selectModeleContrat').style.display =
        document.getElementById('modèlesContrat').checked ? 'block' : 'none';
    document.getElementById('zonePreview').innerHTML = '';
}
function toggleBibliothequeClause() {
    document.getElementById('selectClause').style.display =
        document.getElementById('bibliothèqueClauses').checked ? 'block' : 'none';
    document.getElementById('zonePreview').innerHTML = '';
}
function afficherContenuModele(id) {
    if (!id) return;
    fetch("{{ url('contrats/modele-content') }}/" + id)
        .then(response => response.json())
        .then(data => {
            if (data.type === 'html') {
                let iframe = document.getElementById('modele-contrat-frame');
                let doc = iframe.contentDocument || iframe.contentWindow.document;
                doc.open();
                doc.write(data.html);
                doc.close();
            } else {
                document.getElementById('modele-contrat-frame').contentDocument.body.innerHTML = data.message || 'Erreur';
            }
        });
}
function afficherContenuClause(id) {
    if (!id) return;
    fetch("{{ url('clausiers/contenu') }}/" + id)
        .then(response => response.json())
        .then(data => {
            if (data.type === 'html') {
                let iframe = document.getElementById('modele-contrat-frame');
                let doc = iframe.contentDocument || iframe.contentWindow.document;
                doc.open();
                doc.write(data.html);
                doc.close();
            } else {
                document.getElementById('modele-contrat-frame').contentDocument.body.innerHTML = data.message || 'Erreur';
            }
        });
}

function getVariablesFromIframe() {
    let iframe = document.getElementById('modele-contrat-frame');
    let doc = iframe.contentDocument || iframe.contentWindow.document;
    let variables = {};
    doc.querySelectorAll('.variable-field').forEach(input => {
        variables[input.name] = input.value;
    });
    return variables;
}

document.getElementById('btnGenererContrat').addEventListener('click', function() {
    let iframe = document.getElementById('modele-contrat-frame');
    let doc = iframe.contentDocument || iframe.contentWindow.document;

    // Remplacer chaque input par sa valeur saisie, dans le DOM
    doc.querySelectorAll('.variable-field').forEach(function(input) {
        let textNode = doc.createTextNode(input.value);
        input.parentNode.replaceChild(textNode, input);
    });

    // Maintenant on récupère le HTML final sans <input>
    let html = doc.body.innerHTML;

    // Remplir les champs cachés
    document.getElementById('contenu_document').value = html;
    document.getElementById('hidden_variables').value = JSON.stringify(getVariablesFromIframe());

    document.getElementById('formContrat').submit();
});
</script>
        

         </form>
            {{-- ONGLET WORKFLOW --}}
            <div class="tab-pane fade" id="workflow">
              <form action="{{ route('contrats.assignWorkflow', $contrat->id) }}" method="POST" id="workflowForm">
                 @csrf
              <fieldset class="border p-3 mb-3 position-relative">
                <legend class="w-auto fw-bold">Workflow d’approbation</legend>

                    <!-- Bouton en haut à droite du fieldset -->
                    <button type="submit" form="workflowForm" 
                           class="btn bg-gradient-info  position-absolute"  style="top: 20px; right: 15px; ">
                        Enregistrer
                    </button>
                        <div class="row align-items-center g-3 mt-6">
                            <!-- Sélecteur à droite -->
                            <div class="col-md-6">
                                <label for="workflow_id" class="form-label mb-1">Sélectionner un workflow</label>
                                <select name="workflow_id" id="workflow_id" class="form-control" required>
                                    <option value="">-- Choisir un workflow --</option>
                                    @foreach($workflows as $wf)
                                        <option value="{{ $wf->id }}" {{ $contrat->workflow_id == $wf->id ? 'selected' : '' }}>
                                            {{ $wf->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Checkbox à gauche -->
                            <div class="col-md-6 d-flex align-items-center ">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="notif_contractant" value="1" id="notif_contractant"
                                        {{ $contrat->notif_contractant ? 'checked' : '' }}>
                                    <label class="form-check-label ms-1" for="notif_contractant">
                                        Notifier les contractants
                                    </label>
                                </div>
                            </div>

                        
                        </div>
                    </form>

                    <hr>

                    <!-- Tableau des étapes -->
                    <table class="table table-bordered" id="workflowEtapesTable">
                        <thead style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                            <tr>
                                <th class="text-center">Titre étape</th>
                                <th class="text-center">Niveau</th>
                                <th class="text-center">Équipe</th>
                                <th class="text-center">Approbateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Chargé en AJAX après sélection --}}
                        </tbody>
                    </table>
                </fieldset>


            </div>
            {{-- ONGLET SIGNATURE --}}
        <div class="tab-pane fade" id="signature">
            <fieldset class="border p-3 mb-3 position-relative">
                  <legend class="w-auto fw-bold">Signature</legend>
                  <form action="{{ route('contrats.signatures.store', $contrat->id) }}" method="POST">
                    @csrf     
                                <button type="submit"  class="btn bg-gradient-info position-absolute "  style="top: 20px; right: 15px; "> Marquer signé</button>
                        <div class=" align-items-center mb-3 mt-6">
                              {{-- Liste des signatures déjà faites --}}
                    @if($contrat->signatures->count())
                        <ul class="list-group mb-3 mt-6">
                            @foreach($contrat->signatures as $sig)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $sig->representant->libelle ?? 'Représentant inconnu' }}
                                    <span class="badge bg-success">
                                        Signé le {{ $sig->signed_at ? \Carbon\Carbon::parse($sig->signed_at)->format('d/m/Y H:i') : '-' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                        </div>
              
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="signature_entity_id">Entité de signature</label>
                                <select name="signature_entity_id" id="signature_entity_id" class="form-control" required>
                                    <option value="">-- Choisir --</option>
                                    @foreach($representants as $rep)
                                        @php
                                            $alreadySigned = $contrat->signatures->where('representant_legal_id', $rep->id)->isNotEmpty();
                                        @endphp
                                        <option value="{{ $rep->id }}" {{ $alreadySigned ? 'disabled' : '' }}>
                                            {{ $rep->libelle }} {{ $alreadySigned ? '(déjà signé)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </fieldset>
        </div>

            </div>
   
</div>

{{-- Script pour gérer ajout des contractants --}}
<script>
    document.querySelectorAll('.removeRow').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('tr').remove();
        });
    });

    document.getElementById('addContractant').addEventListener('click', function () {
        let select = document.getElementById('selectContractant');
        let id = select.value;
        let text = select.options[select.selectedIndex].text;

        let tbody = document.getElementById('contractantsTable');
        let row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <input type="hidden" name="contractants[]" value="${id}">
                ${text}
            </td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">❌</button></td>
        `;
        tbody.appendChild(row);

        row.querySelector('.removeRow').addEventListener('click', function () {
            row.remove();
        });
    });
</script>

<script>
const routeEtapesTemplate = "{{ route('workflows.etapes.ajax', ':id') }}";

document.getElementById('workflow_id').addEventListener('change', function() {
    let workflowId = this.value;
    if (!workflowId) return;

    let url = routeEtapesTemplate.replace(':id', workflowId);

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('Erreur serveur : ' + res.status);
            return res.json();
        })
        .then(data => {
            let tbody = document.querySelector('#workflowEtapesTable tbody');
            tbody.innerHTML = '';
            data.forEach((etape, index) => {
                tbody.innerHTML += `
                    <tr>
                        <td class="text-center">${etape.libelle}</td>
                        <td class="text-center">${etape.niveau}</td>
                        <td class="text-center">${etape.user.user_type}</td>
                        <td class="text-center">${etape.user.name}</td>
                    </tr>
                `;
            });
        })
        .catch(err => {
            console.error('Erreur chargement étapes :', err);
        });
});
</script>

<!-- // pour l’affichage conditionnel du bouton "Marquer comme révisé" -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const actionButtons = document.getElementById('action-buttons');
    actionButtons.style.display = 'none'; // masqué par défaut

    const allowedTabs = ['#informations', '#clauses'];

    function toggleActionButtons(tabHref) {
        actionButtons.style.display = allowedTabs.includes(tabHref) ? 'flex' : 'none';
    }

    // Initialisation au chargement
    const activeTab = document.querySelector('#contratTabs .nav-link.active');
    if (activeTab) toggleActionButtons(activeTab.getAttribute('href'));

    // Changement d’onglet
    document.querySelectorAll('#contratTabs [data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            toggleActionButtons(e.target.getAttribute('href'));
        });
    });
});
</script>
<!-- // pour l’affichage initial du contenu dans l’iframe dpuis la base -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const iframe = document.getElementById('modele-contrat-frame');
    const modeleId = "{{ $contrat->modele_contrat_id ?? '' }}";
    const clausesCount = "{{ optional($contrat->clauses)->count() ?? 0 }}";
    const contenu = `{!! addslashes($contrat->contenu_document ?? '') !!}`;

    // Affiche le contenu enregistré
    if (contenu.trim() !== '') {
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        doc.open();
        doc.write(contenu);
        doc.close();
    }
    // Sinon, recharge depuis le modèle ou la première clause
    else if (modeleId) {
        afficherContenuModele(modeleId);
    } else if (clausesCount > 0) {
        const firstClause = document.getElementById('selectClause').value;
        afficherContenuClause(firstClause);
    }

    // Affichage correct des selects et checkboxes
    if (modeleId) {
        document.getElementById('selectModeleContrat').style.display = '';
        document.getElementById('selectClause').style.display = 'none';
        document.getElementById('modèlesContrat').checked = true;
        document.getElementById('bibliothèqueClauses').checked = false;
    } 
    else if (clausesCount > 0) {
        document.getElementById('selectClause').style.display = '';
        document.getElementById('selectModeleContrat').style.display = 'none';
        document.getElementById('bibliothèqueClauses').checked = true;
        document.getElementById('modèlesContrat').checked = false;
    }
});
</script>




@endsection
