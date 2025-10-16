@extends('layouts.app1')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card my-4">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="text-white mb-0">Détails du Contractant</h6>
                    <a href="{{ route('contractants.edit', $contractant->id) }}" class="btn btn-sm btn-outline-white">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nom / Raison sociale :</strong>
                        <p class="mb-0">
                            @if($contractant->categorie === 'personne morale')
                                {{ $contractant->raison_sociale }}
                            @else
                                {{ $contractant->nom }} {{ $contractant->prenom }}
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Catégorie :</strong>
                        <p class="mb-0">{{ ucfirst($contractant->categorie) }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Type :</strong>
                        <p class="mb-0">{{ $contractant->typeContractant->libelle ?? 'Non défini' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Email :</strong>
                        <p class="mb-0">{{ $contractant->email }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Téléphone :</strong>
                        <p class="mb-0">{{ $contractant->telephone }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>ICE :</strong>
                        <p class="mb-0">{{ $contractant->ice }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Ville :</strong>
                        <p class="mb-0">{{ $contractant->ville }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Adresse :</strong>
                        <p class="mb-0">{{ $contractant->adresse }}</p>
                    </div>

                    <div class="d-flex justify-content-end">
                        <form action="{{ route('contractants.destroy', $contractant->id) }}" method="POST" class="me-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce contractant ?')">
                                <i class="bi bi-trash me-1"></i>Supprimer
                            </button>
                        </form>
                        <a href="{{ route('contractants.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
