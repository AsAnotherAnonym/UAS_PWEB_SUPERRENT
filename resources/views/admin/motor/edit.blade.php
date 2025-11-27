@extends('layouts.app')
@section('title', 'Edit Motorcycle')
@section('page-title', 'Edit Motorcycle')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Motorcycle: {{ $motor->nama_motor }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.motor.update', $motor) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nama_motor" class="form-label">Motorcycle Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_motor') is-invalid @enderror" 
                               id="nama_motor" name="nama_motor" value="{{ old('nama_motor', $motor->nama_motor) }}" required>
                        @error('nama_motor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="merk" class="form-label">Brand <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                               id="merk" name="merk" value="{{ old('merk', $motor->merk) }}" required>
                        @error('merk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipe" class="form-label">Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tipe') is-invalid @enderror" 
                               id="tipe" name="tipe" value="{{ old('tipe', $motor->tipe) }}" required>
                        @error('tipe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="cc" class="form-label">Engine Capacity (CC) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('cc') is-invalid @enderror" 
                               id="cc" name="cc" value="{{ old('cc', $motor->cc) }}" min="50" max="2000" required>
                        @error('cc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Description</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $motor->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Update Motorcycle
                        </button>
                        <a href="{{ route('admin.motor.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection