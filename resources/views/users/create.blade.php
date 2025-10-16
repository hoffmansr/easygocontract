@extends('layouts.app1')

@section('content')
<div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Création d'utilisateur</legend>
          
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                    
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                                @csrf

                                {{-- Ligne 1 : Nom & Prénom --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Prénom</label>
                                        <input type="text" name="prenom" class="form-control"
                                            value="{{ old('prenom') }}">
                                    </div>
                                </div>

                                {{-- Ligne 2 : Email & Photo --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Photo de profil</label>
                                        <input type="file" name="photo" class="form-control">
                                    </div>
                                </div>

                                {{-- Ligne 3 : Mot de passe & Confirmation --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mot de passe</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirmer mot de passe</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>

                                {{-- Ligne 4 : Rôle & Équipe --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">-- Choisir un Rôle --</label>
                                        <select name="role" class="form-select" required>
                                            <option value=""></option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">-- Choisir une Équipe --</label>
                                        <select name="user_type" class="form-select">
                                            <option value=""></option>
                                            @foreach($userTypes as $type)
                                                <option value="{{ $type }}" {{ old('user_type') == $type ? 'selected' : '' }}>
                                                    {{ ucfirst($type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Ligne 5 : Actif --}}
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1" checked>
                                            <label class="form-check-label" for="actif">Utilisateur actif</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Boutons --}}
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-person-plus"></i> Créer l’utilisateur
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Annuler
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

@endsection
