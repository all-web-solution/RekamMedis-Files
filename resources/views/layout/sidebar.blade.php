<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon">
            <i class="fas fa-hospital-user"></i>
        </div>
        <h3>Klinik Sehat</h3>
        <p>Medical Management System</p>
    </div>

    <div class="sidebar-menu">
        <div class="menu-title">Main Menu</div>

        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('patients') }}"
            class="menu-item {{ request()->routeIs('patients') || request()->routeIs('patients.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Data Pasien</span>
        </a>

        <a href="{{ route('visits') }}"
            class="menu-item {{ request()->routeIs('visits') || request()->routeIs('visits.*') ? 'active' : '' }}">
            <i class="fas fa-notes-medical"></i>
            <span>Data Kunjungan</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="menu-item menu-button logout-menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
