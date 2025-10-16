@extends('layouts.app1')
@section('content')
<h4>Remplir les variables du modèle</h4>

<form action="{{ route('contrats.genererModele', [$contrat->id, $modele->id]) }}" method="POST">
    @csrf
    @foreach($variables as $var)
        <div class="mb-3">
            <label class="form-label">{{ $var }}</label>
            <input type="text" name="{{ $var }}" class="form-control" required>
        </div>
    @endforeach
    <button type="submit" class="btn btn-primary">Générer le contrat</button>
</form>
@endsection