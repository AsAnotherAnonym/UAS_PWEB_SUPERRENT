@extends('layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Rentals</p>
                    <h3 class="mb-0">{{ $totalPenyewaan }}</h3>
                    <small class="text-muted">Completed rentals</small>
                </div>
                <div class="icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-bicycle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Days Rented</p>
                    <h3 class="mb-0">{{ $totalHariSewa }}</h3>
                    <small class="text-muted">Days of riding</small>
                </div>
                <div class="icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@if($activePenyewaan)
    <!-- Active Rental -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-bicycle me-2"></i> Currently Renting</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3">Rental Details</h6>
                    
                    <div class="mb-3">
                        <strong>Motor:</strong>
                        <p class="mb-1">{{ $activePenyewaan->unitMotor->motor->nama_motor }}</p>
                        <small class="text-muted">
                            {{ $activePenyewaan->unitMotor->motor->merk }} - 
                            {{ $activePenyewaan->unitMotor->plat_nomor }}
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Location:</strong>
                        <p class="mb-0">{{ $activePenyewaan->lokasiRental->nama_cabang }}</p>
                        <small class="text-muted">{{ $activePenyewaan->lokasiRental->alamat }}</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <strong>Start Date:</strong>
                            <p class="mb-0">{{ $activePenyewaan->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                        <div class="col-6">
                            <strong>End Date:</strong>
                            <p class="mb-0">{{ $activePenyewaan->tanggal_selesai->format('d M Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <span class="badge bg-success">Active Rental</span>
                        <span class="badge bg-info">{{ $activePenyewaan->getTotalHari() }} Days</span>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('user.penyewaan.show', $activePenyewaan) }}" class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i> View Details
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h6 class="mb-3">Rental Location</h6>
                    <div id="map" style="height: 300px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- No Active Rental -->
    <div class="card mb-4 text-center py-5">
        <div class="card-body">
            <i class="bi bi-scooter display-1 text-muted mb-3"></i>
            <h4 class="mb-3">Need a ride? Hook us up!</h4>
            <p class="text-muted mb-4">You don't have any active rentals. Browse our locations to start your journey!</p>
            <a href="{{ route('user.lokasi-rental.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-geo-alt me-2"></i> Browse Locations
            </a>
        </div>
    </div>
@endif

<!-- Recent Rentals -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Rentals</h5>
        <a href="{{ route('user.penyewaan.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body">
        @if($recentPenyewaan->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Motor</th>
                            <th>Location</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPenyewaan as $penyewaan)
                            <tr>
                                <td>
                                    <strong>{{ $penyewaan->unitMotor->motor->nama_motor }}</strong><br>
                                    <small class="text-muted">{{ $penyewaan->unitMotor->plat_nomor }}</small>
                                </td>
                                <td>{{ $penyewaan->lokasiRental->nama_cabang }}</td>
                                <td>
                                    {{ $penyewaan->tanggal_mulai->format('d M Y') }} - 
                                    {{ $penyewaan->tanggal_selesai->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ $penyewaan->getTotalHari() }} days</small>
                                </td>
                                <td>
                                    @if($penyewaan->status === 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($penyewaan->status === 'Accept')
                                        <span class="badge bg-success">Accepted</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('user.penyewaan.show', $penyewaan) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted mb-0">No rental history yet</p>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($activePenyewaan)
<script>
    // Initialize map with rental location
    const map = L.map('map').setView([{{ $activePenyewaan->lokasiRental->latitude }}, {{ $activePenyewaan->lokasiRental->longitude }}], 15);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add marker with popup
    L.marker([{{ $activePenyewaan->lokasiRental->latitude }}, {{ $activePenyewaan->lokasiRental->longitude }}])
        .addTo(map)
        .bindPopup('<strong>{{ $activePenyewaan->lokasiRental->nama_cabang }}</strong><br>{{ $activePenyewaan->lokasiRental->alamat }}')
        .openPopup();
</script>
@endif
@endsection