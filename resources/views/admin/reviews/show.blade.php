@extends('layouts.app')
@section('title', 'View Review')
@section('page-title', 'Review Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Review Details</h5>
        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this review?')">
                <i class="bi bi-trash me-2"></i> Delete
            </button>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <strong>Customer:</strong>
                    <p class="mb-1 fs-5">{{ $review->user->name }}</p>
                    <small class="text-muted">{{ $review->user->email }}</small>
                </div>
                
                <div class="mb-3">
                    <strong>Rating:</strong>
                    <div class="fs-4">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                        @endfor
                        <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Category:</strong>
                    <p class="mb-0">
                        <span class="badge bg-secondary">{{ ucfirst($review->kategori) }}</span>
                    </p>
                </div>
                
                @if($review->lokasiRental)
                    <div class="mb-3">
                        <strong>Location:</strong>
                        <p class="mb-0">{{ $review->lokasiRental->nama_cabang }}</p>
                    </div>
                @endif
                
                <div class="mb-3">
                    <strong>Review:</strong>
                    <p class="mb-0 p-3 bg-light rounded">{{ $review->review }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Submitted:</strong>
                    <p class="mb-0 text-muted">{{ $review->created_at->format('d M Y H:i') }}</p>
                </div>
                
                @if($review->updated_at != $review->created_at)
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mb-0 text-muted">{{ $review->updated_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
</div>
@endsection