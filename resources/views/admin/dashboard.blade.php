@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="form-label mb-0">Time Range:</label>
            </div>
            <div class="col-auto">
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7" {{ $filter == '7' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ $filter == '30' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="365" {{ $filter == '365' ? 'selected' : '' }}>Last 1 Year</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Users</p>
                    <h3 class="mb-0">{{ $totalUsers }}</h3>
                </div>
                <div class="icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Karyawan</p>
                    <h3 class="mb-0">{{ $totalKaryawan }}</h3>
                </div>
                <div class="icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Locations</p>
                    <h3 class="mb-0">{{ $totalLokasi }}</h3>
                </div>
                <div class="icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-geo-alt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Units</p>
                    <h3 class="mb-0">{{ $totalUnit }}</h3>
                </div>
                <div class="icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-bicycle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Penyewaan Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card border-start border-4 border-primary">
            <p class="text-muted mb-1">Total Rentals</p>
            <h3 class="mb-0">{{ $totalPenyewaan }}</h3>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card border-start border-4 border-warning">
            <p class="text-muted mb-1">Pending</p>
            <h3 class="mb-0 text-warning">{{ $pendingPenyewaan }}</h3>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card border-start border-4 border-success">
            <p class="text-muted mb-1">Accepted</p>
            <h3 class="mb-0 text-success">{{ $acceptedPenyewaan }}</h3>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card border-start border-4 border-danger">
            <p class="text-muted mb-1">Rejected</p>
            <h3 class="mb-0 text-danger">{{ $rejectedPenyewaan }}</h3>
        </div>
    </div>
</div>

<!-- Unit Status -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <p class="text-muted mb-1">Available Units</p>
            <h4 class="mb-0 text-success">{{ $unitTersedia }}</h4>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <p class="text-muted mb-1">Rented Units</p>
            <h4 class="mb-0 text-primary">{{ $unitDisewa }}</h4>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <p class="text-muted mb-1">Maintenance</p>
            <h4 class="mb-0 text-warning">{{ $unitMaintenance }}</h4>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Rental Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="rentalChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Top Motors</h5>
            </div>
            <div class="card-body">
                @forelse($topMotors as $motor)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="mb-0 fw-semibold">{{ $motor['nama_motor'] }}</p>
                        </div>
                        <span class="badge bg-primary">{{ $motor['count'] }} rentals</span>
                    </div>
                @empty
                    <p class="text-muted text-center">No data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Top Locations</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($topLokasi as $lokasi)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    <span class="fw-semibold">{{ $lokasi['nama_cabang'] }}</span>
                                </div>
                                <span class="badge bg-success">{{ $lokasi['count'] }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Rental Trends Chart
    const ctx = document.getElementById('rentalChart').getContext('2d');
    const rentalChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Rentals',
                data: @json($chartData['data']),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection