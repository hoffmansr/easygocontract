@extends('layouts.app1')
@section('content')
<div>
    <a href="{{ route('workflows.show', $workflow->id) }}" class="btn btn-sm btn-outline-secondary">Retour</a>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>  <h6>Modifier l'étape : {{ $etape->libelle }} (Workflow : {{ $workflow->libelle }})</h6></legend>
           <div class="card">
              <div class="card-body">
                <form action="{{ route('workflows.etapes.update', ['workflow' => $workflow->id, 'etape' => $etape->id]) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="row">
                    <div class="col-md-2 mb-3">
                      <label class="form-label">Niveau</label>
                      <input type="number" name="niveau" class="form-control" value="{{ old('niveau', $etape->niveau) }}" min="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="form-label">Libellé</label>
                      <input type="text" name="libelle" class="form-control" value="{{ old('libelle', $etape->libelle) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label class="form-label">Équipe approbatrice</label>
                      <input type="text" name="equipe" class="form-control" value="{{ old('equipe', $etape->equipe ?? '') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                      <label class="form-label">Approbateur (utilisateur)</label>
                      <select name="user_id" class="form-select">
                        <option value="">-- Aucun --</option>
                        @foreach($users as $u)
                          <option value="{{ $u->id }}" {{ old('user_id', $etape->user_id) == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} {{ $u->prenom ?? '' }} ({{ $u->user_type }})
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-primary">Mettre à jour</button>
                  </div>
                </form>
              </div>
            </div>
        </fieldset>
    </div>
</div>
 
@endsection
