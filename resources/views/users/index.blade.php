@extends('layouts.app1')
@section('content')
<div class="">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Titre / Section à gauche -->
        <h5 class="mb-0">Liste des utilisateurs</h5>

        <!-- Bouton à droite -->
        <a href="{{ route('users.create') }}"  class="btn bg-gradient-info">
            <i class="bi bi-person-plus me-1"></i>Nouvel utilisateur
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                   
                </select>
            </div>

            <!-- Select type de contrat -->
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm rounded">
                    <option value="">Tous les Equipes</option>
                    <option value="collaborateur"  {{ request('equipe') == 'collaborateur' ? 'selected' : '' }}></option>
                    <option value="client"  {{ request('equipe') == 'client' ? 'selected' : '' }}></option>
                    <option value="admin"  {{ request('equipe') == 'admin' ? 'selected' : '' }}></option>
                  
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

</div>
<div class="border p-4 my-4">
    <fieldset class="border p-3">
        <legend class="w-auto">Utilisateurs</legend>
        <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
               
                <div class="card-body px-0 pb-2">
                    <div class=" p-0">
                        <table class="table align-items-center mb-0">
                            <thead  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Utilisateur</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Équipe</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rôle</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if($user->photo)
                                                    <img src="{{ asset('storage/' . $user->photo) }}" 
                                                         class="avatar avatar-sm me-3 border-radius-lg" 
                                                         alt="{{ $user->name }}">
                                                @else
                                                    <div class="avatar avatar-sm me-3 border-radius-lg bg-gradient-secondary d-flex align-items-center justify-content-center">
                                                        <span class="text-white text-sm font-weight-bold">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->prenom ?? '', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $user->name }} {{ $user->prenom }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ ucfirst($user->user_type) }}
                                        </p>
                                        <p class="text-xs text-secondary mb-0">
                                            @switch($user->user_type)
                                                @case('admin')
                                                    Administration
                                                    @break
                                                @case('client')
                                                    Client externe
                                                    @break
                                                @case('collaborateur')
                                                    Équipe interne
                                                    @break
                                                @default
                                                    Non défini
                                            @endswitch
                                        </p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($user->actif)
                                            <span class="badge badge-sm bg-gradient-success">
                                                <i class="bi bi-check-circle me-1"></i>Actif
                                            </span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">
                                                <i class="bi bi-x-circle me-1"></i>Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @if(isset($user->userRoles) && $user->userRoles->isNotEmpty())
                                            @foreach($user->userRoles as $role)
                                                <span class="badge badge-sm bg-gradient-info me-1 mb-1">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        @else
                                               <span class="text-secondary text-xs font-weight-bold">
                                                    Aucun rôle
                                                    <small class="d-block text-xs">
                                                        (ID: {{ $user->id ?? '' }}, Société: {{ $user->societe_id ?? 'N/A' }})
                                                    </small>
                                                </span>
                                        @endif  
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownUser{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownUser{{ $user->id }}">
                                                <li>
                                                    
                                                    <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                        <i class="bi bi-eye me-2"></i>Voir
                                                    </a>
                                                  
                                                </li>
                                                <li>
                                                  
                                                    <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a>
                                                   
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                @if($user->actif)
                                                  @can('Activer/Désactiver des utilisateurs')
                                                    <li>
                                                        
                                                        <form action="{{ route('users.deactivate', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item text-warning" onclick="return confirm('Désactiver cet utilisateur ?')">
                                                                <i class="bi bi-pause-circle me-2"></i>Désactiver
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <form action="{{ route('users.activate', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="bi bi-play-circle me-2"></i>Activer
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endcan
                                                @endif
                                                <li>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        @can('Supprimer des utilisateurs')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer définitivement cet utilisateur ?')">
                                                            <i class="bi bi-trash me-2"></i>Supprimer
                                                        </button>
                                                        @endcan
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-secondary">
                                            <i class="bi bi-people text-lg"></i>
                                            <p class="mb-0 mt-2">Aucun utilisateur trouvé</p>
                                            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary mt-2">
                                                Créer le premier utilisateur
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
</div>
    </fieldset>
</div>

@endsection

@push('scripts')
<script>
    // Auto-hide success messages after 5 seconds
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