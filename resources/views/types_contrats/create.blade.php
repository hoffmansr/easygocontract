@extends('layouts.app1')
@section('content')
<div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Créer un type de contrat</legend>
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        
                        <div class="card-body">
                            <form method="POST" action="{{ isset($typeContrat) ? route('types_contrats.store', $typeContrat->id) : route('types_contrats.store') }}">
                                @csrf
                                @if(isset($typeContrat))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Libellé</label>
                                        <input type="text" name="libelle" class="form-control" value="{{ $typeContrat->libelle ?? old('libelle') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Type contractant</label>
                                        <select name="type_contractant" class="form-control">
                                            <option value="">-- Sélectionner --</option>
                                            @foreach($typeContractants as $tc)
                                                <option value="{{ $tc->id }}" @if(isset($typeContrat) && $typeContrat->type_contractant == $tc->id) selected @endif>
                                                    {{ $tc->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                            <input type="checkbox" name="actif" id="actif" class="form-check-input" value="1" 
                                                {{ old('actif') ? 'checked' : '' }}>
                                            <label for="actif" class="form-check-label">Actif</label>
                                        </div>

                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ isset($typeContrat) ? 'Mettre à jour' : 'Créer' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                
                </fieldset>
            </div>
        </div>

    
@endsection
