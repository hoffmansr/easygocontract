@extends('layouts.app1')

@section('content')
<div>
    <a href="{{ route('workflows.index') }}" class="btn btn-sm btn-outline-secondary">Retour</a>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend><h6 class="mb-0">Détails du workflow</h6></legend>
            <div class="card my-4">
            <div class="card-body">
              <h4>{{ $workflow->libelle }}</h4>
              <p><strong>Statut :</strong> {{ $workflow->statut }}</p>

              <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6>Étapes</h6>
                  <a href="{{ route('workflows.etapes.create', $workflow->id) }}" class="btn btn-sm btn-primary">Ajouter une étape</a>
                </div>

                <div class="">
                  <table class="table table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th class="text-center">Titre d'approbation</th>
                        <th class="text-center">Étape</th>
                        <th class="text-center">Équipe approbatrice</th>
                        <th class="text-center">Approbateur</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($workflow->etapes as $etape)
                        <tr>
                          <td class="text-center"> {{ $etape->libelle }}</td>
                          <td class="text-center">{{ $etape->niveau }}</td>
                          <td class="text-center">{{ $etape->user ? ucfirst($etape->user->user_type) : '-' }}</td>
                          <td class="text-center">{{ $etape->user ? $etape->user->name . ' ' . ($etape->user->prenom ?? '') : 'Non défini' }}</td>
                          <td class="text-center">
                            
                                <div class="dropdown">
                                    <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownType{{  $etape->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownType{{  $etape->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('workflows.etapes.destroy',['workflow' => $workflow->id, 'etape' => $etape->id]) }}">
                                                <i class="bi bi-eye me-2"></i>Voir
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('workflows.etapes.edit',['workflow' => $workflow->id, 'etape' => $etape->id]) }}">
                                                <i class="bi bi-pencil me-2"></i>Modifier
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('workflows.etapes.destroy',['workflow' => $workflow->id, 'etape' => $etape->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer ce workflow ?')">
                                                    <i class="bi bi-trash me-2"></i>Supprimer
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                          
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="5" class="text-center">Aucune étape</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </fieldset>
    </div>
</div>

@endsection
