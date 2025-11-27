@extends('layouts.app')

@section('title', 'Login - SuperRent')

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="brand-icon">
                                <i class="bi bi-scooter"></i>
                            </div>
                            <h3 class="mt-4 fw-bold" style="color: var(--dark-gray);">Welcome Back!</h3>
                            <p class="text-muted">Login to access your account</p>
                        </div>
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif
                        
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                           id="username" name="username" value="{{ old('username') }}" 
                                           placeholder="Enter your username" required autofocus>
                                </div>
                                @error('username')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Enter your password" required>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">Don't have an account? 
                                <a href="{{ route('register') }}" class="fw-semibold" style="color: var(--primary-green); text-decoration: none;">Register here</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-white-50">&copy; 2024 SuperRent. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection