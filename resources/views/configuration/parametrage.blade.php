@extends('layouts.app1')
@section('content')
<style>
.config-card {
    border: none;
    border-radius: 16px;
    transition: all 0.3s ease;
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    height: 100%;
}

.config-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}

.config-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 16px 16px 0 0;
    border: none;
}

.config-card-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.config-card-header p {
    margin: 0.5rem 0 0 0;
    font-size: 0.875rem;
    opacity: 0.9;
}

.list-item-modern {
    border: none;
    padding: 1rem 1.25rem;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.list-item-modern:hover {
    background-color: #f8f9fa;
    border-left-color: #667eea;
    transform: translateX(5px);
}

.list-item-modern .content-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.list-item-modern .icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea15, #764ba215);
    color: #667eea;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    border: 1px solid;
}

.btn-action:hover {
    transform: scale(1.1);
}

.btn-edit {
    border-color: #ffc107;
    color: #ffc107;
    background: #fff;
}

.btn-edit:hover {
    background: #ffc107;
    color: white;
}

.btn-delete {
    border-color: #dc3545;
    color: #dc3545;
    background: #fff;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
}

.add-item-link {
    display: block;
    padding: 1rem;
    text-align: center;
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    border-radius: 0 0 16px 16px;
}

.add-item-link:hover {
    background: linear-gradient(135deg, #667eea10, #764ba210);
    color: #764ba2;
}

.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
}

.page-header {
    margin-bottom: 2rem;
    padding: 1.5rem 0;
}

.page-header h4 {
    font-weight: 700;
    color: #2d3748;
    font-size: 1.75rem;
    margin: 0;
}

.modal-modern .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.modal-modern .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px 16px 0 0;
    padding: 1.5rem;
    border: none;
}

.modal-modern .modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.25rem;
}

.form-control-modern {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.btn-primary-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
}

