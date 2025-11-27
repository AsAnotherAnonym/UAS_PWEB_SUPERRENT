@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@section('title', 'Manage Locations')
@section('page-title', 'Manage Locations')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Rental Locations</h5>
        <a href="{{ route('admin.lokasi-rental.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Add New Location
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Branch Name</th>
                        <th>Address</th>
                        <th>Coordinates</th>
                        <th>Total Units</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasiRentals as $lokasi)
                        <tr>
                            <td>{{ $lokasi->id }}</td>
                            <td>
                                <strong>{{ $lokasi->nama_cabang }}</strong>
                                @if($lokasi->foto_path && Storage::disk('local')->exists($lokasi->foto_path))
                                    <br><small class="text-muted"><i class="bi bi-image"></i> Has photo</small>
                                @endif
                            </td>
                            <td>{{ Str::limit($lokasi->alamat, 50) }}</td>
                            <td>
                                <small class="text-muted">
                                    Lat: {{ $lokasi->latitude }}<br>
                                    Lng: {{ $lokasi->longitude }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $lokasi->unit_motor_count }} units</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.lokasi-rental.show', $lokasi) }}" class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.lokasi-rental.edit', $lokasi) }}" class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.lokasi-rental.destroy', $lokasi) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" 
                                                onclick="return confirm('Are you sure you want to delete this location?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <span class="text-muted">No locations found</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-3">
            {{ $lokasiRentals->links() }}
        </div>
    </div>
</div>
@endsection