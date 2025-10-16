@extends('layouts.app1')

@section('content')
<div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Modifier le Workflow</legend>
          <div class="row">
                <div class="col-12 col-md-8 offset-md-2">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('workflows.update', $workflow->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Libellé -->
                                <div class="mb-3">
                                    <label for="libelle" class="form-label fw-bold">Titre du Workflow</label>
                                    <input type="text" name="libelle" id="libelle" class="form-control @error('libelle') is-invalid @enderror" value="{{ old('libelle', $workflow->libelle) }}" required>
                                    @error('libelle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Statut -->
                                <div class="mb-3">
                                    <label for="statut" class="form-label fw-bold">Statut</label>
                                    <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                        <option value="actif" {{ old('statut', $workflow->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactif" {{ old('statut', $workflow->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('workflows.index') }}" class="btn btn-secondary me-2">
                                        <i class="bi bi-x-circle"></i> Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Mettre à jour
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

    
@endsection
