@extends('layouts.app1')

@section('content')
<div class="container">
    <h4 class="mb-4">Mes contrats en attente d'approbation</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Contrat</th>
                <th>Étape</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($contrats as $contrat)
            @foreach($contrat->workflowEtapes as $etape)
                @if($etape->approver_id == auth()->id() && $etape->statut == 'en_attente')
                <tr>
                    <td>{{ $contrat->titre }}</td>
                    <td>{{ $etape->etape->nom ?? 'Étape' }}</td>
                    <td>
                        <span class="badge bg-warning text-dark">En attente</span>
                    </td>
                    <td>
                        <a href="{{ route('contrats.voir', $contrat->id) }}" class="btn btn-info btn-sm">Voir</a>
                        <form action="{{ route('contrats.approuver', $contrat) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approuver</button>
                        </form>
                       
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                        </form>
                    </td>
                </tr>
                @endif
            @endforeach
        @empty
            <tr>
                <td colspan="4" class="text-center">Aucun contrat en attente</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- <div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Mes approbations en attente</h4>
            <span class="badge bg-light text-primary fs-6">{{ $contrats->count() }}</span>
        </div>
        <div class="card-body p-0">
            @if($contrats->count())
            <div class="table-responsive">
                <table class="table align-middle table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Étape</th>
                            <th>Date début</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($contrats as $contrat)
                        @php
                            $etape = $contrat->workflowEtapes->where('approver_id', auth()->id())->where('statut', 'en_attente')->first();
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('contrats.voir', $contrat->id) }}" class="text-primary fw-bold">
                                    {{ $contrat->titre }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $contrat->typesContrat?->libelle ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">
                                    {{ $etape?->etape?->libelle ?? '-' }}
                                </span>
                            </td>
                            <td>
                                {{ \Illuminate\Support\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge 
                                    @if($contrat->statut === 'en_approbation') bg-warning text-dark
                                    @elseif($contrat->statut === 'approuvé') bg-success
                                    @else bg-secondary @endif">
                                    {{ ucfirst($contrat->statut) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('contrats.voir', $contrat->id) }}" class="btn btn-outline-success btn-sm">
                                    Voir & Approuver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-emoji-smile fs-2 mb-3"></i>
                    <p class="mb-0">Aucune approbation en attente.</p>
                </div>
            @endif
        </div>
    </div>
</div> -->
@endsection
