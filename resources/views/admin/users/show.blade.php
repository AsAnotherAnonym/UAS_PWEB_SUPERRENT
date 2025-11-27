@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@section('title', 'View User')
@section('page-title', 'User Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Details</h5>
        <div class="btn-group">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i> Edit
            </a>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                    <i class="bi bi-trash me-2"></i> Delete
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                @if($user->foto_path && Storage::disk('local')->exists($user->foto_path))
                    <img src="{{ route('file.user', basename($user->foto_path)) }}" alt="Profile" class="profile-photo-lg">
                @else
                    <div class="profile-initial profile-initial-lg d-inline-flex">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Username:</strong>
                        <p class="mb-0"><span class="badge bg-info">{{ $user->username }}</span></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong>
                        <p class="mb-0">{{ $user->phone ?? '-' }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Role:</strong>
                        <p class="mb-0">
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($user->role === 'karyawan')
                                <span class="badge bg-warning">Karyawan</span>
                            @else
                                <span class="badge bg-primary">User</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <strong>Address:</strong>
                        <p class="mb-0">{{ $user->alamat ?? '-' }}</p>
                    </div>
                    
                    @if($user->lokasiRental)
                        <div class="col-md-12 mb-3">
                            <strong>Assigned Location:</strong>
                            <p class="mb-0">{{ $user->lokasiRental->nama_cabang }}</p>
                        </div>
                    @endif
                    
                    <div class="col-md-6 mb-3">
                        <strong>Registered:</strong>
                        <p class="mb-0">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mb-0">{{ $user->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($user->role === 'user')
            <hr class="my-4">
            <h6>Rental History</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Motor</th>
                            <th>Location</th>
                            <th>Period</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->penyewaan as $penyewaan)
                            <tr>
                                <td>{{ $penyewaan->unitMotor->motor->nama_motor }}</td>
                                <td>{{ $penyewaan->lokasiRental->nama_cabang }}</td>
                                <td>{{ $penyewaan->tanggal_mulai->format('d M Y') }} - {{ $penyewaan->tanggal_selesai->format('d M Y') }}</td>
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
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
</div>
@endsection