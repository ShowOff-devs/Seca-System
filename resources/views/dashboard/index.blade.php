@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <h1 class="mb-4">Dashboard</h1>
    
    <div class="row">
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-9">
                  <div class="d-flex align-items-center align-self-start">
                    <h3 class="mb-0">{{ $totalAssets }}</h3>
                    {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> --}}
                  </div>
                </div>
                <div class="col-3">

                </div>
              </div>
              <h6 class="text-muted font-weight-normal">Total Assets</h6>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-9">
                  <div class="d-flex align-items-center align-self-start">
                    <h3 class="mb-0">{{ $activeAssets }}</h3>
                  </div>
                </div>
                <div class="col-3">
                  
                </div>
              </div>
              <h6 class="text-muted font-weight-normal">Active Assets</h6>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-9">
                  <div class="d-flex align-items-center align-self-start">
                    <h3 class="mb-0">{{ $inactiveAssets }}</h3>
                  </div>
                </div>
                <div class="col-3">
                  
                </div>
              </div>
              <h6 class="text-muted font-weight-normal">Inactive Assets</h6>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                      <h3 class="mb-0">
                            <div id="clock" style="font-size: .80em;"></div>

                      </h3>
                    </div>
                  </div>
                  <div class="col-3">
                    
                  </div>
                </div>
                <h6 class="text-muted font-weight-normal">Real-Time</h6>
              </div>
          </div>
        </div>
    </div>
    <div class="row mb-4">
        @foreach($assetsPerLab as $lab)
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>{{ $lab->name }}</h5>
                            <h2>{{ $lab->assets_count }}</h2>
                            <span class="text-muted">Assets</span>
                        </div>
                        <div>
                            <span style="font-size:2.5rem; color:#007bff;">
                                <i class="mdi mdi-office-building"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5>Recent Borrowed Items</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Borrower</th>
                                <th>Asset</th>
                                <th>Laboratory</th>
                                <th>Date Borrowed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowedItems as $item)
                            <tr>
                                <td>{{ $item->borrower_name}}</td>
                                <td>{{ $item->asset->name ?? '' }}</td>
                                <td>{{ $item->laboratory->name ?? '' }}</td>
                                <td>{{ $item->date_borrowed }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Real-time clock
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').innerText =
            now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Assets per Laboratory Chart
    const ctx = document.getElementById('assetsPerLabChart').getContext('2d');
    const assetsPerLabChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($assetsPerLab->pluck('name')) !!},
            datasets: [{
                label: 'Assets per Laboratory',
                data: {!! json_encode($assetsPerLab->pluck('assets_count')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
