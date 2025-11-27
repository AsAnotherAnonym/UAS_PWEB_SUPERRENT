@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@section('title', 'Location Details')
@section('page-title', 'Location Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                @if($lokasiRental->foto_path && Storage::disk('local')->exists($lokasiRental->foto_path))
                    <img src="{{ route('file.location', basename($lokasiRental->foto_path)) }}" alt="{{ $lokasiRental->nama_cabang }}" 
                         class="img-fluid rounded mb-3" style="max-height: 300px; width: 100%; object-fit: cover;">
                @endif
                
                <h3 class="mb-3">{{ $lokasiRental->nama_cabang }}</h3>
                
                <div class="mb-3">
                    <strong><i class="bi bi-geo-alt me-2"></i> Address:</strong>
                    <p class="mb-0">{{ $lokasiRental->alamat }}</p>
                </div>
                
                <div class="mb-3">
                    <strong><i class="bi bi-info-circle me-2"></i> Description:</strong>
                    <p class="mb-0">{{ $lokasiRental->deskripsi ?? 'No description available' }}</p>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Available Motorcycles ({{ $lokasiRental->unitMotor->count() }})</h5>
                    @if($lokasiRental->unitMotor->count() > 0)
                        <a href="{{ route('user.penyewaan.create', ['lokasi_id' => $lokasiRental->id]) }}" class="btn btn-primary">
                            <i class="bi bi-bicycle me-2"></i> Rent from Here
                        </a>
                    @endif
                </div>
                
                @if($lokasiRental->unitMotor->count() > 0)
                    <div class="row">
                        @foreach($lokasiRental->unitMotor as $unit)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    @if($unit->foto_path && Storage::disk('local')->exists($unit->foto_path))
                                        <img src="{{ route('file.unit', basename($unit->foto_path)) }}" class="card-img-top" alt="{{ $unit->motor->nama_motor }}" style="height: 150px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $unit->motor->nama_motor }}</h6>
                                        <p class="card-text small text-muted mb-2">
                                            {{ $unit->motor->merk }} - {{ $unit->motor->tipe }} ({{ $unit->motor->cc }}cc)
                                        </p>
                                        <p class="card-text small mb-2">{{ $unit->plat_nomor }}</p>
                                        @if($unit->status === 'tersedia')
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-secondary">Not Available</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i> No motorcycles available at this location currently.
                    </div>
                @endif
                
                <hr class="my-4">
                
                <h5 class="mb-3">Customer Reviews ({{ $lokasiRental->reviews->count() }})</h5>
                
                @forelse($lokasiRental->reviews as $review)
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <div class="my-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0">{{ $review->review }}</p>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No reviews yet. Be the first to review!</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 100px;">
            <div class="card-header">
                <h5 class="mb-0">Location Map</h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 300px; border-radius: 8px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('user.lokasi-rental.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to All Locations
    </a>
</div>
@endsection

@section('scripts')
<script>
    LeafletHelper.initSimpleMap('map', {{ $lokasiRental->latitude }}, {{ $lokasiRental->longitude }}, 15, 
        '<strong>{{ $lokasiRental->nama_cabang }}</strong><br>{{ $lokasiRental->alamat }}');
</script>
@endsection