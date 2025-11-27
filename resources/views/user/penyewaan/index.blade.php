@extends('layouts.app')
@section('title', 'My Rentals')
@section('page-title', 'My Rentals')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Rental History</h5>
        <a href="{{ route('user.penyewaan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> New Rental Request
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Motorcycle</th>
                        <th>Location</th>
                        <th>Period</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penyewaans as $penyewaan)
                        <tr>
                            <td>{{ $penyewaan->id }}</td>
                            <td>
                                <strong>{{ $penyewaan->unitMotor->motor->nama_motor }}</strong><br>
                                <small class="text-muted">{{ $penyewaan->unitMotor->plat_nomor }}</small>
                            </td>
                            <td>{{ $penyewaan->lokasiRental->nama_cabang }}</td>
                            <td>
                                {{ $penyewaan->tanggal_mulai->format('d M Y') }}<br>
                                <small class="text-muted">to {{ $penyewaan->tanggal_selesai->format('d M Y') }}</small>
                            </td>
                            <td><span class="badge bg-info">{{ $penyewaan->getTotalHari() }} days</span></td>
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
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('user.penyewaan.show', $penyewaan) }}" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($penyewaan->status === 'Pending')
                                        <form action="{{ route('user.penyewaan.cancel', $penyewaan) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Cancel this rental request?')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">No rental history yet</p>
                                <a href="{{ route('user.penyewaan.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle me-2"></i> Create Your First Rental
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $penyewaans->links() }}
        </div>
    </div>
</div>
@endsection