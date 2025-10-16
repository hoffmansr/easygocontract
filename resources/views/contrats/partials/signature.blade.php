<fieldset class="border p-3 mb-3">
    <legend class="w-auto">Signature</legend>

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
</fieldset>
