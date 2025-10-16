@extends('layouts.app1')
@section('content')
<div>
    <div class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>
                <h4 class="mb-4">Modifier un Clausier</h4>
            </legend>

            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-0 pb-2">
                            @if(session('success'))
                                <div class="alert alert-success mx-3" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="container">
                                <form action="{{ route('clausiers.update', $clausier->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Langue -->
                                        <div class="col-md-4 mb-3">
                                            <label for="langue" class="form-label">Langue</label>
                                            <select name="langue" id="langue" class="form-select" required>
                                                <option value="">-- Choisir --</option>
                                                <option value="fr" {{ $clausier->langue == 'fr' ? 'selected' : '' }}>Français</option>
                                                <option value="en" {{ $clausier->langue == 'en' ? 'selected' : '' }}>Anglais</option>
                                                <option value="ar" {{ $clausier->langue == 'ar' ? 'selected' : '' }}>Arabe</option>
                                            </select>
                                        </div>

                                        <!-- Type de clause -->
                                        <div class="col-md-4 mb-3">
                                            <label for="type_clause" class="form-label">Type de clause</label>
                                            <input type="text" name="type_clause" id="type_clause"
                                                class="form-control" value="{{ old('type_clause', $clausier->type_clause) }}" required>
                                        </div>

                                        <!-- Désignation -->
                                        <div class="col-md-4 mb-3">
                                            <label for="designation" class="form-label">Désignation</label>
                                            <input type="text" name="designation" id="designation"
                                                class="form-control" value="{{ old('designation', $clausier->designation) }}" required>
                                        </div>
                                    </div>

                                    <!-- Contenu -->
                                    <div class="mb-3">
                                        <label for="contenu" class="form-label">Contenu</label>
                                        <textarea name="contenu" id="contenu" class="form-control" rows="8">{{ old('contenu', $clausier->contenu) }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <a href="{{ route('clausiers.index') }}" class="btn btn-secondary">Annuler</a>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </fieldset>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#contenu'))
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
