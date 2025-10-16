@extends('layouts.app1')

@section('content')
<div class="container">
    <h4 class="mb-3">Consultation du contrat : {{ $contrat->titre }}</h4>

    <div class="card mb-3">
        <div class="card-body">
           <div class="contrat-contenu">
    {!! $contenu !!}
</div>
        </div>
    </div>

    <a href="{{ route('contrats.approbation') }}" class="btn btn-secondary">← Retour</a>
</div>
@endsection
