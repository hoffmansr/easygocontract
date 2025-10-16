@extends('layouts.app2')

@section('content')
<div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Détails de la société</legend>
          <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card my-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Raison sociale :</strong>
                                <p class="mb-0">{{ $societe->raison_sociale }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>ICE :</strong>
                                <p class="mb-0">{{ $societe->ice }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>Pays :</strong>
                                <p class="mb-0">{{ $societe->pays }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>Ville :</strong>
                                <p class="mb-0">{{ $societe->ville }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>Adresse :</strong>
                                <p class="mb-0">{{ $societe->adresse }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>Logo :</strong>
                                <p class="mb-0">
                                    @if($societe->logo)
                                        <img src="{{ asset('storage/'.$societe->logo) }}" alt="Logo" width="120">
                                    @else
                                        Non défini
                                    @endif
                                </p>
                            </div>

                            <hr>

                            <h5 class=" fw-bold mb-3">Utilisateur principal</h5>
                            <div class="mb-3">
                                <strong>Nom :</strong>
                                <p class="mb-0">{{ $user?->name ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Prénom :</strong>
                                <p class="mb-0">{{ $user?->prenom ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Email :</strong>
                                <p class="mb-0">{{ $user?->email ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Photo de profil :</strong>
                                <p class="mb-0">
                                    @if($user?->photo)
                                        <img src="{{ asset('storage/'.$user->photo) }}" alt="Photo" width="80">
                                    @else
                                        Non défini
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <strong>Statut de l'utilisateur :</strong>
                                <p class="mb-0">
                                    @if($user?->actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </p>
                            </div>

                            <div class="d-flex justify-content-end">
                                <form action="{{ route('societes.destroy', $societe->id) }}" method="POST" class="me-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cette société ?')">
                                        <i class="bi bi-trash me-1"></i>Supprimer
                                    </button>
                                </form>
                                <a href="{{ route('societes.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Retour
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
    
@endsection
