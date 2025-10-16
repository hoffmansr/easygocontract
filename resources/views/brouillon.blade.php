@extends('layouts.app1')

@section('content')
<div class="container border py-4">
    <div class="container py-3">
        <h3 class="mb-4">Gérer un Contrat</h3>

    {{-- Barre de progression des statuts --}}
    <div class="status-bar d-flex justify-content-between align-items-center mb-4">
        @php
            $statuses = [
                'ebauche' => 'Brouillon',
                'revise' => 'Revisé',
                'en_approbation' => 'En approbation',
                'approuve' => 'Approuvé',
                'signe' => 'Signé',
                'actif' => 'Actif'
            ];
            $currentStatus = $contrat->statut ?? 'ebauche';
        @endphp

        @foreach($statuses as $key => $label)
            <div class="status-step text-center flex-fill position-relative">
                <div class="step-circle @if(array_search($key, array_keys($statuses)) <= array_search($currentStatus, array_keys($statuses))) completed @endif">
                    {{ array_search($key, array_keys($statuses)) + 1 }}
                </div>
                <div class="step-label mt-2">{{ $label }}</div>
                @if(!$loop->last)
                    <div class="step-bar @if(array_search($key, array_keys($statuses)) < array_search($currentStatus, array_keys($statuses))) completed @endif"></div>
                @endif
            </div>
        @endforeach
    </div>

</div>

{{-- Styles --}}
<style>
.status-bar {
    position: relative;
}
.status-step {
    position: relative;
    text-align: center;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    color: #555;
    z-index: 2;
    margin: 0 auto;
}
.step-circle.completed {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
}
.step-bar {
    height: 4px;
    background: #e0e0e0;
    position: absolute;
    top: 18px;
    left: 50%;
    width: 100%;
    z-index: 1;
}
.step-bar.completed {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
}
.step-label {
    font-size: 0.85rem;
}
.status-step:not(:last-child) .step-bar {
    width: 100%;
    left: 50%;
}
</style>


    

    <ul class="nav nav-tabs" id="contratTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">Informations</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="clauses-tab" data-bs-toggle="tab" data-bs-target="#clauses" type="button">Clauses</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="workflow-tab" data-bs-toggle="tab" data-bs-target="#workflow" type="button">Workflow d'approbations</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="signed-tab" data-bs-toggle="tab" data-bs-target="#signed" type="button">Signature</button>
        </li>
    </ul>

    <div class="tab-content card  border p-4 mt-2">
        {{-- Onglet Information --}}
        <div class="tab-pane fade show active" id="info" role="tabpanel">
            <div></div>
            <h5 class="mb-3">Informations du contrat</h5>
            <form id="form-info" action="{{ route('contrats.store') }}" method="POST">
                @csrf
             {{-- Section 1 : Type de contrat --}}
