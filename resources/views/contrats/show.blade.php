@extends('layouts.app1')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">

    {{-- Titre et statut --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            Contrat : {{ $contrat->titre ?? 'Sans titre' }}
        </h4>

        <span class="badge 
            @switch($contrat->statut)
                @case('ebauche') bg-secondary @break
                @case('revise') bg-info text-dark @break
                @case('en_approbation') bg-warning text-dark @break
                @case('approuve') bg-success @break
                @case('signe') bg-primary @break
                @case('actif') bg-success @break
                @case('annule') bg-danger @break
                @case('expire') bg-dark @break
                @case('renouvele') bg-success @break
                @default bg-light text-dark
            @endswitch
        ">
            {{ ucfirst($contrat->statut) }}
        </span>
    </div>

    {{-- Informations principales --}}
    <div class="row">
        <div class="col-md-6">
            <h6 class="text-uppercase text-muted">Informations du contrat</h6>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>ID :</strong> {{ $contrat->id }}</li>
                <li class="list-group-item"><strong>Type :</strong> {{ $contrat->typesContrat->libelle?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Date de création :</strong> {{ $contrat->created_at->format('d/m/Y') }}</li>
                <li class="list-group-item"><strong>Dernière mise à jour :</strong> {{ $contrat->updated_at->diffForHumans() }}</li>
            </ul>
        </div>

        <div class="col-md-6">
            <h6 class="text-uppercase text-muted">Parties concernées</h6>
            <ul class="list-group mb-3">
                @foreach ($contrat->contractants as $contractant)
                    <li class="list-group-item"><strong>Contractant :</strong>{{ $contractant->nom }} {{ $contractant->prenom }} ({{ $contractant->email }})</li>
                @endforeach
                    <li class="list-group-item"><strong>Responsable :</strong> {{  $contrat->responsable->nom ?? 'Non spécifié' }}</li>
                    
            </ul>
        </div>
    </div>

    {{-- Contenu du contrat --}}
    <div class="mt-4">
        <h6 class="text-uppercase text-muted">Contenu du contrat</h6>
        <div class="border p-3 rounded" style="min-height: 250px; color: #000;">
            {!! $contrat->contenu_document !!}
        </div>
    </div>

    {{-- Boutons d’action --}}
    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('contrats.index') }}" class="btn btn-secondary">⬅️ Retour</a>

        <div>
            <form action="{{ route('contrats.approuver', $contrat) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn bg-gradient-info ">Approuver</button>
        </form>
        </div>
    </div>

</div>
@endsection
