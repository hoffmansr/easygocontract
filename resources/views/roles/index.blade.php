@extends('layouts.app1')

@section('content')
<div>
     <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Liste des Roles</h5>
        <a href="{{ route('roles.create') }}" class="btn  bg-gradient-info">
            <i class="bi bi-plus-circle me-1"></i>Nouveau rôle
        </a>
    </div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Roles</legend>
           <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-0 pb-2"  style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            @if(session('success'))
                                <div class="alert alert-success mx-3" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class=" p-0">
                                <table class="table align-items-center mb-0">
                                    <thead  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                                        <tr>
                                            <th class="text-uppercase text-secondary opacity-7 text-center">Nom du rôle</th>
                                            <th class="text-uppercase text-secondary opacity-7 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($roles as $role)
                                            <tr>
                                                <td class="text-center">
                                                    <span class="badge badge-sm bg-gradient-info me-1 mb-1">
                                                        {{ $role->name }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownRole{{ $role->id }}" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownRole{{ $role->id }}">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">
                                                                    <i class="bi bi-pencil me-2"></i>Modifier
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce rôle ?')">
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
                                                <td colspan="2" class="text-center py-4">
                                                    <div class="text-secondary">
                                                        <i class="bi bi-shield-lock text-lg"></i>
                                                        <p class="mb-0 mt-2">Aucun rôle trouvé</p>
                                                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary mt-2">
                                                            Créer un rôle
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($roles->hasPages())
                                <div class="px-3 py-3">
                                    {{ $roles->links() }}
                                </div>
                            @endif
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
        document.querySelectorAll('.alert-success').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endpush
