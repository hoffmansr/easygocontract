@extends('layouts.app2')

@section('content')
  <!-- 4 Cards -->
  <div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Sociétés</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $totalSocietes ?? 0 }}
                  <span class="text-success text-sm font-weight-bolder">+{{ $newSocietesMois ?? 0 }}</span>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-building text-lg opacity-10"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Abonnements actifs</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $abonnementsActifs ?? 0 }}
                  <span class="text-success text-sm font-weight-bolder">+{{ $nouveauxActifs ?? 0 }}</span>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-check-bold text-lg opacity-10"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Abonnements expirés</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $abonnementsExpirés ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-fat-remove text-lg opacity-10"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Revenus totaux</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ number_format($revenusTotaux ?? 0, 0, ',', ' ') }} FCFA
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-credit-card text-lg opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Graphiques -->
  <div class="row mt-4">
    <div class="col-lg-6 col-md-6 mb-4">
      <div class="card shadow">
        <div class="card-header pb-0">
          <h6>Évolution des sociétés</h6>
        </div>
        <div class="card-body">
          <canvas id="societesChart" height="200"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 mb-4">
      <div class="card shadow">
        <div class="card-header pb-0">
          <h6>Répartition des abonnements</h6>
        </div>
        <div class="card-body">
          <canvas id="abonnementsChart" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Table des sociétés -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header pb-0 d-flex justify-content-between">
          <h6>Sociétés récentes</h6>
          <a href="{{ route('societes.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Abonnement</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Créée le</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentSocietes ?? [] as $societe)
                <tr>
                  <td class="text-sm">{{ $societe->nom }}</td>
                  <td class="text-sm">{{ $societe->email ?? '-' }}</td>
                  <td class="text-sm">{{ $societe->plan ?? 'Free' }}</td>
                  <td class="text-sm">
                    @if($societe->actif)
                      <span class="badge bg-success">Actif</span>
                    @else
                      <span class="badge bg-danger">Inactif</span>
                    @endif
                  </td>
                  <td class="text-center text-sm">{{ $societe->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center py-4">Aucune société trouvée</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Charts -->
@push('scripts')
<script src="{{ url('assets/js/plugins/chartjs.min.js') }}"></script>
<script>
  // Evolution sociétés
  new Chart(document.getElementById('societesChart').getContext('2d'), {
    type: 'line',
    data: {
      labels: @json($mois ?? []),
      datasets: [{
        label: 'Nouvelles sociétés',
        data: @json($societesParMois ?? []),
        borderColor: '#06b6d4',
        backgroundColor: 'rgba(6, 182, 212, 0.2)',
        fill: true
      }]
    }
  });

  // Répartition abonnements
  new Chart(document.getElementById('abonnementsChart').getContext('2d'), {
    type: 'doughnut',
    data: {
      labels: ['Free', 'Pro', 'Premium'],
      datasets: [{
        data: @json($abonnementsRepartition ?? [0,0,0]),
        backgroundColor: ['#0ea5e9', '#06b6d4', '#22d3ee']
      }]
    }
  });
</script>
@endpush
@endsection
