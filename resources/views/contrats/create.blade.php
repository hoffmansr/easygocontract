@extends('layouts.app1')

@section('content')
<div class="container bg-white">
    <h4 class="mb-4">Créer un contrat</h4>

    <form action="{{ route('contrats.store') }}" method="POST">
        @csrf

        {{-- ONGLET NAVIGATION --}}
        <ul class="nav nav-tabs" id="contratTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#informations">Informations</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#clauses">Clauses</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#workflow">Workflow</a></li>
        </ul>

        <div class="tab-content border p-3">
            {{-- ONGLET INFORMATIONS --}}
            <div class="tab-pane fade show active" id="informations">
                
                {{-- Section Contrat --}}
                <fieldset class="border  p-3 mb-3">
                    <legend class="w-auto">Contrat</legend>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type_contrat_id">Type de contrat</label>
                            <select name="type_contrat_id" id="type_contrat_id" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach($typesContrats as $type)
                                    <option value="{{ $type->id }}">{{ $type->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="titre">Titre du contrat</label>
                            <input type="text" name="titre" id="titre" class="form-control" required>
                        </div>
                    </div>
                </fieldset>

                {{-- Section Renouvellement --}}
                <fieldset class="border p-3 mb-3">
                    <legend class="w-auto">Renouvellement</legend>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type_renouvelement">Type de renouvellement</label>
                            <select name="type_renouvelement" id="type_renouvelement" class="form-control">
                                <option value="aucun">Aucun</option>
                                <option value="automatique">Automatique</option>
                                <option value="unique">Contrat unique</option>
                                <option value="indeterminee">Durée indéterminée</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_notification">Date de notification</label>
                            <input type="date" name="date_notification" id="date_notification" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="duree_auto">Durée auto-renouvellement (mois)</label>
                            <input type="number" name="duree_auto" id="duree_auto" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jours_annulation">Jours d’avance pour annulation</label>
                            <input type="number" name="jours_annulation" id="jours_annulation" class="form-control">
                        </div>
                    </div>
                </fieldset>

                {{-- Section Informations générales --}}
                <fieldset class="border p-3 mb-3">
                    <legend class="w-auto">Informations générales</legend>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="annee_fiscale_id">Année fiscale</label>
                            <select name="annee_fiscale_id" id="annee_fiscale_id" class="form-control">
                                @foreach($anneesFiscales as $af)
                                    <option value="{{ $af->id }}">{{ $af->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_debut">Date début</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="notes_generales">Notes générales</label>
                            <textarea name="notes_generales" id="notes_generales" class="form-control"></textarea>
                        </div>
                    </div>
                </fieldset>

                {{-- Section Contractants --}}
                <fieldset class="border p-3 mb-3">
                    <legend class="w-auto">Parties contractantes</legend>
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
                            {{-- JS ajoutera ici les lignes --}}
                        </tbody>
                    </table>
                </fieldset>
            </div>

            {{-- ONGLET CLAUSES --}}
           <div class="tab-pane fade show active" id="clauses" role="tabpanel">
    <!-- Ligne titre + bouton -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">CLAUSES DE CONTRAT</h5>
        <button class="btn btn-primary">+ Ajouter une clause</button>
    </div>

    <!-- Deux colonnes -->
    <div class="row mb-3">
        <!-- Bloc Mes modèles -->
        <div class="col-md-6">
            <div class="mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="mesModeles" checked>
                    <label class="form-check-label fw-bold" for="mesModeles">
                        Mes modèles de contrat
                    </label>
                </div>
            </div>
            <div class="d-flex">
                <select class="form-select me-2">
                    <option value="">-- Sélectionner un modèle --</option>
                    <option value="1">Modèle 1</option>
                    <option value="2">Modèle 2</option>
                    <option value="3">Modèle 3</option>
                </select>
                <button class="btn btn-outline-secondary">+</button>
            </div>
        </div>

        <!-- Bloc Bibliothèque -->
        <div class="col-md-6">
            <div class="mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="bibliothequeClauses">
                    <label class="form-check-label fw-bold" for="bibliothequeClauses">
                        Bibliothèque des clauses
                    </label>
                </div>
            </div>
            <div class="d-flex">
                <select class="form-select me-2">
                    <option value="">-- Sélectionner une clause --</option>
                    <option value="a">Clause A</option>
                    <option value="b">Clause B</option>
                    <option value="c">Clause C</option>
                </select>
                <button class="btn btn-outline-secondary">+</button>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Texte de clause</th>
                <th>Ordre de placement</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3" class="text-center text-muted">Aucune clause ajoutée</td>
            </tr>
        </tbody>
    </table>
</div>


                {{-- Bouton submit --}}
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        </div>
 </form>
        <div class="tab-pane fade" id="workflow">
    <fieldset class="border p-3 mb-3">
        <legend class="w-auto">Workflow d’approbation</legend>

        <form action="{{ route('contrats.assignWorkflow', $contrat->id ?? 0) }}" method="POST" id="workflowForm">
            @csrf
            <div class="row align-items-center mb-3">
                <div class="col-md-8">
                    <label for="workflow_id">Sélectionner un workflow</label>
                    <select name="workflow_id" id="workflow_id" class="form-control" required>
                        <option value="">-- Choisir un workflow --</option>
                        @foreach($workflows as $wf)
                            <option value="{{ $wf->id }}">{{ $wf->libelle }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="notif_contractant" value="1" id="notif_contractant">
                        <label class="form-check-label" for="notif_contractant">
                            Notifier les contractants
                        </label>
                    </div>
                </div>

                <div class="col-md-1 mt-4">
                    <button type="submit" class="btn btn-success">💾 Enregistrer</button>
                </div>
            </div>
        </form>

        <hr>

        <!-- Tableau des étapes -->
        <table class="table table-bordered" id="workflowEtapesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titre étape</th>
                    <th>Niveau</th>
                    <th>Équipe</th>
                    <th>Approbateur</th>
                </tr>
            </thead>
            <tbody>
                {{-- Chargé en AJAX après sélection --}}
            </tbody>
        </table>
    </fieldset>
</div>


        </div>
   
</div>

{{-- Script pour gérer ajout des contractants --}}
<script>
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
    // placeholder :id qu’on remplacera en JS
    const routeEtapes = "{{ route('workflows.etapes.ajax', ['workflow' => ':id']) }}";
</script>


<script>
const routeEtapesTemplate = "{{ route('workflows.etapes.ajax', ':id') }}";

document.getElementById('workflow_id').addEventListener('change', function() {
    let workflowId = this.value;
    if (!workflowId) return;

    // Remplace :id par la valeur du workflow
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
                        <td>${index + 1}</td>
                        <td>${etape.libelle}</td>
                        <td>${etape.niveau}</td>
                        <td>${etape.user.user_type}</td>
                        <td>${etape.user.name}</td>
                    </tr>
                `;
            });
        })
        .catch(err => {
            console.error('Erreur chargement étapes :', err);
        });
});
</script>


@endsection
