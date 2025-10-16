<fieldset class="border p-3 mb-3">
    <legend class="w-auto">Clauses</legend>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="mesModeles" checked>
                    <label class="form-check-label fw-bold" for="mesModeles">Mes modèles de contrat</label>
                </div>
            </div>
            <div class="d-flex">
                <select id="selectModeles" class="form-select me-2">
                    <option value="">-- Sélectionner un modèle --</option>
                    @foreach($modelesContrat as $modele)
                        <option value="{{ $modele->id }}">{{ $modele->titre }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-secondary" onclick="ajouterModele()">+</button>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="bibliothequeClauses">
                    <label class="form-check-label fw-bold" for="bibliothequeClauses">Bibliothèque des clauses</label>
                </div>
            </div>
            <div class="d-flex">
                <select id="selectClauses" class="form-select me-2">
                    <option value="">-- Sélectionner une clause --</option>
                    @foreach($bibliothequeClauses as $clause)
                        <option value="{{ $clause->id }}">{{ Str::limit($clause->texte, 50) }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-secondary" onclick="ajouterClause()">+</button>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-hover" id="tableClauses">
        <thead>
            <tr>
                <th>Texte de clause</th>
                <th>Ordre</th>
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
</fieldset>
