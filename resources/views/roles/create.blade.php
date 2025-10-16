@extends('layouts.app1')

@section('content')
<div>
    <div  class="border p-4 my-4">
        <fieldset class="border p-3">
            <legend>Création de Roles</legend>
             <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST" action="{{ route('roles.store') }}">
                                @csrf

                                {{-- Nom du rôle --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Nom du rôle</label>
                                    <input name="name" class="form-control form-control-lg" 
                                        value="{{ old('name') }}" placeholder="Ex: Administrateur" required>
                                </div>

                                {{-- Permissions par module 3 colonnes --}}
                                <h5 class="mb-3">Permissions par module</h5>
                                <div class="row g-3">
                                    @foreach($permissionsByModule as $module => $permissions)
                                        <div class="col-md-4">
                                            <div class="card h-100 border-secondary">
                                                <div class="card-header">
                                                    <div class="form-check">
                                                        <input class="form-check-input module-checkbox" type="checkbox" 
                                                            id="module_{{ Str::slug($module) }}" 
                                                            data-module-id="{{ Str::slug($module) }}">
                                                        <label class="form-check-label fw-bold" for="module_{{ Str::slug($module) }}">
                                                            {{ $module }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($permissions as $permission)
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                                name="permissions[]" 
                                                                value="{{ $permission }}"
                                                                data-module-id="{{ Str::slug($module) }}"
                                                                id="perm_{{ Str::slug($permission) }}">
                                                            <label class="form-check-label" for="perm_{{ Str::slug($permission) }}">
                                                                {{ $permission }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Bouton enregistrer --}}
                                <div class="mt-4 text-end">
                                    <button class="btn btn-success btn-lg">
                                        <i class="bi bi-check-circle me-1"></i> Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>   
        </fieldset>
    </div>
</div>



   

{{-- JS pour cocher/décocher par module --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
        moduleCheckbox.addEventListener('change', function() {
            const moduleId = this.dataset.moduleId;
            const checked = this.checked;
            document.querySelectorAll(`.permission-checkbox[data-module-id="${moduleId}"]`)
                .forEach(p => p.checked = checked);
        });
    });

    document.querySelectorAll('.permission-checkbox').forEach(function(permissionCheckbox) {
        permissionCheckbox.addEventListener('change', function() {
            const moduleId = this.dataset.moduleId;
            const moduleCheckbox = document.querySelector(`.module-checkbox[data-module-id="${moduleId}"]`);
            const allChecked = Array.from(document.querySelectorAll(`.permission-checkbox[data-module-id="${moduleId}"]`))
                                   .every(p => p.checked);
            moduleCheckbox.checked = allChecked;
        });
    });
});
</script>
@endsection
