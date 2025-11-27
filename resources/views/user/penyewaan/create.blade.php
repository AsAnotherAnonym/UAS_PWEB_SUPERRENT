@extends('layouts.app')
@section('title', 'Create Rental')
@section('page-title', 'Create Rental Request')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">New Rental Request</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.penyewaan.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="unit_motor_id" class="form-label">Select Motorcycle <span class="text-danger">*</span></label>
                        <select class="form-select @error('unit_motor_id') is-invalid @enderror" 
                                id="unit_motor_id" name="unit_motor_id" required>
                            <option value="">Choose a motorcycle...</option>
                            @foreach($unitMotors as $unit)
                                <option value="{{ $unit->id }}" 
                                        {{ old('unit_motor_id', $selectedUnitId) == $unit->id ? 'selected' : '' }}
                                        data-location="{{ $unit->lokasiRental->nama_cabang }}">
                                    {{ $unit->motor->nama_motor }} ({{ $unit->plat_nomor }}) - 
                                    {{ $unit->lokasiRental->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_motor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Only available motorcycles are shown</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                   id="tanggal_mulai" name="tanggal_mulai" 
                                   value="{{ old('tanggal_mulai', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                   id="tanggal_selesai" name="tanggal_selesai" 
                                   value="{{ old('tanggal_selesai', date('Y-m-d', strtotime('+1 day'))) }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> Your rental request will be pending approval from the location staff. 
                        You'll be notified once it's approved or rejected.
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i> Submit Request
                        </button>
                        <a href="{{ route('user.penyewaan.index') }}" class="btn btn-secondary">
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
                <h5 class="mb-0">Rental Guide</h5>
            </div>
            <div class="card-body">
                <h6><i class="bi bi-1-circle me-2"></i> Choose Motorcycle</h6>
                <p class="small text-muted">Select from available motorcycles at different locations</p>
                
                <h6><i class="bi bi-2-circle me-2"></i> Select Dates</h6>
                <p class="small text-muted">Pick your rental start and end dates</p>
                
                <h6><i class="bi bi-3-circle me-2"></i> Wait for Approval</h6>
                <p class="small text-muted">Location staff will review your request</p>
                
                <h6><i class="bi bi-4-circle me-2"></i> Enjoy Your Ride!</h6>
                <p class="small text-muted">Pick up your motorcycle once approved</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Update min date for end date when start date changes
    document.getElementById('tanggal_mulai').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const nextDay = new Date(startDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        const endDateInput = document.getElementById('tanggal_selesai');
        endDateInput.min = nextDay.toISOString().split('T')[0];
        
        if (new Date(endDateInput.value) <= startDate) {
            endDateInput.value = nextDay.toISOString().split('T')[0];
        }
    });
</script>
@endsection