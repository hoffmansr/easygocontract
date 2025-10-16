@extends('layouts.app1')
@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Titre / Section à gauche -->
        <h5 class="mb-0">Liste des clausiers</h5>

        <!-- Bouton à droite -->
    
       <a href="{{ route('clausiers.create') }}" class="btn bg-gradient-info">
            <i class="bi bi-plus-circle me-1"></i>Nouveau clausier
        </a>
    </div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Clausiers</legend>
              <div class="row">
                    <div class="col-12">
                        <div class="card my-4">
                           

                            <div class="card-body px-0 pb-2">
                                @if(session('success'))
                                    <div class="alert alert-success mx-3" role="alert">{{ session('success') }}</div>
                                @endif

                                <div class=" p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                                            <tr>
                                                <th class="text-center">Langue</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Désignation</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($clausiers as $clausier)
                                                <tr>
                                                    <td class="text-center">{{ $clausier->langue }}</td>
                                                    <td class="text-center">{{ $clausier->type_clause }}</td>
                                                    <td class="text-center">{{ $clausier->designation }}</td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownClausier{{ $clausier->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownClausier{{ $clausier->id }}">
                                                                <li><a class="dropdown-item" href="{{ route('clausiers.show', $clausier->id) }}"><i class="bi bi-eye me-2"></i>Voir</a></li>
                                                                <li><a class="dropdown-item" href="{{ route('clausiers.edit', $clausier->id) }}"><i class="bi bi-pencil me-2"></i>Modifier</a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <form action="{{ route('clausiers.destroy', $clausier->id) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer ce clausier ?')">
                                                                            <i class="bi bi-trash me-2"></i>Supprimer
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="text-center">Aucun clausier trouvé</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
          
        </fieldset>
    </div>
</div>
  
@endsection
