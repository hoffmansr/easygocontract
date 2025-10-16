   {{-- ONGLET SIGNATURE --}}
            <div class="tab-pane fade" id="signature">
               <fieldset class="border p-3 mb-3">
                    <legend class="w-auto">Signature</legend>

                   <form action="{{ route('contrats.marquerSignature', $contrat->id) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="signature_entity_id">Entité de signature</label>
                                <select name="signature_entity_id" id="signature_entity_id" class="form-control" required>
                                    <option value="">-- Choisir --</option>
                                    @foreach($representants as $rep)
                                        <option value="{{ $rep->id }}" {{ $contrat->signature_entity_id == $rep->id ? 'selected' : '' }}>
                                            {{ $rep->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-success">✅ Marquer signé</button>
                            </div>
                        </div>
                    </form>

                </fieldset>

            </div>
            {{-- FIN ONGLET SIGNATURE --}}
            {{-- ONGLET WORKFLOW --}}

               <div class="tab-pane fade" id="workflow">
                <fieldset class="border p-3 mb-3">
                    <legend class="w-auto">Workflow d’approbation</legend>

                    <form action="{{ route('contrats.assignWorkflow', $contrat->id) }}" method="POST" id="workflowForm">
                        @csrf
                        <div class="row align-items-center mb-3">
                            <div class="col-md-8">
                                <label for="workflow_id">Sélectionner un workflow</label>
                                <select name="workflow_id" id="workflow_id" class="form-control" required>
                                    <option value="">-- Choisir un workflow --</option>
                                    @foreach($workflows as $wf)
                                        <option value="{{ $wf->id }}" {{ $contrat->workflow_id == $wf->id ? 'selected' : '' }}>
                                            {{ $wf->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="notif_contractant" value="1" id="notif_contractant"
                                           {{ $contrat->notif_contractant ? 'checked' : '' }}>
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
            {{-- FIN ONGLET WORKFLOW --}}

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
                            <select id="selectModeles" class="form-select me-2">
                                <option value="">-- Sélectionner un modèle --</option>
                                @foreach($modelesContrat as $modele)
                                    <option value="{{ $modele->id }}">{{ $modele->titre }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-secondary" onclick="ajouterModele()">+</button>
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
                            <select id="selectClauses" class="form-select me-2">
                                <option value="">-- Sélectionner une clause --</option>
                                @foreach($bibliothequeClauses as $clause)
                                    <option value="{{ $clause->id }}">{{ Str::limit($clause->texte, 50) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-secondary" onclick="ajouterClause()">+</button>
                        </div>
                    </div>
                </div>

                <!-- Tableau -->
                <table class="table table-bordered table-hover" id="tableClauses">
                    <thead>
                        <tr>
                            <th>Texte de clause</th>
                            <th>Ordre de placement</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="rowEmpty">
                            <td colspan="4" class="text-center text-muted">Aucune clause ajoutée</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- FIN ONGLET CLAUSES --}}

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
                    <legend class="w-auto">Renouvellement</legend>
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
                    <legend class="w-auto">Informations générales</legend>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="annee_fiscale_id">Année fiscale</label>
                            <select name="annee_fiscale_id" id="annee_fiscale_id" class="form-control">
                                @foreach($anneesFiscales as $af)
                                    <option value="{{ $af->id }}" {{ $contrat->annee_fiscale_id == $af->id ? 'selected' : '' }}>
                                        {{ $af->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_debut">Date début</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control"
                                   value="{{ old('date_debut', $contrat->date_debut) }}">
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