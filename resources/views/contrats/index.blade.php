@extends('layouts.app1')

@section('content')
<div class="container bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Titre / Section à gauche -->
        <h5 class="mb-0">Liste des contrats</h5>

        <!-- Bouton à droite -->
        <a href="" class="btn bg-gradient-info btn-sm">
            <i class="bi bi-plus-lg"></i> Ajouter un contrat
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="border p-4 my-4">
        <form method="GET" action="{{ route('contrats.index') }}" class="row g-2 align-items-center mb-3">
            <!-- Champ recherche -->
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm rounded"
                    placeholder="Rechercher un contrat..."
                    value="{{ request('search') }}">
            </div>

            <!-- Select statut -->
            <div class="col-md-3">
                <select name="statut" class="form-select form-select-sm rounded">
                    <option value="">Tous les statuts</option>
                    <option value="ebauche" {{ request('statut') == 'ebauche' ? 'selected' : '' }}>Ébauche</option>
                    <option value="revise" {{ request('statut') == 'revise' ? 'selected' : '' }}>Révisé</option>
                    <option value="en_approbation" {{ request('statut') == 'en_approbation' ? 'selected' : '' }}>En approbation</option>
                    <option value="approuve" {{ request('statut') == 'approuve' ? 'selected' : '' }}>Approuvé</option>
                    <option value="signe" {{ request('statut') == 'signe' ? 'selected' : '' }}>Signé</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    <option value="expire" {{ request('statut') == 'expire' ? 'selected' : '' }}>Expiré</option>
                    <option value="renouvele" {{ request('statut') == 'renouvele' ? 'selected' : '' }}>Renouvelé</option>
                </select>
            </div>

            <!-- Select type de contrat -->
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm rounded">
                    <option value="">Tous les types</option>
                    @foreach($typesContrat as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bouton rechercher -->
            <div class="col-md-2 text-end mt-4">
                <button type="submit" class="btn btn-outline-info btn-sm w-100">
                    <i class="bi bi-search"></i> Filtrer
                </button>
            </div>
        </form>


    </div>

  

<div class="border p-4 my-4">
    <fieldset class="border p-3">

    <legend class="w-auto">Contrats</legend>

    <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                
                    <div class="card-body px-0 pb-2">
                        
                        <div class=" p-0">
                            <table class="table ">
                                <thead  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                                       <tr>
                                            <th scope="col">Titre de contrat</th>
                                            <th class="align-middle text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type de contrat</th>
                                            <th class="align-middle text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                            <th class="align-middle text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Intervenants</th>
                                            <th class="align-middle text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de debut</th>
                                            <th class="align-middle text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de fin</th>
                                            <th class="align-middle text-center text-secondary opacity-7">Actions</th>
                                       </tr>
                                </thead>
                                <tbody>
                                      @forelse($contrats as $contrat)
                                    <tr>
                                       
                                        <td scope="row" >{{ $contrat->titre }}</td>
                                        <td class="align-middle text-center" >{{ $contrat->typesContrat->libelle ?? '' }}</td>
                                       <td class="align-middle text-center text-sm">
                                            @switch($contrat->statut)
                                                @case('ebauche')
                                                    <span class="badge badge-sm bg-gradient-secondary">
                                                        <i class="bi bi-pencil-square me-1"></i>Ébauche
                                                    </span>
                                                    @break
                                                @case('revise')
                                                    <span class="badge badge-sm bg-gradient-warning">
                                                        <i class="bi bi-search me-1"></i>Révisé
                                                    </span>
                                                    @break
                                                @case('en_approbation')
                                                    <span class="badge badge-sm bg-gradient-info">
                                                        <i class="bi bi-people me-1"></i>En approbation
                                                    </span>
                                                    @break
                                                @case('approuve')
                                                    <span class="badge badge-sm bg-gradient-primary">
                                                        <i class="bi bi-check-circle me-1"></i>Approuvé
                                                    </span>
                                                    @break
                                                @case('signe')
                                                    <span class="badge badge-sm bg-gradient-dark">
                                                        <i class="bi bi-pencil me-1"></i>Signé
                                                    </span>
                                                    @break
                                                @case('actif')
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <i class="bi bi-play-circle me-1"></i>Actif
                                                    </span>
                                                    @break
                                                @case('annule')
                                                    <span class="badge badge-sm bg-gradient-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Annulé
                                                    </span>
                                                    @break
                                                @case('expire')
                                                    <span class="badge badge-sm bg-gradient-secondary">
                                                        <i class="bi bi-hourglass-split me-1"></i>Expiré
                                                    </span>
                                                    @break
                                                @case('renouvele')
                                                    <span class="badge badge-sm bg-gradient-info">
                                                        <i class="bi bi-arrow-repeat me-1"></i>Renouvelé
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge badge-sm bg-gradient-secondary">
                                                        Inconnu
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($contrat->workflow)
                                                @foreach($contrat->workflow->etapes as $etape)
                                                    @php $user = $etape->user; @endphp
                                                    @if($user)
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle border border-primary" 
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" 
                                                        title="{{ $user->name }}">
                                                            <img src="{{ $user->photo ?? asset('images/default-user.png') }}" alt="{{ $user->name }}">
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </td>

                                        <td class="align-middle text-center" >{{ $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : '' }}</td>
                                        <td class="align-middle text-center">{{ $contrat->date_fin ? $contrat->date_fin->format('d/m/Y') : '' }}</td>
                                        <td class="align-middle text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownUser{{ $contrat->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownUser{{ $contrat->id }}">
                                                    <li>
                                                      
                                                        <a class="dropdown-item" href="{{ route('contrats.edit', $contrat->id) }}">
                                                            <i class="bi bi-pencil me-2"></i>Voir
                                                        </a>
                                                      
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    
                                                    <li>
                                                        <form action="" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                           
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer définitivement cet utilisateur ?')">
                                                                <i class="bi bi-trash me-2"></i>Supprimer
                                                            </button>
                                                           
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-secondary text-center">
                                                <i class="bi bi-people text-lg"></i>
                                                <p class="mb-0 mt-2">Aucun contrat trouvé</p>
                                               
                                                    Créer le premier contrat
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $contrats->links('pagination::bootstrap-5') }}
                        </div>

                        
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

    </fieldset>

</div>
</div>
@endsection
