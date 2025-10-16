<fieldset class="border p-3 mb-3">
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
            <label for="date_debut">Date début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control"
                   value="{{ old('date_debut', $contrat->date_debut) }}">
        </div>
    </div>

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
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $contrat->description) }}</textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="notes_generales">Notes générales</label>
            <textarea name="notes_generales" id="notes_generales" class="form-control">{{ old('notes_generales', $contrat->notes_generales) }}</textarea>
        </div>
    </div>
</fieldset>
