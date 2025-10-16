@extends('layouts.app1')
@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Liste des Contractants</h5>
        <a href="{{ route('contractants.create') }}" class="btn bg-gradient-info">
            <i class="bi bi-person-plus me-1"></i>Nouveau contractant
        </a>
    </div>
      <div class="border p-4 my-4">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center mb-3">
            <!-- Champ recherche -->
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm rounded"
                    placeholder="Rechercher un contrat..."
                    value="{{ request('search') }}">
            </div>

            <!-- Select statut -->
            <div class="col-md-3">
                <select name="statut" class="form-select form-select-sm rounded">
                    <option value="">Tous les catégories</option>
                    <option value="personne physique" {{ request('categorie') == 'personne_physique' ? 'selected' : '' }}>Personne physique</option>
                    <option value="personne moral" {{ request('categorie') == 'personne_morale' ? 'selected' : '' }}>Personne morale</option>
                   
                </select>
            </div>

            <!-- Select type de contrat -->
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm rounded">
                     <option value="">Tous les types</option>
                    @foreach($typeContractant as $type)
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
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend> Contractants</legend>
          <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                       
                        <div class="card-body px-0 pb-2">
                            @if(session('success'))
                                <div class="alert alert-success mx-3" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            <div class=" p-0">
                                <table class="table align-items-center mb-0">
                                    <thead  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom / Raison sociale</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Catégorie</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ville</th>
                                            <th class="text-secondary opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($contractants as $contractant)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            @if($contractant->categorie === 'personne morale')
                                                                {{ $contractant->raison_sociale }}
                                                            @else
                                                                {{ $contractant->nom }} {{ $contractant->prenom }}
                                                            @endif
                                                        </h6>
                                                        <p class="text-xs text-secondary mb-0">ICE : {{ $contractant->ice }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ ucfirst($contractant->categorie) }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $contractant->typeContractant->libelle ?? 'Non défini' }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs mb-0">
                                                    <i class="bi bi-envelope me-1"></i>{{ $contractant->email }}
                                                </p>
                                                <p class="text-xs mb-0">
                                                    <i class="bi bi-telephone me-1"></i>{{ $contractant->telephone }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs mb-0">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $contractant->ville }}
                                                </p>
                                                <p class="text-xs text-secondary mb-0">{{ $contractant->adresse }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <div class="dropdown">
                                                    <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownContractant{{ $contractant->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownContractant{{ $contractant->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('contractants.show', $contractant->id) }}">
                                                                <i class="bi bi-eye me-2"></i>Voir
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('contractants.edit', $contractant->id) }}">
                                                                <i class="bi bi-pencil me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('contractants.destroy', $contractant->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer définitivement ce contractant ?')">
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
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-secondary">
                                                    <i class="bi bi-people text-lg"></i>
                                                    <p class="mb-0 mt-2">Aucun contractant trouvé</p>
                                                    <a href="{{ route('contractants.create') }}" class="btn btn-sm btn-primary mt-2">
                                                        Créer le premier contractant
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
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

@push('scripts')
<script>
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endpush
