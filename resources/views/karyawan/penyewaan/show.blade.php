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
                <strong>Customer:</strong>
                <p class="mb-0">{{ $penyewaan->user->name }}</p>
                <small class="text-muted">
                    {{ $penyewaan->user->email }}<br>
                    {{ $penyewaan->user->phone ?? '-' }}
                </small>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Motor:</strong>
                <p class="mb-0">{{ $penyewaan->unitMotor->motor->nama_motor }}</p>
                <small class="text-muted">
                    {{ $penyewaan->unitMotor->motor->merk }} - {{ $penyewaan->unitMotor->plat_nomor }}
                </small>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Location:</strong>
                <p class="mb-0">{{ $penyewaan->lokasiRental->nama_cabang }}</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Status:</strong>
                <p class="mb-0">
                    @if($penyewaan->status === 'Pending')
                        <span class="badge bg-warning fs-6">Pending</span>
                    @elseif($penyewaan->status === 'Accept')
                        <span class="badge bg-success fs-6">Accepted</span>
                    @else
                        <span class="badge bg-danger fs-6">Rejected</span>
                    @endif
                </p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Start Date:</strong>
                <p class="mb-0">{{ $penyewaan->tanggal_mulai->format('d M Y') }}</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>End Date:</strong>
                <p class="mb-0">{{ $penyewaan->tanggal_selesai->format('d M Y') }}</p>
            </div>
            
            <div class="col-md-12 mb-3">
                <strong>Total Days:</strong>
                <p class="mb-0"><span class="badge bg-info fs-6">{{ $penyewaan->getTotalHari() }} days</span></p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Requested:</strong>
                <p class="mb-0 text-muted">{{ $penyewaan->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
        
        @if($penyewaan->status === 'Pending')
            <hr class="my-4">
            <h6>Actions</h6>
            <div class="d-flex gap-2">
                <form action="{{ route('karyawan.penyewaan.accept', $penyewaan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Accept this rental request?')">
                        <i class="bi bi-check-circle me-2"></i> Accept
                    </button>
                </form>
                
                <form action="{{ route('karyawan.penyewaan.reject', $penyewaan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this rental request?')">
                        <i class="bi bi-x-circle me-2"></i> Reject
                    </button>
                </form>
            </div>
        @elseif($penyewaan->status === 'Accept' && $penyewaan->isActive())
            <hr class="my-4">
            <h6>Complete Rental</h6>
            <form action="{{ route('karyawan.penyewaan.complete', $penyewaan) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Mark this rental as completed?')">
                    <i class="bi bi-check-circle me-2"></i> Complete Rental
                </button>
            </form>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('karyawan.penyewaan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
</div>
@endsection