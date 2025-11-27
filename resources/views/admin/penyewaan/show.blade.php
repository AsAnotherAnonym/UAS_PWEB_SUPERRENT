@extends('layouts.app')
@section('title', 'View Rental')
@section('page-title', 'Rental Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Rental Request #{{ $penyewaan->id }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>User:</strong>
                <p>{{ $penyewaan->user->name }}<br>
                   <small class="text-muted">{{ $penyewaan->user->email }}</small>
                </p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Motor:</strong>
                <p>{{ $penyewaan->unitMotor->motor->nama_motor }}<br>
                   <small class="text-muted">{{ $penyewaan->unitMotor->plat_nomor }}</small>
                </p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Location:</strong>
                <p>{{ $penyewaan->lokasiRental->nama_cabang }}</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Status:</strong>
                <p>
                    @if($penyewaan->status === 'Pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($penyewaan->status === 'Accept')
                        <span class="badge bg-success">Accepted</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Start Date:</strong>
                <p>{{ $penyewaan->tanggal_mulai->format('d M Y') }}</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>End Date:</strong>
                <p>{{ $penyewaan->tanggal_selesai->format('d M Y') }}</p>
            </div>
            
            <div class="col-md-12 mb-3">
                <strong>Total Days:</strong>
                <p><span class="badge bg-info">{{ $penyewaan->getTotalHari() }} days</span></p>
            </div>
        </div>
        
        @if($penyewaan->status === 'Pending')
            <hr class="my-4">
            <h6>Actions</h6>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.penyewaan.accept', $penyewaan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Accept this rental?')">
                        <i class="bi bi-check-circle me-2"></i> Accept
                    </button>
                </form>
                
                <form action="{{ route('admin.penyewaan.reject', $penyewaan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this rental?')">
                        <i class="bi bi-x-circle me-2"></i> Reject
                    </button>
                </form>
            </div>
        @elseif($penyewaan->status === 'Accept')
            <hr class="my-4">
            <form action="{{ route('admin.penyewaan.complete', $penyewaan) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Mark as completed?')">
                    <i class="bi bi-check-circle me-2"></i> Complete Rental
                </button>
            </form>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.penyewaan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
</div>
@endsection