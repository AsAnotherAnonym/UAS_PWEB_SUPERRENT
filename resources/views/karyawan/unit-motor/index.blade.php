@extends('layouts.app')
@section('title', 'Manage Units')
@section('page-title', 'Manage Motor Units')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Motor Units</h5>
        <a href="{{ route('karyawan.unit-motor.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Add New Unit
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Plat Nomor</th>
                        <th>Motorcycle</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unitMotors as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td><strong>{{ $unit->plat_nomor }}</strong></td>
                            <td>{{ $unit->motor->nama_motor }}<br><small class="text-muted">{{ $unit->motor->merk }}</small></td>
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
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('karyawan.unit-motor.show', $unit) }}" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('karyawan.unit-motor.edit', $unit) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('karyawan.unit-motor.destroy', $unit) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Delete this unit?')">
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
                                <span class="text-muted">No units found</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $unitMotors->links() }}
        </div>
    </div>
</div>
@endsection