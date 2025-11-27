@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@section('title', 'View Location')
@section('page-title', 'Location Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Location Details</h5>
                <div class="btn-group">
                    <a href="{{ route('admin.lokasi-rental.edit', $lokasiRental) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i> Edit
                    </a>
                    <form action="{{ route('admin.lokasi-rental.destroy', $lokasiRental) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this location?')">
                            <i class="bi bi-trash me-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($lokasiRental->foto_path && Storage::disk('local')->exists($lokasiRental->foto_path))
                    <div class="mb-3">
                        <img src="{{ route('file.location', basename($lokasiRental->foto_path)) }}" alt="Location Photo" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <strong>Branch Name:</strong>
                        <p class="mb-0">{{ $lokasiRental->nama_cabang }}</p>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <strong>Address:</strong>
                        <p class="mb-0">{{ $lokasiRental->alamat }}</p>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <strong>Description:</strong>
                        <p class="mb-0">{{ $lokasiRental->deskripsi ?? '-' }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Latitude:</strong>
                        <p class="mb-0">{{ $lokasiRental->latitude }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Longitude:</strong>
                        <p class="mb-0">{{ $lokasiRental->longitude }}</p>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <h6>Available Units ({{ $lokasiRental->unitMotor->count() }})</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Motor</th>
                                <th>Plat Nomor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lokasiRental->unitMotor as $unit)
                                <tr>
                                    <td>{{ $unit->motor->nama_motor }}</td>
                                    <td>{{ $unit->plat_nomor }}</td>
                                    <td>
                                        @if($unit->status === 'tersedia')
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($unit->status === 'disewa')
                                            <span class="badge bg-primary">Disewa</span>
                                        @else
                                            <span class="badge bg-warning">Maintenance</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No units assigned</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <hr class="my-4">
                
                <h6>Reviews ({{ $lokasiRental->reviews->count() }})</h6>
                @forelse($lokasiRental->reviews as $review)
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $review->user->name }}</strong>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="mb-0 mt-2">{{ $review->review }}</p>
                            <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No reviews yet</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Map Location</h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 400px; border-radius: 8px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.lokasi-rental.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
</div>
@endsection

@section('scripts')
<script>
    LeafletHelper.initSimpleMap('map', {{ $lokasiRental->latitude }}, {{ $lokasiRental->longitude }}, 15, '<strong>{{ $lokasiRental->nama_cabang }}</strong><br>{{ $lokasiRental->alamat }}');
</script>
@endsection