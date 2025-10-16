@extends('layouts.app1')
@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Informations du Clausier</h4>
        </div>
        <div class="card-body">
            <!-- Langue -->
            <div class="mb-3">
                <strong>Langue :</strong>
                <span>{{ $clausier->langue == 'fr' ? 'Français' : ($clausier->langue == 'en' ? 'Anglais' : 'Arabe') }}</span>
            </div>

            <!-- Type de clause -->
            <div class="mb-3">
                <strong>Type de clause :</strong>
                <span>{{ $clausier->type_clause }}</span>
            </div>

            <!-- Désignation -->
            <div class="mb-3">
                <strong>Désignation :</strong>
                <span>{{ $clausier->designation }}</span>
            </div>

            <!-- Contenu -->
            <div class="mb-3">
                <strong>Contenu :</strong>
                <div class="border p-3" style="background-color: #f8f9fa;">
                    {!! $clausier->contenu !!}
                </div>
            </div>

            <a href="{{ route('clausiers.index') }}" class="btn btn-secondary">Retour à la liste</a>
            <a href="{{ route('clausiers.edit', $clausier->id) }}" class="btn btn-primary">Modifier</a>
        </div>
    </div>
</div>
@endsection
