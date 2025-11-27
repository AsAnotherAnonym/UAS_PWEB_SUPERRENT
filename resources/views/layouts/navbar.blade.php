@php
use Illuminate\Support\Facades\Storage;
@endphp

<nav class="top-navbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <button class="btn btn-link d-md-none text-dark" id="sidebarToggle">
            <i class="bi bi-list fs-3"></i>
        </button>
        <h5 class="mb-0 d-none d-md-inline">@yield('page-title', 'Dashboard')</h5>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <!-- User Info -->
        <div class="dropdown">
            <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2 p-0" type="button" id="userDropdown" data-bs-toggle="dropdown">
                @if(auth()->user()->foto_path && Storage::disk('local')->exists(auth()->user()->foto_path))
                    <img src="{{ route('file.user', basename(auth()->user()->foto_path)) }}" alt="Profile" class="profile-photo">
                @else
                    <div class="profile-initial">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="d-none d-md-block text-start">
                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                        @if(auth()->user()->isAdmin())
                            Administrator
                        @elseif(auth()->user()->isKaryawan())
                            Karyawan
                        @else
                            Customer
                        @endif
                    </div>
                </div>
                <i class="bi bi-chevron-down text-muted"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="bi bi-person me-2"></i> Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>