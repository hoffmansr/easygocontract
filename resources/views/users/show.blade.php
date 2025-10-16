@extends('layouts.app1')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card my-4">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class=" text-white text-capitalize ps-3 mb-0">Détails de l’utilisateur</h6>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-white">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                </div>
                <div class="card-body">
                    {{-- Ligne 1 : Nom et Prénom --}}
                    <div class="mb-3">
                        <strong>Nom :</strong>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Prénom :</strong>
                        <p class="mb-0">{{ $user->prenom ?? '-' }}</p>
                    </div>

                    {{-- Ligne 2 : Email & Photo --}}
                    <div class="mb-3">
                        <strong>Email :</strong>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Photo de profil :</strong><br>
                        @if($user->photo)
                            <img src="{{ asset('storage/'.$user->photo) }}" alt="Photo de profil" width="100" class="mt-2 rounded">
                        @else
                            <span class="text-muted">Aucune photo</span>
                        @endif
                    </div>

                    {{-- Ligne 3 : Rôle & Équipe --}}
                    <div class="mb-3">
                        <strong>Rôle :</strong>
                        <p class="mb-0">{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Équipe :</strong>
                        <p class="mb-0">{{ ucfirst($user->user_type ?? '-') }}</p>
                    </div>

                    {{-- Ligne 4 : Statut --}}
                    <div class="mb-3">
                        <strong>Statut :</strong>
                        @if($user->actif)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </div>

                    {{-- Boutons --}}
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="me-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
                                <i class="bi bi-trash me-1"></i>Supprimer
                            </button>
                        </form>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
