@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@section('title', 'Browse Locations')
@section('page-title', 'Rental Locations')

@section('content')
<!-- Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by branch name..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Map -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-map me-2"></i> All Locations</h5>
    </div>
    <div class="card-body">
        <div id="map" style="height: 400px; border-radius: 8px;"></div>
    </div>
</div>

<!-- Location Cards -->
<div class="row">
    @forelse($lokasiRentals as $lokasi)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($lokasi->foto_path && Storage::disk('local')->exists($lokasi->foto_path))
                    <img src="{{ route('file.location', basename($lokasi->foto_path)) }}" class="card-img-top" alt="{{ $lokasi->nama_cabang }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $lokasi->nama_cabang }}</h5>
                    <p class="card-text text-muted small">
                        <i class="bi bi-geo-alt"></i> {{ Str::limit($lokasi->alamat, 50) }}
                    </p>
                    <p class="card-text">{{ Str::limit($lokasi->deskripsi, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">{{ $lokasi->unit_motor_count }} units</span>
                        <a href="{{ route('user.lokasi-rental.show', $lokasi) }}" class="btn btn-sm btn-primary">
                            View Details <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
            <p class="text-muted">No locations found</p>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
    @php
        $locationsJson = $lokasiRentals->map(function($lokasi) {
            return [
                'lat' => floatval($lokasi->latitude),
                'lng' => floatval($lokasi->longitude),
                'title' => $lokasi->nama_cabang ?? '',
                'description' => $lokasi->alamat ?? '',
                'url' => route('user.lokasi-rental.show', $lokasi)
            ];
        })->values();
    @endphp

    <script>
        const locations = @json($locationsJson);
        LeafletHelper.initMultipleMarkers('map', locations, 12);
    </script>
@endsection