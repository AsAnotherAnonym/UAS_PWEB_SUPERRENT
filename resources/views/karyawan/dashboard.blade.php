@extends('layouts.app')
@section('title', 'Karyawan Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Units</p>
                    <h3 class="mb-0">{{ $totalUnit }}</h3>
                </div>
                <div class="icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-bicycle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Available</p>
                    <h3 class="mb-0 text-success">{{ $unitTersedia }}</h3>
                </div>
                <div class="icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Rented</p>
                    <h3 class="mb-0 text-primary">{{ $unitDisewa }}</h3>
                </div>
                <div class="icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-bicycle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Maintenance</p>
                    <h3 class="mb-0 text-warning">{{ $unitMaintenance }}</h3>
                </div>
                <div class="icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-tools"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card border-start border-4 border-primary">
            <p class="text-muted mb-1">Total Rentals</p>
            <h3 class="mb-0">{{ $totalPenyewaan }}</h3>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="stats-card border-start border-4 border-warning">
            <p class="text-muted mb-1">Pending</p>
            <h3 class="mb-0 text-warning">{{ $pendingPenyewaan }}</h3>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="stats-card border-start border-4 border-success">
            <p class="text-muted mb-1">Accepted</p>
            <h3 class="mb-0 text-success">{{ $acceptedPenyewaan }}</h3>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Recent Rentals</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Motor</th>
                        <th>Period</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPenyewaan as $penyewaan)
                        <tr>
                            <td>{{ $penyewaan->user->name }}</td>
                            <td>{{ $penyewaan->unitMotor->motor->nama_motor }}</td>
                            <td>{{ $penyewaan->tanggal_mulai->format('d M') }} - {{ $penyewaan->tanggal_selesai->format('d M') }}</td>
                            <td>
                                @if($penyewaan->status === 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($penyewaan->status === 'Accept')
                                    <span class="badge bg-success">Accepted</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No rentals yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection