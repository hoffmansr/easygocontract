@extends('layouts.app1')
@section('content')
<div>
   <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Titre / Section à gauche -->
        <h5 class="mb-0">Liste des workflows d'approbation</h5>

        <!-- Bouton à droite -->
          <a href="{{ route('workflows.create') }}" class="btn bg-gradient-info"><i class="bi bi-plus-circle"></i> Nouveau</a>
        </a>
    </div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Workflows</legend>
           <div class="card my-4">
              <div class="card-body">
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                <div class="">
                  <table class="table align-items-center">
                    <thead  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                      <tr>
                        <th class="text-center">Titre du workflow</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Approbateur(s)</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($workflows as $w)
                        <tr>
                          <td class="text-center">{{ $w->libelle }}</td>
                          <td class="text-center">
                            @if($w->statut === 'actif') <span class="badge bg-success">Actif</span>
                            @else <span class="badge bg-secondary">Inactif</span> @endif
                          </td>
                          <td class="text-center">
                            @if($w->etapes->isEmpty())
                              <small class="text-muted">—</small>
                            @else
                              @foreach($w->etapes as $etape)
                                <span class="badge bg-info mb-1">
                                  {{ $etape->user ? ($etape->user->name . ' ' . ($etape->user->prenom ?? '')) : 'Non défini' }}
                                  <small class="d-block text-muted">Niv.{{ $etape->niveau }}</small>
                                </span>
                              @endforeach
                            @endif
                          </td>
                          <td>
                              <div class="dropdown">
                                  <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownType{{  $w->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                      <i class="bi bi-three-dots-vertical"></i>
                                  </button>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownType{{  $w->id }}">
                                      <li>
                                          <a class="dropdown-item" href="{{ route('workflows.show', $w->id) }}">
                                              <i class="bi bi-eye me-2"></i>Voir
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item" href="{{ route('workflows.edit', $w->id) }}">
                                              <i class="bi bi-pencil me-2"></i>Modifier
                                          </a>
                                      </li>
                                      <li><hr class="dropdown-divider"></li>
                                      <li>
                                          <form action="{{ route('workflows.destroy', $w->id) }}" method="POST" class="d-inline">
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
                        <tr><td colspan="4" class="text-center">Aucun workflow</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
        </fieldset>
    </div>
</div>
 
@endsection
