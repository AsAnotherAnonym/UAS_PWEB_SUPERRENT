<div class="sidebar" id="sidebar">
    <div class="brand">
        <i class="bi bi-scooter"></i>
        <span>SuperRent</span>
    </div>
    
    <nav class="nav flex-column">
        @if(auth()->user()->isAdmin())
            <!-- Admin Navigation -->
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="nav-section">Management</div>
            
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
            
            <a href="{{ route('admin.lokasi-rental.index') }}" class="nav-link {{ request()->routeIs('admin.lokasi-rental.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i>
                <span>Locations</span>
            </a>
            
            <a href="{{ route('admin.motor.index') }}" class="nav-link {{ request()->routeIs('admin.motor.*') ? 'active' : '' }}">
                <i class="bi bi-bicycle"></i>
                <span>Motorcycles</span>
            </a>
            
            <a href="{{ route('admin.unit-motor.index') }}" class="nav-link {{ request()->routeIs('admin.unit-motor.*') ? 'active' : '' }}">
                <i class="bi bi-stack"></i>
                <span>Units</span>
            </a>
            
            <a href="{{ route('admin.penyewaan.index') }}" class="nav-link {{ request()->routeIs('admin.penyewaan.*') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                <span>Rentals</span>
            </a>
            
            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                <span>Reviews</span>
            </a>
            
        @elseif(auth()->user()->isKaryawan())
            <!-- Karyawan Navigation -->
            <a href="{{ route('karyawan.dashboard') }}" class="nav-link {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="nav-section">My Location</div>
            
            <a href="{{ route('karyawan.unit-motor.index') }}" class="nav-link {{ request()->routeIs('karyawan.unit-motor.*') ? 'active' : '' }}">
                <i class="bi bi-stack"></i>
                <span>Units</span>
            </a>
            
            <a href="{{ route('karyawan.penyewaan.index') }}" class="nav-link {{ request()->routeIs('karyawan.penyewaan.*') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                <span>Rentals</span>
            </a>
            
        @else
            <!-- User Navigation -->
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i>
                <span>Home</span>
            </a>
            
            <a href="{{ route('user.lokasi-rental.index') }}" class="nav-link {{ request()->routeIs('user.lokasi-rental.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i>
                <span>Locations</span>
            </a>
            
            <a href="{{ route('user.penyewaan.index') }}" class="nav-link {{ request()->routeIs('user.penyewaan.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>My Rentals</span>
            </a>
            
            <a href="{{ route('user.reviews.index') }}" class="nav-link {{ request()->routeIs('user.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                <span>My Reviews</span>
            </a>
        @endif
        
        <div class="nav-section">Account</div>
        
        <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    </nav>
</div>