<div class="card mb-4 p-3">
    <h5 class="mb-3">Type de contrat</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="type_contrat_id">Type de contrat</label>
            <select name="type_contrat_id" id="type_contrat_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                @foreach($typesContrats as $type)
                    <option value="{{ $type->id }}" {{ old('type_contrat_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->libelle }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label for="titre">Titre du contrat</label>
            <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre') }}" required>
        </div>
    </div>
</div>

{{-- Section 2 : Renouvellement --}}
<div class="card mb-4 p-3">
    <h5 class="mb-3">Renouvellement</h5>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="type_renouvelement">Type de renouvellement</label>
            <select name="type_renouvelement" id="type_renouvelement" class="form-select">
                <option value="">-- Sélectionner --</option>
                <option value="automatique" {{ old('type_renouvelement')=='automatique' ? 'selected' : '' }}>Automatique</option>
                <option value="unique" {{ old('type_renouvelement')=='unique' ? 'selected' : '' }}>Contrat unique</option>
                <option value="indetermine" {{ old('type_renouvelement')=='indetermine' ? 'selected' : '' }}>Durée indéterminée</option>
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label for="date_notification">Date de notification</label>
            <input type="date" name="date_notification" id="date_notification" class="form-control" value="{{ old('date_notification') }}">
        </div>

        <div class="col-md-3 mb-3">
            <label for="duree_auto_renouvellement">Durée auto-renouvellement</label>
            <input type="text" name="duree_auto_renouvellement" id="duree_auto_renouvellement" class="form-control" value="{{ old('duree_auto_renouvellement') }}">
        </div>

        <div class="col-md-3 mb-3">
            <label for="jours_avis_annulation">Jours à l'avance pour annuler</label>
            <input type="number" name="jours_avis_annulation" id="jours_avis_annulation" class="form-control" value="{{ old('jours_avis_annulation') }}">
        </div>
    </div>
</div>

{{-- Section 3 : Informations --}}
<div class="card mb-4 p-3">
    <h5 class="mb-3">Informations</h5>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="annee_fiscale_id">Année fiscale</label>
            <select name="annee_fiscale_id" id="annee_fiscale_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                @foreach($anneesFiscales as $annee)
                    <option value="{{ $annee->id }}" {{ old('annee_fiscale_id') == $annee->id ? 'selected' : '' }}>
                        {{ $annee->libelle }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="date_debut">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label for="description">Description du contrat</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="col-md-6">
            <label for="notes_generales">Notes générales</label>
            <textarea name="notes_generales" id="notes_generales" class="form-control" rows="4">{{ old('notes_generales') }}</textarea>
        </div>
    </div>
</div>

{{-- Section 4 : Partie contractant --}}
<div class="card mb-4 p-3">
    <h5 class="mb-3">Partie contractant</h5>
    <div class="row mb-3">
        <div class="col-md-8 d-flex">
            <select id="select-contractant" class="form-select" style="height: 40px;">
                <option value="">-- Sélectionner un contractant --</option>
                @foreach($contractants as $c)
                    <option value="{{ $c->id }}"
                            data-nom="{{ $c->nom }}"
                            data-raison="{{ $c->raison_sociale }}"
                            data-telephone="{{ $c->telephone }}"
                            data-ice="{{ $c->ice }}">
                        {{ $c->nom ?? $c->raison_sociale }}
                    </option>
                @endforeach
            </select>
            <button type="button" id="btn-add-selected" class="btn btn-success ms-2">
                <i class="bi bi-plus"></i>
            </button>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddContractant">
                <i class="bi bi-person-plus me-1"></i> Ajouter un contractant
            </button>
        </div>
    </div>

    <table class="table table-bordered table-striped" id="table-contractants">
        <thead>
            <tr>
                <th>Nom / Raison sociale</th>
                <th>Contact principal</th>
                <th>Téléphone</th>
                <th>RC / CIN</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            {{-- Lignes ajoutées dynamiquement --}}
        </tbody>
    </table>
</div>



                <button type="submit" class="btn btn-primary">Enregistrer et passer aux Clauses</button>
            </form>
        </div>

        {{-- Onglet Clauses --}}
        <div class="tab-pane fade" id="clauses" role="tabpanel">
    <h5 class="mb-3">Clauses du contrat</h5>

    <div class="row g-4">
        <!-- Sélection via mes modèles de contrat -->
        <div class="col-md-6">
            <div class="form-check mb-2">
                <input class="form-check-input toggle-input" type="checkbox" id="chkModels" data-target="#selectModels">
                <label class="form-check-label fw-bold" for="chkModels">
                    Activer mes modèles de contrat
                </label>
            </div>
           <label for="modele_contrat_id" class="form-label">Mes modèles de contrat</label>
            <select name="modele_contrat_id" id="modele_contrat_id" class="form-select">
                <option value="">-- Sélectionner un modèle --</option>
                @foreach($modelesContrat as $modele)
                    <option value="{{ $modele->id }}">{{ $modele->titre }}</option>
                @endforeach
            </select>
            
            
        </div>

        <!-- Sélection via bibliothèque de clauses -->
        <div class="col-md-6">
            <div class="form-check mb-2">
                <input class="form-check-input toggle-input" type="checkbox" id="chkBibliotheque" data-target="#selectBibliotheque">
                <label class="form-check-label fw-bold" for="chkBibliotheque">
                    Activer la bibliothèque des clauses
                </label>
            </div>
            <label for="selectBibliotheque" class="form-label">Bibliothèque des clauses</label>
            <select id="selectBibliotheque" name="bibliotheque_clause_id" class="form-select" disabled>
                <option value="">-- Sélectionner une clause --</option>
                @foreach($bibliothequeClauses as $clause)
                    <option value="{{ $clause->id }}">{{ $clause->titre }}</option>
                @endforeach
            </select>
        </div>
    </div>
   <div id="document-container" class="mt-4">
    <!-- Ici on affichera le document -->
</div>



    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Ajouter les clauses sélectionnées
        </button>
    </div>
</div>


        {{-- Onglet Workflow --}}
       <div class="tab-pane fade" id="workflow" role="tabpanel">
    <h5 class="mb-3">Workflow d’approbation</h5>
@if(isset($contrat) && $contrat->id)
    <form action="{{ route('contrats.assignWorkflow', $contrat->id) }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="workflow_id">Mes workflows d’approbation</label>
                <select name="workflow_id" id="workflow_id" class="form-control" required>
                    <option value="">-- Sélectionnez un workflow --</option>
                    @foreach($workflows as $wf)
                        <option value="{{ $wf->id }}" {{ $contrat->workflow_id == $wf->id ? 'selected' : '' }}>
                            {{ $wf->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    💾 Enregistrer
                </button>
            </div>

            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="notif_contractant" id="notif_contractant" value="1"
                           {{ $contrat->notif_contractant ? 'checked' : '' }}>
                    <label class="form-check-label" for="notif_contractant">
                        Notifier les contractants
                    </label>
                </div>
            </div>
        </div>
    </form>
@else
    <div class="alert alert-info">
        ⚠️ Vous devez d’abord enregistrer le contrat (onglet **Informations**) avant de pouvoir lui affecter un workflow.
    </div>
@endif



<hr>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Titre étape</th>
            <th>Numéro étape</th>
            <th>Équipe approbatrice</th>
            <th>Approuveur</th>
        </tr>
    </thead>
    <tbody id="etapes-table">
        <tr><td colspan="4" class="text-center">Veuillez sélectionner un workflow</td></tr>
    </tbody>
</table>
</div>

        {{-- Onglet Signé --}}
        <div class="tab-pane fade" id="signed" role="tabpanel">
            <form id="form-signed" action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Entité signataire</label>
                    <select name="representant_legal_id" class="form-select" required>
                        @foreach($representants as $rep)
                            <option value="{{ $rep->id }}">{{ $rep->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Marquer signé</button>
            </form>
        </div>
    </div>
</div>
<!-- Modal Ajout contractant -->
<div class="modal fade" id="modalAddContractant" tabindex="-1" aria-labelledby="modalAddContractantLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalAddContractantLabel">Nouveau contractant</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <form action="{{ route('contractants.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <!-- Colonne 1 -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                <select name="categorie" id="categorie" class="form-select" required>
                  <option value="">-- Sélectionner --</option>
                  <option value="personne physique">Personne physique</option>
                  <option value="personne morale">Personne morale</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control">
              </div>

              <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" name="prenom" id="prenom" class="form-control">
              </div>

              <div class="mb-3">
                <label for="raison_sociale" class="form-label">Raison sociale</label>
                <input type="text" name="raison_sociale" id="raison_sociale" class="form-control">
              </div>

              <div class="mb-3">
                <label for="ice" class="form-label">ICE</label>
                <input type="text" name="ice" id="ice" class="form-control">
              </div>
            </div>

            <!-- Colonne 2 -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="type_contractant" class="form-label">Type</label>
                <select name="type_contractant" id="type_contractant" class="form-select">
                  <option value="">-- Sélectionner --</option>
                  @foreach($typesContrats as $type)
                      <option value="{{ $type->id }}">{{ $type->libelle }}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="ville" class="form-label">Ville</label>
                <input type="text" name="ville" id="ville" class="form-control">
              </div>

              <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" name="adresse" id="adresse" class="form-control">
              </div>

              <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" name="telephone" id="telephone" class="form-control">
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


{{-- Scripts pour clauses dynamiques --}}
<script>
$(document).ready(function() {
    $('.clause-checkbox').change(function() {
        let id = $(this).data('id');
        let label = $(this).data('label');
        if($(this).is(':checked')) {
            $('#clauses-selected').append(`
                <div id="clause-${id}" class="mb-2">
                    <strong>${label}</strong>
                    <input type="text" name="variables[${id}]" class="form-control mt-1" placeholder="Remplir variables si nécessaire">
                </div>
            `);
        } else {
            $('#clause-'+id).remove();
        }
    });

    $('#save-clauses').click(function() {
        // Ici tu peux envoyer un AJAX POST pour sauvegarder les clauses
        alert('Clauses enregistrées !');
    });
});
</script>

<script>
$(document).ready(function(){
    $('#btn-add-selected').click(function(){
        let sel = $('#select-contractant').find(':selected');
        let id = sel.val();
        if(!id) {
            alert('Veuillez sélectionner un contractant');
            return;
        }

        // Vérifier si déjà ajouté
        if($('#row-'+id).length) {
            alert('Ce contractant est déjà dans la liste');
            return;
        }

        // Récupération des infos
        let nom = sel.data('nom') || '';
        let raison = sel.data('raison') || '';
        let tel = sel.data('telephone') || '';
        let ice = sel.data('ice') || '';

        // Créer la ligne
        let row = `
        <tr id="row-${id}">
            <td>${raison ? raison : nom}</td>
            <td>${nom}</td>
            <td>${tel}</td>
            <td>${ice}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm btn-remove">X</button>
                <input type="hidden" name="contractants[]" value="${id}">
            </td>
        </tr>`;

        $('#table-contractants tbody').append(row);
    });

    // Supprimer une ligne
    $(document).on('click', '.btn-remove', function(){
        $(this).closest('tr').remove();
    });
});
</script>
 
<!-- pour charger les clauses du modèle de contrat -->
<script>
document.getElementById('modele_contrat_id').addEventListener('change', function() {
    let modeleId = this.value;
    let container = document.getElementById('document-container');
    container.innerHTML = '';

    if (!modeleId) return;

    let url = '{{ route("modeles_contrats.clauses", ":id") }}';
    url = url.replace(':id', modeleId);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            // data[0].fichier_modele doit contenir le chemin vers le fichier
            if (data[0].fichier_modele) {
                let fileUrl = '/storage/' + data[0].fichier_modele; // ou le chemin correct
                container.innerHTML = `<iframe src="${fileUrl}" width="100%" height="600px"></iframe>`;
            } else {
                container.innerHTML = '<p class="text-muted">Aucun document disponible pour ce modèle.</p>';
            }
        })
        .catch(err => {
            container.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement du document.</div>';
            console.error(err);
        });
});

</script>

<!-- pour charger les étapes du workflow -->
<script>
document.getElementById('workflow_id').addEventListener('change', function () {
    let workflowId = this.value;
    let tableBody = document.getElementById('etapes-table');
    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Chargement...</td></tr>';

    if (workflowId) {
        fetch(`/workflow/${workflowId}/etapes`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Aucune étape trouvée</td></tr>';
                    return;
                }

                let rows = '';
                data.forEach(etape => {
                    rows += `
                        <tr>
                            <td>${etape.libelle}</td>
                            <td>${etape.niveau}</td>
                            <td>${etape.user ? etape.user.user_type : ''}</td>
                            <td>${etape.user ? etape.user.name : ''}</td>
                        </tr>
                    `;
                });
                tableBody.innerHTML = rows;
            })
            .catch(err => {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-danger text-center">Erreur de chargement</td></tr>';
                console.error(err);
            });
    } else {
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Veuillez sélectionner un workflow</td></tr>';
    }
});
</script>


<div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Roles</legend>
          
        </fieldset>
    </div>
</div>



@endsection
