@extends('layouts.app')
@section('title', 'My Reviews')
@section('page-title', 'My Reviews')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Reviews</h5>
        <a href="{{ route('user.reviews.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Write a Review
        </a>
    </div>
    <div class="card-body">
        @forelse($reviews as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }} fs-5"></i>
                                    @endfor
                                </div>
                                <span class="badge bg-secondary">{{ ucfirst($review->kategori) }}</span>
                            </div>
                            
                            @if($review->lokasiRental)
                                <p class="mb-2 text-muted small">
                                    <i class="bi bi-geo-alt me-1"></i> {{ $review->lokasiRental->nama_cabang }}
                                </p>
                            @endif
                            
                            <p class="mb-2">{{ $review->review }}</p>
                            
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i> {{ $review->created_at->format('d M Y H:i') }}
                                @if($review->created_at != $review->updated_at)
                                    (Edited)
                                @endif
                            </small>
                        </div>
                        
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('user.reviews.show', $review) }}" class="btn btn-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('user.reviews.edit', $review) }}" class="btn btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('user.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                        onclick="return confirm('Delete this review?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-star fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted mb-3">No Reviews Yet</h5>
                <p class="text-muted mb-3">Share your experience with our service!</p>
                <a href="{{ route('user.reviews.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Write Your First Review
                </a>
            </div>
        @endforelse
        
        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection