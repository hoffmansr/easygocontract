@extends('layouts.app1')
@section('content')
<div>
    <a href="{{ route('types_contrats.index') }}" class="btn btn-sm btn-outline-dark">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Modifier un type de contra</legend>
          <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-4 py-3">
                            @if(session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('types_contrats.update', $types_contrat->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="libelle" class="form-label">Libellé</label>
                                        <input type="text" name="libelle" id="libelle" class="form-control" value="{{ old('libelle', $types_contrat->libelle) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="type_contractant" class="form-label">Type de contractant</label>
                                        <select name="type_contractant" id="type_contractant" class="form-select" required>
                                            <option value="">-- Choisir --</option>
                                            @foreach($typeContractants as $tc)
                                                <option value="{{ $tc->id }}" {{ $types_contrat->type_contractant == $tc->id ? 'selected' : '' }}>
                                                    {{ $tc->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" name="actif" id="actif" class="form-check-input" value="1" {{ $types_contrat->actif ? 'checked' : '' }}>
                                    <label for="actif" class="form-check-label">Actif</label>
                                </div>

                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
    

@endsection


@push('scripts')
<script>
    // Auto-hide success message after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if(alert){
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
</script>
@endpush
