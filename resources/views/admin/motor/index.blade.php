@extends('layouts.app')
@section('title', 'Manage Motorcycles')
@section('page-title', 'Manage Motorcycles')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Motorcycles</h5>
        <a href="{{ route('admin.motor.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Add New Motorcycle
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>CC</th>
                        <th>Total Units</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($motors as $motor)
                        <tr>
                            <td>{{ $motor->id }}</td>
                            <td><strong>{{ $motor->nama_motor }}</strong></td>
                            <td>{{ $motor->merk }}</td>
                            <td>{{ $motor->tipe }}</td>
                            <td>{{ $motor->cc }} cc</td>
                            <td>
                                <span class="badge bg-primary">{{ $motor->unit_motor_count }} units</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.motor.show', $motor) }}" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.motor.edit', $motor) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.motor.destroy', $motor) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Delete this motorcycle?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <span class="text-muted">No motorcycles found</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $motors->links() }}
        </div>
    </div>
</div>
@endsection