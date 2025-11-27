@extends('layouts.app')
@section('title', 'Edit Review')
@section('page-title', 'Edit Review')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Your Review</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.reviews.update', $review) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" 
                                id="kategori" name="kategori" required>
                            <option value="">Select category...</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat }}" {{ old('kategori', $review->kategori) == $kat ? 'selected' : '' }}>
                                    {{ ucfirst($kat) }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="lokasiField" style="display: {{ old('kategori', $review->kategori) == 'tempat sewa' ? 'block' : 'none' }};">
                        <label for="lokasi_rental_id" class="form-label">Location (Optional)</label>
                        <select class="form-select @error('lokasi_rental_id') is-invalid @enderror" 
                                id="lokasi_rental_id" name="lokasi_rental_id">
                            <option value="">Not related to specific location</option>
                            @foreach($lokasiRentals as $lokasi)
                                <option value="{{ $lokasi->id }}" 
                                        {{ old('lokasi_rental_id', $review->lokasi_rental_id) == $lokasi->id ? 'selected' : '' }}>
                                    {{ $lokasi->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        @error('lokasi_rental_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rating <span class="text-danger">*</span></label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                       {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                <label for="star{{ $i }}" class="star">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="review" class="form-label">Your Review <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('review') is-invalid @enderror" 
                                  id="review" name="review" rows="5" 
                                  placeholder="Tell us about your experience..." required>{{ old('review', $review->review) }}</textarea>
                        @error('review')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 10 characters</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Update Review
                        </button>
                        <a href="{{ route('user.reviews.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Review Info</h5>
            </div>
            <div class="card-body">
                <p class="small mb-2">
                    <strong>Originally posted:</strong><br>
                    {{ $review->created_at->format('d M Y H:i') }}
                </p>
                @if($review->created_at != $review->updated_at)
                    <p class="small mb-2">
                        <strong>Last updated:</strong><br>
                        {{ $review->updated_at->format('d M Y H:i') }}
                    </p>
                @endif
                
                <hr class="my-3">
                
                <p class="small text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i> 
                    You can edit your review at any time. Changes will be marked as edited.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show location field only for "tempat sewa" category
    document.getElementById('kategori').addEventListener('change', function() {
        const lokasiField = document.getElementById('lokasiField');
        if (this.value === 'tempat sewa') {
            lokasiField.style.display = 'block';
        } else {
            lokasiField.style.display = 'none';
            document.getElementById('lokasi_rental_id').value = '';
        }
    });
</script>
@endsection