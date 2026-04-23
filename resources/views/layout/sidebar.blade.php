<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon">
            <i class="fas fa-hospital-user"></i>
        </div>
        <h2>EmeraldMed</h2>
        <p>Medical Records System</p>
    </div>
    <div class="sidebar-menu">
        <div class="menu-item active" data-page="dashboard">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </div>
        <div class="menu-item" data-page="patient">
            <i class="fas fa-users"></i>
            <span>Data Pasien</span>
        </div>
       <a href="{{ route('visits.index') }}" class="menu-item" style="text-decoration: none;">
        <i class="fas fa-notes-medical"></i>
        <span>Kunjungan</span>
    </a>
        <div class="menu-item" data-page="history">
            <i class="fas fa-archive"></i>
            <span>Riwayat</span>
        </div>
    </div>
</div>