@extends('layouts.app')
@section('title', 'Add Unit')
@section('page-title', 'Add Motor Unit')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create New Motor Unit</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan.unit-motor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Unit will be assigned to: <strong>{{ auth()->user()->lokasiRental->nama_cabang ?? 'Your Location' }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <label for="motor_id" class="form-label">Motorcycle <span class="text-danger">*</span></label>
                        <select class="form-select @error('motor_id') is-invalid @enderror" id="motor_id" name="motor_id" required>
                            <option value="">Select Motorcycle</option>
                            @foreach($motors as $motor)
                                <option value="{{ $motor->id }}" {{ old('motor_id') == $motor->id ? 'selected' : '' }}>
                                    {{ $motor->nama_motor }} - {{ $motor->merk }} ({{ $motor->cc }}cc)
                                </option>
                            @endforeach
                        </select>
                        @error('motor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="plat_nomor" class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('plat_nomor') is-invalid @enderror" 
                               id="plat_nomor" name="plat_nomor" value="{{ old('plat_nomor') }}" 
                               placeholder="e.g., DK 1234 AB" required>
                        @error('plat_nomor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="disewa" {{ old('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="foto_path" class="form-label">Unit Photo</label>
                        <input type="file" class="form-control @error('foto_path') is-invalid @enderror" 
                               id="foto_path" name="foto_path" accept="image/*">
                        @error('foto_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Max 2MB (JPG, PNG)</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Save Unit
                        </button>
                        <a href="{{ route('karyawan.unit-motor.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection