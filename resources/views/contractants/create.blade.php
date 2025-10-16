@extends('layouts.app1')
@section('content')
<div>
     <a href="{{ route('contractants.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Création de Contractant</legend>
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('contractants.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Colonne 1 -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                            <select name="categorie" id="categorie" class="form-select" required>
                                                <option value="">-- Sélectionner --</option>
                                                <option value="personne physique">Personne physique</option>
                                                <option value="personne morale">Personne morale</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="nom" class="form-label">Nom</label>
                                            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="prenom" class="form-label">Prénom</label>
                                            <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="raison_sociale" class="form-label">Raison sociale</label>
                                            <input type="text" name="raison_sociale" id="raison_sociale" class="form-control" value="{{ old('raison_sociale') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="ice" class="form-label">ICE</label>
                                            <input type="text" name="ice" id="ice" class="form-control" value="{{ old('ice') }}">
                                        </div>
                                    </div>

                                    <!-- Colonne 2 -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type_contractant" class="form-label">Type</label>
                                            <select name="type_contractant" id="type_contractant" class="form-select">
                                                <option value="">-- Sélectionner --</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->libelle }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="ville" class="form-label">Ville</label>
                                            <input type="text" name="ville" id="ville" class="form-control" value="{{ old('ville') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="adresse" class="form-label">Adresse</label>
                                            <input type="text" name="adresse" id="adresse" class="form-control" value="{{ old('adresse') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="telephone" class="form-label">Téléphone</label>
                                            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save me-1"></i>Enregistrer
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
