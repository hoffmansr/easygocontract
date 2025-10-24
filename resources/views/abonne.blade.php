@extends('layouts.app1')

@section('content')

<div >
  <!-- 4 Cards nombre de contrats avec status -->
      <div class="row">
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('dashboard.total_contracts') }}</p>
                    <h5 class="font-weight-bolder mb-0">
                     {{ $totalContrats }}
                      <span class="text-success text-sm font-weight-bolder"></span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-info me-1 mb-1 shadow text-center border-radius-md">
                   <i class="ni ni-single-copy-04 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('dashboard.contracts_active') }}</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $contratsActifs }}
                      <span class="text-success text-sm font-weight-bolder"></span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-info me-1 mb-1 shadow text-center border-radius-md">
                   <i class="ni ni-settings text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('dashboard.contracts_in_preparation') }}</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $contratsPreparation }}
                      <span class="text-danger text-sm font-weight-bolder"></span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-info me-1 mb-1 shadow text-center border-radius-md">
                  <i class="ni ni-single-copy-04 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('dashboard.contracts_validated') }}</p>
                    <h5 class="font-weight-bolder mb-0">
                       {{ $contratsValides }}
                      <span class="text-success text-sm font-weight-bolder"></span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-info me-1 mb-1 y shadow text-center border-radius-md">
                    <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Diagramme 1 -->
      <div class="row mt-4">
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="max-w-lg mx-auto bg-white p-2 border-radius-xl shadow-md">
              <p class="text-sm mb-2 text-capitalize font-weight-bold">Nombre de contrats</p>
            <canvas id="satisfactionChart" height="200"></canvas>
         </div>
        </div>
        <!-- Diagramme 2 -->
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="max-w-lg mx-auto bg-white p-2 border-radius-xl  shadow-md">
              <p class="text-sm mb-2 text-capitalize font-weight-bold">Contrats qui arrivent à ècheance</p>
              <canvas id="contractsDoughnut" height="200"></canvas>
          </div>
        </div>
        <!-- Plan Abonnement -->
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card shadow-lg rounded-3 text-center p-4" >
            
           
            <p class="text-uppercase fw-bold mb-3" style="color: #0c4a6e; letter-spacing: 0.5px;">Plan utilisé</p>
            
           
            <div class="mb-3">
              <i class="bi bi-award" style="font-size: 3rem; color: #4facfe;"></i>
            </div>

           
            <h5 class="fw-bold mb-2" style="color: #4facfe; font-size: 1.4rem;">Premium Plan</h5>

           
            <p class="text-muted mb-1" style="font-size: 0.9rem;">10 / 1000 vérifications</p>
            <p class="text-muted mb-3" style="font-size: 0.9rem;">Il vous reste 900</p>

           
            <a href="/abonnement" class="btn btn-sm fw-bold mt-6" style="background-color: #4facfe; border-color: #4facfe; color: white; padding: 0.5rem 1.2rem; border-radius: 50px; transition: 0.3s;">
              Gérer mon plan
            </a>
          </div>
        </div>

      </div>
      <!-- Table des contrats -->
      <div class="row mb-4">
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Contrats recents</h6>
                  <p class="text-sm mb-0">
                    <i class="fa fa-check text-info" aria-hidden="true"></i>
                    <span class="font-weight-bold ms-1">{{ $totalContratsMois }}</span> this month
                  </p>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead  style="background: linear-gradient(135deg, #4facfe08, #00f2fe08); color: #6fbaff;">
                    <tr>
                      <th class=" text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Titre de contrat</th>
                      <th class="align-middle text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type de contrat</th>
                      <th class="align-middle text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                      <th class="align-middle text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Assigné à</th>
                      <th class="align-midd text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date début</th>
                      <th class="align-midd text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date fin</th>
                    </tr>
                  </thead>
                <tbody>
                    @forelse ($recentContrats as $contrat)
                        <tr>
                            <td class="align-middle text-center text-sm">
                                <h6 class="mb-0 text-sm">{{ $contrat->titre }}</h6>
                            </td>
                            <td>
                                <h6 class="mb-0 text-sm">{{ $contrat->typesContrat->libelle ?? '—' }}</h6>
                            </td>
                            <td class="align-middle text-center text-sm">
                                @switch($contrat->statut)
                                    @case('ebauche')
                                        <span class="badge badge-sm bg-gradient-secondary">
                                            <i class="bi bi-pencil-square me-1"></i>Ébauche
                                        </span>
                                    @break
                                    @case('revise')
                                        <span class="badge badge-sm bg-gradient-warning">
                                            <i class="bi bi-search me-1"></i>Révisé
                                        </span>
                                    @break
                                    @case('en_approbation')
                                        <span class="badge badge-sm bg-gradient-info">
                                            <i class="bi bi-people me-1"></i>En approbation
                                        </span>
                                    @break
                                    @case('approuve')
                                        <span class="badge badge-sm bg-gradient-primary">
                                            <i class="bi bi-check-circle me-1"></i>Approuvé
                                        </span>
                                    @break
                                    @case('signe')
                                        <span class="badge badge-sm bg-gradient-dark">
                                            <i class="bi bi-pencil me-1"></i>Signé
                                        </span>
                                    @break
                                    @case('actif')
                                        <span class="badge badge-sm bg-gradient-success">
                                            <i class="bi bi-play-circle me-1"></i>Actif
                                        </span>
                                    @break
                                    @case('annule')
                                        <span class="badge badge-sm bg-gradient-danger">
                                            <i class="bi bi-x-circle me-1"></i>Annulé
                                        </span>
                                    @break
                                    @case('expire')
                                        <span class="badge badge-sm bg-gradient-secondary">
                                            <i class="bi bi-hourglass-split me-1"></i>Expiré
                                        </span>
                                    @break
                                    @case('renouvele')
                                        <span class="badge badge-sm bg-gradient-info">
                                            <i class="bi bi-arrow-repeat me-1"></i>Renouvelé
                                        </span>
                                    @break
                                    @default
                                        <span class="badge badge-sm bg-gradient-secondary">Inconnu</span>
                                @endswitch
                            </td>
                            <td class="align-middle text-center text-sm">
                                <div class="avatar-group mt-2">
                                    @if($contrat->workflow)
                                        @foreach($contrat->workflow->etapes as $etape)
                                            @php
                                                $user = $etape->user;
                                            @endphp
                                            @if($user)
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle border border-primary"
                                                  data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                  title="{{ $user->name }}">
                                                   <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/default-user.png') }}" alt="{{ $user->name }}">

                                                </a>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                             <td class="align-middle text-center" ><h6 class="mb-0 text-sm">{{ $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : '' }}</h6></td>
                              <td class="align-middle text-center"><h6 class="mb-0 text-sm">{{ $contrat->date_fin ? $contrat->date_fin->format('d/m/Y') : '' }}</h6></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun contrat récent</td>
                        </tr>
                    @endforelse
                </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card h-100">
            <div class="card-header pb-0">
              <h6>Orders overview</h6>
              <p class="text-sm">
                <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                <span class="font-weight-bold">24%</span> this month
              </p>
            </div>
            <div class="card-body p-3">
              <div class="timeline timeline-one-side">
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="material-icons text-success text-gradient">notifications</i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">$2400, Design changes</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 7:20 PM</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="material-icons text-danger text-gradient">code</i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">New order #1832412</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21 DEC 11 PM</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="material-icons text-info text-gradient">shopping_cart</i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Server payments for April</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21 DEC 9:34 PM</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="material-icons text-warning text-gradient">credit_card</i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">New card added for order #4395133</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">20 DEC 2:20 AM</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="material-icons text-primary text-gradient">key</i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Unlock packages for development</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">18 DEC 4:54 AM</p>
                  </div>
                </div>
                <div class="timeline-block">
                  <span class="timeline-step">
                    <i class="material-icons text-dark text-gradient">payments</i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">New order #9583120</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">17 DEC</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <script src="{{url('assets/js/plugins/chartjs.min.js')}}"></script>
