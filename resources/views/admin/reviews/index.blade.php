@extends('layouts.app')
@section('title', 'Manage Reviews')
@section('page-title', 'All Reviews')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Customer Reviews</h5>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET">
                    <select name="kategori" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                {{ ucfirst($kat) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        @forelse($reviews as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $review->user->name }}</h6>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                                @endfor
                            </div>
                            <p class="mb-2">{{ $review->review }}</p>
                            <small class="text-muted">
                                <span class="badge bg-secondary">{{ $review->kategori }}</span>
                                @if($review->lokasiRental)
                                    | {{ $review->lokasiRental->nama_cabang }}
                                @endif
                                | {{ $review->created_at->format('d M Y') }}
                            </small>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                <span class="text-muted">No reviews yet</span>
            </div>
        @endforelse
        
        {{ $reviews->links() }}
    </div>
</div>
@endsection