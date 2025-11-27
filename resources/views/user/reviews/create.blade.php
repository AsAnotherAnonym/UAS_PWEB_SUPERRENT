@extends('layouts.app')
@section('title', 'Write Review')
@section('page-title', 'Write a Review')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Share Your Experience</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.reviews.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" 
                                id="kategori" name="kategori" required>
                            <option value="">Select category...</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>
                                    {{ ucfirst($kat) }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="lokasiField" style="display: none;">
                        <label for="lokasi_rental_id" class="form-label">Location (Optional)</label>
                        <select class="form-select @error('lokasi_rental_id') is-invalid @enderror" 
                                id="lokasi_rental_id" name="lokasi_rental_id">
                            <option value="">Not related to specific location</option>
                            @foreach($lokasiRentals as $lokasi)
                                <option value="{{ $lokasi->id }}" 
                                        {{ old('lokasi_rental_id', $selectedLokasiId) == $lokasi->id ? 'selected' : '' }}>
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
                                       {{ old('rating') == $i ? 'checked' : '' }} required>
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
                                  placeholder="Tell us about your experience..." required>{{ old('review') }}</textarea>
                        @error('review')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 10 characters</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i> Submit Review
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
                <h5 class="mb-0">Review Guidelines</h5>
            </div>
            <div class="card-body">
                <h6><i class="bi bi-check-circle text-success me-2"></i> Do:</h6>
                <ul class="small">
                    <li>Be honest and constructive</li>
                    <li>Share specific details</li>
                    <li>Mention both positives and negatives</li>
                    <li>Help others make informed decisions</li>
                </ul>
                
                <h6 class="mt-3"><i class="bi bi-x-circle text-danger me-2"></i> Don't:</h6>
                <ul class="small">
                    <li>Use offensive language</li>
                    <li>Share personal information</li>
                    <li>Post fake or misleading reviews</li>
                    <li>Include external links</li>
                </ul>
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
    
    // Trigger on page load
    if (document.getElementById('kategori').value === 'tempat sewa') {
        document.getElementById('lokasiField').style.display = 'block';
    }
</script>
@endsection