@extends('layouts.app1')

@section('content')
<div>
     <a href="{{ route('workflows.index') }}" class="btn btn-sm btn-outline-secondary">Retour</a>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend><h6>Détails du Workflow : {{ $workflow->libelle }}</h6></legend>
           <div class="card">
        <div class="card-body">

            <h6 class="mb-3">Liste des étapes</h6>

            <a href="{{ route('workflows.etapes.create', $workflow->id) }}" class="btn btn-sm btn-primary mb-3">
                <i class="bi bi-plus-circle me-1"></i> Ajouter une étape
            </a>

            <table class="table table-bordered table-striped">
                <thead class="">
                    <tr>
                        <th class="text-center">Niveau</th>
                        <th class="text-center">Étape</th>
                        <th class="text-center">Équipe approbatrice</th>
                        <th class="text-center">Approbateur</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workflow->etapes as $etape)
                        <tr>
                            <td class="text-center">{{ $etape->niveau }}</td>
                            <td class="text-center">{{ $etape->libelle }}</td>
                            <td class="text-center">{{ $etape->user ? ucfirst($etape->user->user_type) : '-' }}</td>
                            <td class="text-center">
                                @if($etape->user)
                                    {{ $etape->user->name }} {{ $etape->user->prenom ?? '' }} 
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('workflows.etapes.edit', ['workflow' => $workflow->id, 'etape' => $etape->id]) }}" class="btn btn-sm btn-warning">Modifier</a>
                                <form action="{{ route('workflows.etapes.destroy', ['workflow' => $workflow->id, 'etape' => $etape->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette étape ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucune étape trouvée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
        </fieldset>
    </div>
</div>
   
@endsection
