@extends('layouts.app')
@section('title', 'Manage Rentals')
@section('page-title', 'Manage Rentals')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Rental Requests</h5>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Accept" {{ request('status') == 'Accept' ? 'selected' : '' }}>Accepted</option>
                        <option value="Tolak" {{ request('status') == 'Tolak' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Motor</th>
                        <th>Location</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penyewaans as $penyewaan)
                        <tr>
                            <td>{{ $penyewaan->id }}</td>
                            <td>{{ $penyewaan->user->name }}</td>
                            <td>{{ $penyewaan->unitMotor->motor->nama_motor }}<br>
                                <small class="text-muted">{{ $penyewaan->unitMotor->plat_nomor }}</small>
                            </td>
                            <td>{{ $penyewaan->lokasiRental->nama_cabang }}</td>
                            <td>{{ $penyewaan->tanggal_mulai->format('d M Y') }} - {{ $penyewaan->tanggal_selesai->format('d M Y') }}<br>
                                <small class="text-muted">{{ $penyewaan->getTotalHari() }} days</small>
                            </td>
                            <td>
                                @if($penyewaan->status === 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($penyewaan->status === 'Accept')
                                    <span class="badge bg-success">Accepted</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.penyewaan.show', $penyewaan) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <span class="text-muted">No rentals found</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $penyewaans->links() }}
    </div>
</div>
@endsection