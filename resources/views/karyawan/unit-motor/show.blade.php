@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@section('title', 'View Unit')
@section('page-title', 'Unit Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Motor Unit Details</h5>
        <div class="btn-group">
            <a href="{{ route('karyawan.unit-motor.edit', $unitMotor) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i> Edit
            </a>
            <form action="{{ route('karyawan.unit-motor.destroy', $unitMotor) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this unit?')">
                    <i class="bi bi-trash me-2"></i> Delete
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            @if($unitMotor->foto_path && Storage::disk('local')->exists($unitMotor->foto_path))
                <div class="col-md-4 mb-3">
                    <img src="{{ route('file.unit', basename($unitMotor->foto_path)) }}" alt="Unit Photo" class="img-fluid rounded">
                </div>
            @endif
            
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Plat Nomor:</strong>
                        <p class="mb-0 fs-5 fw-bold text-primary">{{ $unitMotor->plat_nomor }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p class="mb-0">
                            @if($unitMotor->status === 'tersedia')
                                <span class="badge bg-success fs-6">Tersedia</span>
                            @elseif($unitMotor->status === 'disewa')
                                <span class="badge bg-primary fs-6">Disewa</span>
                            @else
                                <span class="badge bg-warning fs-6">Maintenance</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Motorcycle:</strong>
                        <p class="mb-0">{{ $unitMotor->motor->nama_motor }}</p>
                        <small class="text-muted">{{ $unitMotor->motor->merk }} - {{ $unitMotor->motor->tipe }} ({{ $unitMotor->motor->cc }}cc)</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Location:</strong>
                        <p class="mb-0">{{ $unitMotor->lokasiRental->nama_cabang }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        
        <h6>Rental History</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Period</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unitMotor->penyewaan as $penyewaan)
                        <tr>
                            <td>{{ $penyewaan->user->name }}</td>
                            <td>{{ $penyewaan->tanggal_mulai->format('d M Y') }} - {{ $penyewaan->tanggal_selesai->format('d M Y') }}</td>
                            <td>{{ $penyewaan->getTotalHari() }} days</td>
                            <td>
                                @if($penyewaan->status === 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($penyewaan->status === 'Accept')
                                    <span class="badge bg-success">Accepted</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No rental history</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('karyawan.unit-motor.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
</div>
@endsection