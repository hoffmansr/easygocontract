@extends('layouts.app2')

@section('content')
<div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Créer une société & son utilisateur principal</legend>
          <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST" action="{{ route('societes.store') }}" enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Oups !</strong> Des erreurs ont été détectées :
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- PARTIE SOCIÉTÉ --}}
                                <h5 class=" fw-bold mb-3">Informations sur la société</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Raison sociale</label>
                                        <input type="text" name="raison_sociale" class="form-control" value="{{ old('raison_sociale') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ICE</label>
                                        <input type="text" name="ice" class="form-control" value="{{ old('ice') }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Pays</label>
                                        <input type="text" name="pays" class="form-control" value="{{ old('pays') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Ville</label>
                                        <input type="text" name="ville" class="form-control" value="{{ old('ville') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Adresse</label>
                                        <input type="text" name="adresse" class="form-control" value="{{ old('adresse') }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Logo</label>
                                        <input type="file" name="logo" class="form-control" >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Statut</label>
                                        <select name="statut" class="form-select" required>
                                            <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                            <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- PARTIE UTILISATEUR --}}
                                <h5 class=" fw-bold mb-3">Utilisateur principal de la société</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Prénom</label>
                                        <input type="text" name="user_prenom" class="form-control" value="{{ old('user_prenom') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="user_email" class="form-control" value="{{ old('user_email') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Photo de profil</label>
                                        <input type="file" name="user_photo" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mot de passe</label>
                                        <input type="password" name="user_password" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirmer mot de passe</label>
                                        <input type="password" name="user_password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="user_actif" id="user_actif" value="1" checked>
                                            <label class="form-check-label" for="user_actif">Utilisateur actif</label>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6 mb-3">
                                        <label class="form-label">Rôle utilisateur</label>
                                        <select name="user_role" class="form-select" required>
                                            <option value="abonne" selected>abonne</option>
                                            {{-- Ajoute d'autres rôles si besoin --}}
                                        </select>
                                    </div> -->
                                </div>

                                {{-- Bouton --}}
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-building-add"></i> Créer la société et l'utilisateur
                                    </button>
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