@endsection

@push('custom-scripts')
<!-- Diagramme 1 -->
    <script>
const ctx = document.getElementById('satisfactionChart').getContext('2d');

new Chart(ctx, {
  type: 'pie',
  data: {
    labels: @json($contratsParStatut->keys()),
    datasets: [{
      data: @json($contratsParStatut->values()),
      backgroundColor: [
        '#4facfe', '#6fbaff', '#8ed1ff', '#add8ff', '#cae7ff', '#e6f4ff'
      ],
      borderColor: '#fff',
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          usePointStyle: true,
          font: { size: 10 }
        }
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            return context.label + ': ' + context.raw;
          }
        }
      }
    }
  }
});
</script>

<!-- Diagramme 2 -->
<script>
const ctx2 = document.getElementById('contractsDoughnut').getContext('2d');

new Chart(ctx2, {
  type: 'doughnut',
  data: {
    labels: @json($contratsEcheance->keys()),
    datasets: [{
      data: @json($contratsEcheance->values()),
      backgroundColor: ['#8ed1ff', '#f8c0aaff', '#4facfe'],
      borderColor: '#fff',
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    cutout: '65%',
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          usePointStyle: true,
          font: { size: 10 }
        }
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            return context.label + ': ' + context.raw;
          }
        }
      }
    }
  }
});
</script>
 @endpush

