@extends('layouts.app1')
@section('content')
<div>
    <a href="{{ route('contractants.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Modification de Contractant</legend>
              <div class="row">
                    <div class="col-12">
                        <div class="card my-4">
                            <div class="card-body px-4 pb-4">
                                @if(session('success'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('contractants.update', $contractant->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        {{-- Colonne 1 --}}
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="categorie" class="form-label">Catégorie</label>
                                                <select name="categorie" id="categorie" class="form-control" required>
                                                    <option value="personne physique" {{ $contractant->categorie == 'personne physique' ? 'selected' : '' }}>Personne physique</option>
                                                    <option value="personne morale" {{ $contractant->categorie == 'personne morale' ? 'selected' : '' }}>Personne morale</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="nom" class="form-label">Nom</label>
                                                <input type="text" id="nom" name="nom" class="form-control" value="{{ $contractant->nom }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="prenom" class="form-label">Prénom</label>
                                                <input type="text" id="prenom" name="prenom" class="form-control" value="{{ $contractant->prenom }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="raison_sociale" class="form-label">Raison sociale</label>
                                                <input type="text" id="raison_sociale" name="raison_sociale" class="form-control" value="{{ $contractant->raison_sociale }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="ice" class="form-label">ICE</label>
                                                <input type="text" id="ice" name="ice" class="form-control" value="{{ $contractant->ice }}">
                                            </div>
                                        </div>

                                        {{-- Colonne 2 --}}
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="type_contractant" class="form-label">Type de contractant</label>
                                                <select name="type_contractant" id="type_contractant" class="form-control">
                                                    @foreach($types as $type)
                                                        <option value="{{ $type->id }}" {{ $contractant->type_contractant == $type->id ? 'selected' : '' }}>
                                                            {{ $type->libelle }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" id="email" name="email" class="form-control" value="{{ $contractant->email }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="ville" class="form-label">Ville</label>
                                                <input type="text" id="ville" name="ville" class="form-control" value="{{ $contractant->ville }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="adresse" class="form-label">Adresse</label>
                                                <input type="text" id="adresse" name="adresse" class="form-control" value="{{ $contractant->adresse }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="telephone" class="form-label">Téléphone</label>
                                                <input type="text" id="telephone" name="telephone" class="form-control" value="{{ $contractant->telephone }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                                        <a href="{{ route('contractants.index') }}" class="btn btn-secondary me-2">Annuler</a>
                                        
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
