@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@section('title', 'Edit Location')
@section('page-title', 'Edit Location')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Location: {{ $lokasiRental->nama_cabang }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.lokasi-rental.update', $lokasiRental) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @if($lokasiRental->foto_path && Storage::disk('local')->exists($lokasiRental->foto_path))
                        <div class="mb-3">
                            <img src="{{ route('file.location', basename($lokasiRental->foto_path)) }}" alt="Current Photo" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="nama_cabang" class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_cabang') is-invalid @enderror" 
                               id="nama_cabang" name="nama_cabang" value="{{ old('nama_cabang', $lokasiRental->nama_cabang) }}" required>
                        @error('nama_cabang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat', $lokasiRental->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Description</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $lokasiRental->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('latitude') is-invalid @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude', $lokasiRental->latitude) }}" required readonly>
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude', $lokasiRental->longitude) }}" required readonly>
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="foto_path" class="form-label">Location Photo</label>
                        <input type="file" class="form-control @error('foto_path') is-invalid @enderror" 
                               id="foto_path" name="foto_path" accept="image/*">
                        @error('foto_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Max 2MB (JPG, PNG)</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Update Location
                        </button>
                        <a href="{{ route('admin.lokasi-rental.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Map Location</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Drag the marker to update location</p>
                <div id="map" style="height: 400px; border-radius: 8px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const defaultLat = {{ $lokasiRental->latitude }};
    const defaultLng = {{ $lokasiRental->longitude }};
    
    LeafletHelper.initDraggableMarker('map', 'latitude', 'longitude', defaultLat, defaultLng, 15);
</script>
@endsection