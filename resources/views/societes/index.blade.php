@extends('layouts.app2')

@section('content')
<div>
     <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Titre / Section à gauche -->
        <h5 class="mb-0">Liste des Sociétés</h5>

        <!-- Bouton à droite -->
         <a href="{{ route('societes.create') }}" class="btn bg-gradient-info">
        <i class="bi bi-plus-circle me-1"></i>Nouvelle société
    </a>
    </div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Liste des societés</legend>
             <div class="row">
                    <div class="col-12">
                        <div class="card my-4">
                            <div class="card-body px-0 pb-2">
                                <div class="px-3 py-2">

                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success mx-3" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class=" p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                                            <tr>
                                                <th class="text-center text-uppercase text-secondary opacity-7">Raison sociale</th>
                                                <th class="text-center text-uppercase text-secondary opacity-7">ICE</th>
                                                <th class="text-center text-uppercase text-secondary opacity-7">Ville</th>
                                                <th class="text-center text-uppercase text-secondary opacity-7">Admin</th>
                                                <th class="text-center text-uppercase text-secondary opacity-7">Statut</th>
                                                <th class="text-secondary text-uppercase opacity-7 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($societes as $societe)
                                            <tr>
                                                <td class="text-center text-sm">{{ $societe->raison_sociale }}</td>
                                                <td class="text-center text-sm">{{ $societe->ice }}</td>
                                                <td class="text-center text-sm">{{ $societe->ville }}</td>
                                                <td class="text-center text-sm">
                                                    {{ $societe->users->first()->email ?? '-' }}
                                                </td>
                                                <td class="text-center text-sm">
                                                    @if($societe->statut === 'actif')
                                                        <span class="badge badge-sm bg-gradient-success">Actif</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-secondary">Suspendu</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownSociete{{ $societe->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownSociete{{ $societe->id }}">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('societes.show', $societe->id) }}">
                                                                    <i class="bi bi-eye me-2"></i>Voir
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('societes.edit', $societe->id) }}">
                                                                    <i class="bi bi-pencil me-2"></i>Modifier
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('societes.destroy', $societe->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer cette société ?')">
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
                                                        <i class="bi bi-building text-lg"></i>
                                                        <p class="mb-0 mt-2">Aucune société enregistrée</p>
                                                        <a href="{{ route('societes.create') }}" class="btn btn-sm btn-primary mt-2">Créer une société</a>
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
