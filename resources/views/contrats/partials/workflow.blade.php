<fieldset class="border p-3 mb-3">
    <legend class="w-auto">Workflow d’approbation</legend>

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

        <div class="col-md-3 mt-4">
            <div class="form-check">
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

    <hr>

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
