@extends('layouts.app1')

@section('content')
<div>
     <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Titre / Section à gauche -->
        <h5 class="mb-0">Liste des types de contrats</h5>

        <!-- Bouton à droite -->
         <a href="{{ route('types_contrats.create') }}" class="btn bg-gradient-info">
            <i class="bi bi-plus-circle me-1"></i>Nouveau
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
                    <option value="">Statut</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actife</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                   
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
            <legend>Types de contrats</legend>
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
                            <thead style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Libellé</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type de contractant</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actif</th>
                                    <th class="text-center text-secondary opacity-7 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($types_contrats as $type)
                                <tr>
                                    <td class="text-center ">{{ $type->libelle }}</td>
                                    <td class="text-center ">{{ $type->typeContractant->libelle ?? 'Non défini' }}</td>
                                    <td class="text-center ">
                                        @if($type->actif)
                                            <span class="badge badge-sm bg-gradient-success">Actif</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownType{{ $type->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownType{{ $type->id }}">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('types_contrats.show', $type->id) }}">
                                                        <i class="bi bi-eye me-2"></i>Voir
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('types_contrats.edit', $type->id) }}">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('types_contrats.destroy', $type->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer ce type ?')">
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
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-secondary">
                                            <i class="bi bi-card-text text-lg"></i>
                                            <p class="mb-0 mt-2">Aucun type de contrat trouvé</p>
                                            <a href="{{ route('types_contrats.create') }}" class="btn btn-sm btn-primary mt-2">Créer un type</a>
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
            alerts.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endpush
