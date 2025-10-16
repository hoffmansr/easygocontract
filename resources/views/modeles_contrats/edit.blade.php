@extends('layouts.app1')
@section('content')
<div>
     <a href="{{ route('modeles_contrats.index') }}" class="btn btn-sm btn-outline-secondary ">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Modifier le Modèle de Contrat</legend>
           <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-4 pb-4">
                            <form action="{{ route('modeles_contrats.update', $modeles_contrat->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="langue" class="form-label fw-bold">Langue</label>
                                        <select name="langue" id="langue" class="form-select" required>
                                            <option value="">-- Choisir --</option>
                                            <option value="fr" {{ $modeles_contrat->langue === 'fr' ? 'selected' : '' }}>Français</option>
                                            <option value="en" {{ $modeles_contrat->langue === 'en' ? 'selected' : '' }}>Anglais</option>
                                            <option value="ar" {{ $modeles_contrat->langue === 'ar' ? 'selected' : '' }}>Arabe</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="titre" class="form-label fw-bold">Titre du modèle</label>
                                        <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre', $modeles_contrat->titre) }}" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="description" class="form-label fw-bold">Description</label>
                                        <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $modeles_contrat->description) }}" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="fichier_modele" class="form-label fw-bold">Fichier modèle (laisser vide pour ne pas changer)</label>
                                    <input type="file" name="fichier_modele" id="fichier_modele" class="form-control" accept=".pdf,.doc,.docx">
                                </div>

                             <div id="filePreview" class="card shadow-sm border-0 mt-3">
                                <div class="card-body">
                                    <h6>Aperçu actuel</h6>
                                    @php
                                        $ext = pathinfo($modeles_contrat->fichier_modele, PATHINFO_EXTENSION);
                                        $url = asset('storage/'.$modeles_contrat->fichier_modele);
                                    @endphp

                                    @if($ext === 'pdf')
                                        <iframe src="{{ $url }}" width="100%" height="500px" style="border:none;"></iframe>
                                    @elseif(in_array($ext, ['doc', 'docx']) && $modeles_contrat->html_contenu)
                                        <div style="background:white; padding:20px; color:black;">
                                            {!! $modeles_contrat->html_contenu !!}
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> Fichier non affichable.<br>
                                            <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                                                <i class="bi bi-box-arrow-up-right"></i> Ouvrir le fichier
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>


                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Mettre à jour
                                    </button>
                                    <a href="{{ route('modeles_contrats.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Annuler
                                    </a>
                                </div>
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
document.getElementById('fichier_modele')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewContent = document.querySelector('#filePreview .card-body');
    if(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        const url = URL.createObjectURL(file);

        let html = '';
        if(ext === 'pdf') {
            html = `<iframe src="${url}" width="100%" height="500px" style="border:none;"></iframe>`;
        } else if(ext === 'doc' || ext === 'docx') {
            html = `<div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Les fichiers Word ne peuvent pas être prévisualisés.<br>
                        <a href="${url}" target="_blank" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-box-arrow-up-right"></i> Ouvrir le fichier
                        </a>
                    </div>`;
        } else {
            html = `<p class="text-danger">Type de fichier non supporté.</p>`;
        }
        previewContent.innerHTML = html;
    }
});
</script>
@endpush