.btn-secondary-modern {
    background: #6c757d;
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-in {
    animation: fadeInUp 0.5s ease;
}

/* Nouveau style pour le layout en 2 colonnes */
.config-section {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
    animation: fadeInUp 0.5s ease;
}

.config-section-title {
    min-width: 280px;
    flex-shrink: 0;
}

.config-section-title h5 {
    font-weight: 700;
    color: #2d3748;
    font-size: 1.3rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.config-section-title .title-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.config-section-card {
    flex: 1;
}

@media (max-width: 991px) {
    .config-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .config-section-title {
        min-width: auto;
        text-align: center;
    }
    
    .config-section-title h5 {
        justify-content: center;
    }
}
</style>

<div class="page-header animate-in">
    <h4><i class="bi bi-gear-fill me-2"></i>Configurations</h4>
</div>

<!-- Années Fiscales -->
<div class="config-section" style="animation-delay: 0.1s">
    <div class="config-section-title">
        <h5>
            <div class="title-icon" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                <i class="bi bi-calendar3"></i>
            </div>
            <span>Années<br>Fiscales</span>
        </h5>
    </div>
    <div class="config-section-card">
        <div class="config-card">
            <div class="config-card-header"style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                <h5><i class="bi bi-calendar3"></i> Années Fiscales</h5>
                <p class="mb-0">Gérez les périodes fiscales de votre organisation</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($annees as $annee)
                <li class="list-group-item list-item-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="content-wrapper">
                            <div class="icon-wrapper"  style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div>
                                <strong>{{ $annee->debut->translatedFormat('d M Y') }}</strong>
                                <span class="text-muted mx-2">–</span>
                                <strong>{{ $annee->fin->translatedFormat('d M Y') }}</strong>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-action btn-edit" onclick="openEditModal({{ $annee->id }}, {{ $annee->annee }})" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('annees.destroy', $annee->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Supprimer cette année fiscale ?')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <a href="#" class="add-item-link" data-bs-toggle="modal" data-bs-target="#createAnneeModal" style="color: #4facfe;">
                <i class="bi bi-plus-circle me-2"></i>Ajouter une Année Fiscale
            </a>
        </div>
    </div>
</div>

<!-- Types des Pièces Jointes -->
<div class="config-section" style="animation-delay: 0.2s">
    <div class="config-section-title">
        <h5>
            <div class="title-icon" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                <i class="bi bi-paperclip"></i>
            </div>
            <span>Types des<br>Pièces Jointes</span>
        </h5>
    </div>
    <div class="config-section-card">
        <div class="config-card">
            <div class="config-card-header" style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                <h5><i class="bi bi-paperclip"></i> Types des Pièces Jointes</h5>
                <p class="mb-0">Définissez les catégories de documents</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($typesPieces as $type)
                <li class="list-group-item list-item-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="content-wrapper">
                            <div class="icon-wrapper" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                                <i class="bi bi-file-earmark"></i>
                            </div>
                            <span class="badge badge-sm bg-gradient-info me-1 mb-1">{{ $type->libelle }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-action btn-edit" onclick="openEditTypeModal({{ $type->id }}, '{{ $type->libelle }}')" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('pieces.destroy', $type->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Supprimer ce type ?')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <a href="#" class="add-item-link" data-bs-toggle="modal" data-bs-target="#createTypeModal"style="color: #4facfe;">
                <i class="bi bi-plus-circle me-2"></i>Ajouter un type
            </a>
        </div>
    </div>
</div>

<!-- Types de Clauses de Contrat -->
<div class="config-section" style="animation-delay: 0.3s">
    <div class="config-section-title">
        <h5>
            <div class="title-icon" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                <i class="bi bi-file-text"></i>
            </div>
            <span>Types de<br>Clauses</span>
        </h5>
    </div>
    <div class="config-section-card">
        <div class="config-card">
            <div class="config-card-header" style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                <h5><i class="bi bi-file-text"></i> Types de Clauses de Contrat</h5>
                <p class="mb-0">Gérez les clauses contractuelles</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($typesClauses as $clause)
                <li class="list-group-item list-item-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="content-wrapper">
                            <div class="icon-wrapper" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                                <i class="bi bi-file-text"></i>
                            </div>
                            <span class="badge badge-sm bg-gradient-info me-1 mb-1">{{ $clause->libelle }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-action btn-edit" onclick="openEditClauseModal({{ $clause->id }}, '{{ $clause->libelle }}')" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('clauses.destroy', $clause->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Supprimer ce type de clause ?')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <a href="#" class="add-item-link" data-bs-toggle="modal" data-bs-target="#createClauseModal" style="color: #4facfe;">
                <i class="bi bi-plus-circle me-2"></i>Ajouter un type de clause
            </a>
        </div>
    </div>
</div>

<!-- Types de Contractants -->
<div class="config-section" style="animation-delay: 0.4s">
    <div class="config-section-title">
        <h5>
            <div class="title-icon" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                <i class="bi bi-people"></i>
            </div>
            <span>Types de<br>Contractants</span>
        </h5>
    </div>
    <div class="config-section-card">
        <div class="config-card">
            <div class="config-card-header" style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                <h5><i class="bi bi-people"></i> Types de Contractants</h5>
                <p class="mb-0">Définissez les catégories de partenaires</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($typesContractants as $contractant)
                <li class="list-group-item list-item-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="content-wrapper">
                            <div class="icon-wrapper" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <span class="badge badge-sm bg-gradient-info me-1 mb-1">{{ $contractant->libelle }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-action btn-edit" onclick="openEditContractantModal({{ $contractant->id }}, '{{ $contractant->libelle }}')" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('types_contractants.destroy', $contractant->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Supprimer ce type de contractant ?')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <a href="#" class="add-item-link" data-bs-toggle="modal" data-bs-target="#createContractantModal" style="color: #4facfe;">
                <i class="bi bi-plus-circle me-2"></i>Ajouter un type de contractant
            </a>
        </div>
    </div>
</div>

<!-- Représentants légaux -->
<div class="config-section" style="animation-delay: 0.5s">
    <div class="config-section-title">
        <h5>
            <div class="title-icon" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                <i class="bi bi-briefcase"></i>
            </div>
            <span>Représentants<br>Légaux</span>
        </h5>
    </div>
    <div class="config-section-card">
        <div class="config-card">
            <div class="config-card-header" style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                <h5><i class="bi bi-briefcase"></i> Représentants Légaux</h5>
                <p class="mb-0">Gérez les représentants de votre organisation</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($representants as $rep)
                <li class="list-group-item list-item-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="content-wrapper">
                            <div class="icon-wrapper" style="background: linear-gradient(135deg, #4facfe15, #00f2fe15); color: #4facfe;">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <span class="badge badge-sm bg-gradient-info me-1 mb-1">{{ $rep->libelle }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-action btn-edit" onclick="openEditRepresentantModal({{ $rep->id }}, '{{ $rep->libelle }}')" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('representants.destroy', $rep->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Supprimer ce représentant ?')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <a href="#" class="add-item-link" data-bs-toggle="modal" data-bs-target="#createRepresentantModal" style="color: #4facfe;">
                <i class="bi bi-plus-circle me-2"></i>Ajouter un représentant
            </a>
        </div>
    </div>
</div>

<!-- Modal Création Année Fiscale -->
<div class="modal fade modal-modern" id="createAnneeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('annees.store') }}" method="POST">
                @csrf
                <div class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nouvelle Année Fiscale</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="annee" class="form-label fw-semibold">Année</label>
                        <input type="number" class="form-control form-control-modern" id="annee" name="annee" 
                               min="2000" max="2100" step="1" required placeholder="Ex: 2025">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal modification Année Fiscale -->
<div class="modal fade" id="editAnneeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header"  class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
          <h5 class="modal-title">Modifier l’année fiscale</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editAnneeInput" class="form-label">Année</label>
            <input type="number" id="editAnneeInput" name="annee" class="form-control" min="2000" max="2100" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>





<!-- Modal Création Type de Pièce Jointe -->
<div class="modal fade" id="createTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('pieces.store') }}" method="POST">
        @csrf
        <div  class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
          <h5 class="modal-title">Ajouter un type de pièce jointe</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="libelleInput" class="form-label">Libellé</label>
            <input type="text" id="libelleInput" name="libelle" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Modification Type de Pièce Jointe -->
<div class="modal fade" id="editTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editTypeForm"  method="POST">
        @csrf
        @method('PUT')
        <div  class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
          <h5 class="modal-title">Modifier le type de pièce jointe</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editTypeInput" class="form-label">Libellé</label>
            <input type="text" id="editTypeInput" name="libelle" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Création Type de Clause -->
<div class="modal fade modal-modern" id="createClauseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('clauses.store') }}" method="POST">
                @csrf
                <div  class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Ajouter un type de clause de contrat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="libelleClause" class="form-label fw-semibold">Libellé</label>
                        <input type="text" id="libelleClause" name="libelle" class="form-control form-control-modern" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modification Type de Clause -->
<div class="modal fade" id="editClauseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editClauseForm" method="POST">
        @csrf
        @method('PUT')
        <div  class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
          <h5 class="modal-title">Modifier le type de clause de contrat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editClauseInput" class="form-label">Libellé</label>
            <input type="text" id="editClauseInput" name="libelle" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Création Type de Contractant -->
<div class="modal fade" id="createContractantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{route('types_contractants.store')}}" method="POST">
        @csrf
        <div  class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
          <h5 class="modal-title">Ajouter un type de contractant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="libelleContractant" class="form-label">Libellé</label>
            <input type="text" id="libelleContractant" name="libelle" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Modification Type de Contractant -->
<div class="modal fade" id="editContractantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editContractantForm" method="POST">
        @csrf
        @method('PUT')
        <div  class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
          <h5 class="modal-title">Modifier le type de contractant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editContractantInput" class="form-label">Libellé</label>
            <input type="text" id="editContractantInput" name="libelle" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Création Représentant Légal -->
<div class="modal fade" id="createRepresentantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('representants.store') }}" method="POST">
        @csrf
        <div class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
          <h5 class="modal-title">Ajouter un représentant légal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="representantInput" class="form-label">Nom / Libellé</label>
            <input type="text" name="libelle" id="representantInput" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Modification Représentant Légal -->
<div class="modal fade" id="editRepresentantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editRepresentantForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header"  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
          <h5 class="modal-title">Modifier le représentant légal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editRepresentantInput" class="form-label">Nom / Libellé</label>
            <input type="text" id="editRepresentantInput" name="libelle" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>





<!-- // Script pour ouvrir le modal de modification d'année fiscale -->
<script>
 function openEditModal(anneeId, anneeValue) {
    document.getElementById('editAnneeInput').value = anneeValue;
    const form = document.getElementById('editForm');
    form.action = '/annees_fiscales/' + anneeId; // correspond à update
    const modal = new bootstrap.Modal(document.getElementById('editAnneeModal'));
    modal.show();
}

</script>

<!-- // Script pour ouvrir le modal de modification de type de pièce jointe -->
<script>
function openEditTypeModal(typeId, libelle) {
    document.getElementById('editTypeInput').value = libelle;

    const form = document.getElementById('editTypeForm');
    form.action = '/' + document.documentElement.lang + '/types_pieces_jointes/' + typeId;

    const modal = new bootstrap.Modal(document.getElementById('editTypeModal'));
    modal.show();
}

</script>

<!-- // Script pour ouvrir le modal de modification de clause -->
<script>
  function openEditClauseModal(id, libelle) {
    let form = document.getElementById('editClauseForm');
    form.action = '/types_clauses_contrats/' + id; // route resource update
    document.getElementById('editClauseInput').value = libelle;
    let modal = new bootstrap.Modal(document.getElementById('editClauseModal'));
    modal.show();
  }
</script>

<!-- // Script pour ouvrir le modal de modification de type de contractant -->
 <script>
  function openEditContractantModal(id, libelle) {
    const form = document.getElementById('editContractantForm');
    form.action = form.action ='/types_contractants/' + id; 
    document.getElementById('editContractantInput').value = libelle;
    const modal = new bootstrap.Modal(document.getElementById('editContractantModal'));
    modal.show();
}

</script>
<!-- // Script pour ouvrir le modal de modification de représentant légal -->
<script>
  function openEditRepresentantModal(id, libelle) {
    const form = document.getElementById('editRepresentantForm');
    form.action = '/' + document.documentElement.lang + '/representants/' + id;
    document.getElementById('editRepresentantInput').value = libelle;

    const modal = new bootstrap.Modal(document.getElementById('editRepresentantModal'));
    modal.show();
}
</script>



@endsection
