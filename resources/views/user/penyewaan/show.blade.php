@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@section('title', 'Rental Details')
@section('page-title', 'Rental Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Rental #{{ $penyewaan->id }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <strong>Status:</strong>
                    <div class="mt-1">
                        @if($penyewaan->status === 'Pending')
                            <span class="badge bg-warning fs-6">Pending Approval</span>
                        @elseif($penyewaan->status === 'Accept')
                            <span class="badge bg-success fs-6">Accepted</span>
                        @else
                            <span class="badge bg-danger fs-6">Rejected</span>
                        @endif
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Motorcycle:</strong>
                        <p class="mb-0">{{ $penyewaan->unitMotor->motor->nama_motor }}</p>
                        <small class="text-muted">
                            {{ $penyewaan->unitMotor->motor->merk }} - 
                            {{ $penyewaan->unitMotor->plat_nomor }}
                        </small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Location:</strong>
                        <p class="mb-0">{{ $penyewaan->lokasiRental->nama_cabang }}</p>
                        <small class="text-muted">{{ $penyewaan->lokasiRental->alamat }}</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Start Date:</strong>
                        <p class="mb-0">{{ $penyewaan->tanggal_mulai->format('l, d F Y') }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>End Date:</strong>
                        <p class="mb-0">{{ $penyewaan->tanggal_selesai->format('l, d F Y') }}</p>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <strong>Duration:</strong>
                        <p class="mb-0"><span class="badge bg-info fs-6">{{ $penyewaan->getTotalHari() }} days</span></p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Requested:</strong>
                        <p class="mb-0 text-muted">{{ $penyewaan->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                
                @if($penyewaan->status === 'Pending')
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-clock-history me-2"></i>
                        Your rental request is pending approval. Please wait for the location staff to review it.
                    </div>
                    
                    <form action="{{ route('user.penyewaan.cancel', $penyewaan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Are you sure you want to cancel this rental request?')">
                            <i class="bi bi-x-circle me-2"></i> Cancel Request
                        </button>
                    </form>
                @elseif($penyewaan->status === 'Accept')
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle me-2"></i>
                        Your rental has been approved! Please visit the location to pick up your motorcycle.
                    </div>
                    
                    @if($penyewaan->isActive())
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-bicycle me-2"></i>
                            <strong>Currently Active!</strong> This rental is currently in progress.
                        </div>
                    @endif
                @else
                    <div class="alert alert-danger mt-3">
                        <i class="bi bi-x-circle me-2"></i>
                        Unfortunately, your rental request was rejected. Please try another unit or contact the location.
                    </div>
                @endif
            </div>
            
            <div class="col-md-4">
                @if($penyewaan->unitMotor->foto_path && Storage::disk('local')->exists($penyewaan->unitMotor->foto_path))
                    <img src="{{ route('file.unit', basename($penyewaan->unitMotor->foto_path)) }}" 
                         alt="{{ $penyewaan->unitMotor->motor->nama_motor }}" 
                         class="img-fluid rounded mb-3">
                @endif
                
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Need Help?</h6>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-telephone me-2"></i> 
                            Contact location for assistance
                        </p>
                        <a href="{{ route('user.reviews.create', ['lokasi_id' => $penyewaan->lokasi_rental_id]) }}" 
                           class="btn btn-outline-primary btn-sm w-100 mt-2">
                            <i class="bi bi-star me-2"></i> Leave a Review
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('user.penyewaan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to My Rentals
    </a>
</div>
@endsection