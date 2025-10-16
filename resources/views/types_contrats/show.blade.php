@extends('layouts.app1')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header bg-gradient-primary text-white p-3">
                    Détails du type de contrat
                </div>
                <div class="card-body">
                    <p><strong>Libellé :</strong> {{ $types_contrat->libelle }}</p>
                    <p><strong>Type contractant :</strong> {{ $types_contrat->typeContractant->libelle ?? 'Non défini' }}</p>
                    <p><strong>Actif :</strong> {{ $types_contrat->actif ? 'Oui' : 'Non' }}</p>
                    <a href="{{ route('types_contrats.index') }}" class="btn btn-secondary mt-3">Retour</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
