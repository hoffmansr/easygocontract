@extends('layouts.app1')

@section('content')
<div>
    <a href="{{ route('modeles_contrats.index') }}" class="btn btn-sm btn-outline-secondary ">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>

    <div class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Modèle de Contrat</legend>

            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-4 pb-4">
                            <h4 class="mb-4">{{ $modeles_contrat->titre }}</h4>
                            <p><strong>Langue :</strong> {{ strtoupper($modeles_contrat->langue) }}</p>
                            <p><strong>Description :</strong> {{ $modeles_contrat->description }}</p>

                            <div class="card shadow-sm border-0 mt-3">
                                <div class="card-body">
                                    <h6 class="mb-3">Aperçu du fichier</h6>

                                    @php
                                        $ext = strtolower(pathinfo($modeles_contrat->fichier_modele, PATHINFO_EXTENSION));
                                        $url = asset('storage/'.$modeles_contrat->fichier_modele);
                                    @endphp

                                    {{-- ✅ Si c’est un PDF --}}
                                    @if($ext === 'pdf')
                                        <iframe src="{{ $url }}" width="100%" height="600px" style="border:none;"></iframe>

                                    {{-- ✅ Si c’est un Word et qu’on a converti en HTML --}}
                                    @elseif(in_array($ext, ['doc', 'docx']) && $modeles_contrat->html_contenu)
                                        <div id="wordPreview" class="border p-3 rounded bg-white" style="color:black;">
                                            {!! $modeles_contrat->html_contenu !!}
                                        </div>

                                    {{-- ⚠️ Sinon, message d’avertissement --}}
                                    @elseif(in_array($ext, ['doc', 'docx']))
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> Ce fichier Word n’a pas encore été converti.<br>
                                            <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                                                <i class="bi bi-box-arrow-up-right"></i> Ouvrir le fichier
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-danger">Type de fichier non supporté pour la prévisualisation.</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
@endsection

{{-- 🎨 Style pour que le texte du Word soit lisible --}}
@push('styles')
<style>
#wordPreview {
    background: #fff;
    color: #000;
    font-family: "Segoe UI", Arial, sans-serif;
    line-height: 1.6;
}
#wordPreview * {
    color: #000 !important;
}
</style>
@endpush
