@extends('layouts.app1')
@section('content')
<div>
    <a href="{{ route('modeles_contrats.index') }}" class="btn btn-sm btn-outline-secondary ">
                                <i class="bi bi-arrow-left me-1"></i>Retour
                            </a>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Modèle de Contrat</legend>
           <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-4 pb-4">
                            <h4 class="mb-4">Créer un modèle de contrat</h4>

                            <form action="{{ route('modeles_contrats.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Langue -->
                                    <div class="col-md-4 mb-3">
                                        <label for="langue" class="form-label fw-bold">Langue</label>
                                        <select name="langue" id="langue" class="form-select" required>
                                            <option value="">-- Choisir --</option>
                                            <option value="fr">Français</option>
                                            <option value="en">Anglais</option>
                                            <option value="ar">Arabe</option>
                                        </select>
                                    </div>

                                    <!-- Titre -->
                                    <div class="col-md-4 mb-3">
                                        <label for="titre" class="form-label fw-bold">Titre du modèle</label>
                                        <input type="text" name="titre" id="titre"
                                            class="form-control" value="{{ old('titre') }}" required>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-4 mb-3">
                                        <label for="description" class="form-label fw-bold">Description</label>
                                        <input type="text" name="description" id="description"
                                            class="form-control" value="{{ old('description') }}" required>
                                    </div>
                                </div>

                                <!-- Upload fichier -->
                                <div class="mb-4">
                                    <label for="fichier_modele" class="form-label fw-bold">Fichier modèle</label>
                                    <input type="file" name="fichier_modele" id="fichier_modele" class="form-control" required accept=".pdf,.doc,.docx">
                                </div>

                                <!-- Card d’aperçu du fichier -->
                                <div id="filePreview" class="card shadow-sm border-0 d-none mt-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-file-earmark-text fs-3 text-primary me-3"></i>
                                            <div>
                                                <h6 class="mb-0" id="fileName"></h6>
                                                <small class="text-muted">Aperçu du fichier sélectionné</small>
                                            </div>
                                        </div>
                                        <div id="previewContent" class="border rounded p-2" style="min-height: 300px;">
                                            <p class="text-muted">Aucun fichier affiché...</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Enregistrer
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
document.getElementById('fichier_modele').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewCard = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const previewContent = document.getElementById('previewContent');

    if (!file) return previewCard.classList.add('d-none');

    const ext = file.name.split('.').pop().toLowerCase();

    // On vérifie le type
    if (ext !== 'doc' && ext !== 'docx') {
        alert('⚠️ Veuillez téléverser uniquement un fichier Word (.doc ou .docx)');
        e.target.value = ""; // on réinitialise le champ
        previewCard.classList.add('d-none');
        return;
    }

    fileName.textContent = file.name;
    previewCard.classList.remove('d-none');
    previewContent.innerHTML = `<p class="text-muted">Conversion en cours...</p>`;

    // Lecture du fichier Word
    const reader = new FileReader();
    reader.onload = function(event) {
        const arrayBuffer = event.target.result;
        mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
            .then(result => {
                previewContent.innerHTML = `
                    <div class="p-3 border rounded " style="max-height:500px; overflow:auto; color:black; background:white;">
                        ${result.value}
                    </div>
                `;
            })
            .catch(err => {
                previewContent.innerHTML = `<p class="text-danger">Erreur de conversion : ${err.message}</p>`;
            });
    };
    reader.readAsArrayBuffer(file);
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>

@endpush
