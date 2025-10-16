@extends('layouts.app1')
@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Titre / Section à gauche -->
        <h5 class="mb-0">Liste des Modeles de contrats</h5>

        <!-- Bouton à droite -->
         <a href="{{ route('modeles_contrats.create') }}" class="btn bg-gradient-info">
            <i class="bi bi-plus-circle me-1"></i>Nouveau modèle
        </a>
    </div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Modeles de Contrat</legend>
             <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-0 pb-2">
                            @if(session('success'))
                                <div class="alert alert-success mx-3" role="alert">{{ session('success') }}</div>
                            @endif

                            <div class="p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="align-middle text-center">Titre du modèle</th>
                                            <th class="align-middle text-center">Description</th>
                                            <th class="align-middle text-center">Statut</th>
                                            <th class="align-middle text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($modeles as $modele)
                                            <tr>
                                                <td class="align-middle text-center">{{ $modele->titre }}</td>
                                                <td class="align-middle text-center">{{ Str::limit($modele->description, 50) }}</td>
                                                <td class="align-middle text-center">
                                                    @if($modele->actif)
                                                        <span class="badge bg-success">Actif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactif</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                <div class="dropdown">
                                                        <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownmodeleContrat{{ $modele->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownmodeleContrat{{ $modele->id }}">
                                                            <li><a class="dropdown-item" href="{{ route('modeles_contrats.show', $modele->id) }}"><i class="bi bi-eye me-2"></i>Voir</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('modeles_contrats.edit', $modele->id) }}"><i class="bi bi-pencil me-2"></i>Modifier</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('modeles_contrats.destroy', $modele->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer ce modele ?')">
                                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center">Aucun modèle trouvé</td></tr>
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
