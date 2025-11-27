@extends('layouts.app')
@section('title', 'View Motorcycle')
@section('page-title', 'Motorcycle Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Motorcycle Details</h5>
        <div class="btn-group">
            <a href="{{ route('admin.motor.edit', $motor) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i> Edit
            </a>
            <form action="{{ route('admin.motor.destroy', $motor) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this motorcycle?')">
                    <i class="bi bi-trash me-2"></i> Delete
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>Motorcycle Name:</strong>
                <p class="mb-0">{{ $motor->nama_motor }}</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Brand:</strong>
                <p class="mb-0">{{ $motor->merk }}</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Type:</strong>
                <p class="mb-0">{{ $motor->tipe }}</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Engine Capacity:</strong>
                <p class="mb-0">{{ $motor->cc }} cc</p>
            </div>
            
            <div class="col-md-12 mb-3">
                <strong>Description:</strong>
                <p class="mb-0">{{ $motor->deskripsi ?? '-' }}</p>
            </div>
            
            <div class="col-md-6 mb-3">
                <strong>Total Units:</strong>
                <p class="mb-0"><span class="badge bg-primary">{{ $motor->unitMotor->count() }} units</span></p>
            </div>
        </div>
        
        <hr class="my-4">
        
        <h6>Units by Location</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($motor->unitMotor as $unit)
                        <tr>
                            <td>{{ $unit->plat_nomor }}</td>
                            <td>{{ $unit->lokasiRental->nama_cabang }}</td>
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
                            <td colspan="3" class="text-center text-muted">No units assigned yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.motor.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
</div>
@endsection