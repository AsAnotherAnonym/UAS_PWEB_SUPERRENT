<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SuperRent - Motorcycle Rental System')</title>
    
    <!-- Bootstrap 5 CSS (Local) -->
    <link href="{{ asset('assets/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Leaflet CSS (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}" />
    
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>
    
    @auth
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            @include('layouts.navbar')
            
            <!-- Content -->
            <div class="content-wrapper">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    @else
        @yield('content')
    @endauth
    
    <!-- jQuery (Local if needed, or use CDN) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap 5 JS (Local) -->
    <script src="{{ asset('assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Leaflet JS (Local) -->
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    
    <!-- Leaflet Helper -->
    <script src="{{ asset('assets/js/leaflet-helper.js') }}"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    
    @yield('scripts')
</body>
</html>