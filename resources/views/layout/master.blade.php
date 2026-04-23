{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EmeraldMed | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6d9 100%);
            min-height: 100vh;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100%;
            background: rgba(10, 47, 31, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
        }
        .sidebar-header {
            padding: 30px 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2d9c5a, #1b5e3a);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            color: white;
        }
        .sidebar-header h2 { color: white; font-size: 1.5rem; }
        .sidebar-header p { color: rgba(255,255,255,0.6); font-size: 0.75rem; }
        .sidebar-menu { padding: 25px 15px; }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 12px;
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }
        .menu-item:hover { background: rgba(255,255,255,0.1); transform: translateX(5px); }
        .menu-item.active { background: linear-gradient(135deg, #2d9c5a, #1b5e3a); color: white; }
        .main-content { margin-left: 280px; padding: 25px 35px; }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 15px 25px;
            border-radius: 20px;
        }
        .page-title {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1b5e3a, #2d9c5a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .date-badge {
            background: linear-gradient(135deg, #e8f5e9, #c8e6d9);
            padding: 8px 20px;
            border-radius: 12px;
            font-weight: 600;
            color: #1b5e3a;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-number { font-size: 2.5rem; font-weight: 800; color: #1b5e3a; }
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #e8f5e9, #c8e6d9);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #2d9c5a;
        }
        .card-modern {
            background: white;
            border-radius: 24px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .card-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8f5e9;
            flex-wrap: wrap;
            gap: 15px;
        }
        .card-header-modern h3 { color: #1b5e3a; display: flex; align-items: center; gap: 10px; }
        .btn-primary {
            background: linear-gradient(135deg, #2d9c5a, #1b5e3a);
            border: none;
            padding: 12px 28px;
            border-radius: 14px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-primary:hover { transform: translateY(-2px); }
        .btn-secondary {
            background: #f1f5f9;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-danger {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            color: white;
            font-size: 0.75rem;
            cursor: pointer;
        }
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            color: white;
            font-size: 0.75rem;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn-icon {
            background: linear-gradient(135deg, #2d9c5a, #1b5e3a);
            padding: 8px 16px;
            border-radius: 10px;
            color: white;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: transform 0.2s;
        }
        .btn-icon:hover { transform: translateY(-2px); }
        .table-wrapper { overflow-x: auto; }
        .table-modern {
            width: 100%;
            border-collapse: collapse;
        }
        .table-modern th {
            text-align: left;
            padding: 15px 12px;
            background: #f8fafc;
            color: #1b5e3a;
            font-weight: 600;
        }
        .table-modern td {
            padding: 15px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-modern tr:hover { background: #f8fafc; }
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .modal-glass {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        .modal-content-glass {
            background: white;
            border-radius: 32px;
            max-width: 650px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
        }
        .modal-header-glass {
            padding: 20px 25px;
            border-bottom: 2px solid #e8f5e9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-body-glass { padding: 25px; }
        .modal-footer-glass {
            padding: 20px 25px;
            border-top: 2px solid #e8f5e9;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .input-group-custom {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .input-group-custom label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #1b5e3a;
        }
        .input-group-custom input, .input-group-custom select {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }
        .input-group-custom input:focus, .input-group-custom select:focus {
            outline: none;
            border-color: #2d9c5a;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 15px 20px;
            border-radius: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px 20px;
            border-radius: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .hidden { display: none; }
        .badge-success {
            background: #dcfce7;
            color: #166534;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
        }
        .badge-info {
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #64748b;
        }
        
        /* Filter Section Styles */
        .filter-section {
            background: white;
            border-radius: 24px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .filter-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1b5e3a;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .filter-group label {
            font-weight: 600;
            font-size: 0.75rem;
            color: #64748b;
        }
        .filter-group input, .filter-group select {
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
        }
        .filter-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            align-items: flex-end;
        }
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
        .filter-tag {
            background: #e8f5e9;
            color: #1b5e3a;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .filter-tag i {
            cursor: pointer;
            font-size: 0.7rem;
        }
        .filter-tag i:hover {
            color: #dc2626;
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(45, 156, 90, 0.3);
            border-radius: 50%;
            border-top-color: #2d9c5a;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

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
        <div class="menu-item" data-page="visit">
            <i class="fas fa-notes-medical"></i>
            <span>Kunjungan</span>
        </div>
        <div class="menu-item" data-page="history">
            <i class="fas fa-archive"></i>
            <span>Riwayat</span>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="top-bar">
        <h1 class="page-title" id="pageTitle">Dashboard Overview</h1>
        <div class="date-badge"><i class="fas fa-calendar-alt"></i> <span id="currentDate"></span></div>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> 
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i> 
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div id="dashboardPage">
        <div class="stats-grid">
            <div class="stat-card"><div><h3>Total Pasien</h3><div class="stat-number">{{ $totalPatients }}</div></div><div class="stat-icon"><i class="fas fa-users"></i></div></div>
            <div class="stat-card"><div><h3>Total Kunjungan</h3><div class="stat-number">{{ $totalVisits }}</div></div><div class="stat-icon"><i class="fas fa-notes-medical"></i></div></div>
            <div class="stat-card"><div><h3>Bulan Ini</h3><div class="stat-number">{{ $monthlyVisits }}</div></div><div class="stat-icon"><i class="fas fa-calendar-week"></i></div></div>
        </div>
        <div class="card-modern">
            <div class="card-header-modern"><h3><i class="fas fa-chart-line"></i> Statistik Kunjungan Per Bulan</h3></div>
            <canvas id="visitChart" height="200"></canvas>
        </div>
        <div class="card-modern">
            <div class="card-header-modern"><h3><i class="fas fa-clock"></i> Kunjungan Terakhir</h3><button class="btn-icon" onclick="openVisitModal()"><i class="fas fa-plus"></i> Kunjungan Baru</button></div>
            <div class="table-wrapper"><table class="table-modern"><thead><tr><th>Tanggal</th><th>Pasien</th><th>Keluhan</th><th>Diagnostik</th></tr></thead><tbody>
                @forelse($recentVisits as $visit)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($visit->tanggal_berobat)->format('d/m/Y') }}</td>
                    <td><strong>{{ $visit->patient->nama ?? '-' }}</strong></td>
                    <td>{{ Str::limit($visit->keluhan ?? '-', 50) }}</td>
                    <td>{{ Str::limit($visit->diagnostik ?? '-', 50) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center; padding:40px;">✨ Belum ada kunjungan</td></tr>
                @endforelse
            </tbody></table></div>
        </div>
    </div>

    <div id="patientPage" class="hidden">
        <div class="card-modern">
            <div class="card-header-modern">
                <h3><i class="fas fa-user-plus"></i> Data Pasien</h3>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="searchPatient" class="filter-group" placeholder="Cari pasien..." style="padding: 10px 14px; width: 250px;">
                    <button class="btn-icon" onclick="openPatientModal()"><i class="fas fa-plus"></i> Pasien Baru</button>
                </div>
            </div>
            <div class="table-wrapper">
                <table class="table-modern" id="patientTable">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Jenis Kelamin</th>
                            <th>Tinggi</th>
                            <th>Berat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="patientTableBody">
                        @forelse($allPatients as $patient)
                        <tr id="patient-row-{{ $patient->id }}">
                            <td>{{ $patient->nik }}</td>
                            <td>{{ $patient->nama }}</td>
                            <td>{{ $patient->umur }} th</td>
                            <td>{{ $patient->jenis_kelamin }}</td>
                            <td>{{ $patient->tinggi ?? '-' }} cm</td>
                            <td>{{ $patient->berat ?? '-' }} kg</td>
                            <td class="action-buttons">
                                <a href="{{ route('patients.show', $patient->id) }}" class="btn-icon" style="padding: 6px 12px; text-decoration: none; background: #3b82f6;">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <button class="btn-warning" onclick="editPatient({{ $patient->id }})" style="padding: 6px 12px;">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-danger" onclick="deletePatient({{ $patient->id }}, '{{ $patient->nama }}')" style="padding: 6px 12px;">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:40px;">Belum ada data pasien</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="visitPage" class="hidden">
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filter Data Kunjungan
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label><i class="fas fa-user"></i> Nama Pasien</label>
                    <input type="text" id="filterPatientName" class="filter-input" placeholder="Cari nama pasien...">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-stethoscope"></i> Diagnosa</label>
                    <select id="filterDiagnosis" class="filter-input">
                        <option value="">Semua Diagnosa</option>
                        @foreach($uniqueDiagnoses ?? [] as $diagnosis)
                            <option value="{{ $diagnosis }}">{{ $diagnosis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar-alt"></i> Dari Tanggal</label>
                    <input type="date" id="filterDateFrom" class="filter-input">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar-alt"></i> Sampai Tanggal</label>
                    <input type="date" id="filterDateTo" class="filter-input">
                </div>
                <div class="filter-buttons">
                    <button class="btn-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo-alt"></i> Reset
                    </button>
                    <button class="btn-primary" onclick="applyFilters()">
                        <i class="fas fa-search"></i> Terapkan Filter
                    </button>
                </div>
            </div>
            <div id="activeFilters" class="active-filters"></div>
        </div>

        <div class="card-modern">
            <div class="card-header-modern">
                <h3><i class="fas fa-history"></i> Semua Kunjungan</h3>
                <button class="btn-icon" onclick="openVisitModal()"><i class="fas fa-plus"></i> Kunjungan Baru</button>
            </div>
            <div class="table-wrapper">
                <table class="table-modern" id="visitsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pasien</th>
                            <th>Keluhan</th>
                            <th>Diagnostik</th>
                            <th>Terapi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="visitsTableBody">
                        @include('partials.visit-table-body', ['visits' => $allVisits])
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="historyPage" class="hidden">
        <div class="card-modern">
            <div class="card-header-modern"><h3><i class="fas fa-archive"></i> Arsip Kunjungan</h3></div>
            <div class="table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr><th>Tanggal</th><th>Pasien</th><th>Diagnostik</th><th>Terapi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($oldVisits ?? [] as $visit)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($visit->tanggal_berobat)->format('d/m/Y') }}</td>
                            <td>{{ $visit->patient->nama ?? '-' }}</td>
                            <td>{{ Str::limit($visit->diagnostik ?? '-', 50) }}</td>
                            <td>{{ Str::limit($visit->terapi ?? '-', 50) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center; padding:40px;">Belum ada arsip</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pasien -->
<div id="patientModal" class="modal-glass">
    <div class="modal-content-glass">
        <div class="modal-header-glass">
            <h3 id="patientModalTitle"><i class="fas fa-user-md"></i> Tambah Pasien Baru</h3>
            <button class="close-modal" onclick="closePatientModal()">&times;</button>
        </div>
        <form id="patientForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="patient_id" id="patientId">
            <div class="modal-body-glass">
                <div class="form-grid">
                    <div class="input-group-custom">
                        <label>NIK *</label>
                        <input type="text" name="nik" id="nik" required maxlength="20">
                    </div>
                    <div class="input-group-custom">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="nama" id="nama" required>
                    </div>
                    <div class="input-group-custom">
                        <label>Umur *</label>
                        <input type="number" name="umur" id="umur" required min="0" max="150">
                    </div>
                    <div class="input-group-custom">
                        <label>Jenis Kelamin *</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="input-group-custom">
                        <label>Tinggi Badan (cm)</label>
                        <input type="number" step="0.01" name="tinggi" id="tinggi" min="0" max="300">
                    </div>
                    <div class="input-group-custom">
                        <label>Berat Badan (kg)</label>
                        <input type="number" step="0.01" name="berat" id="berat" min="0" max="500">
                    </div>
                </div>
            </div>
            <div class="modal-footer-glass">
                <button type="button" class="btn-secondary" onclick="closePatientModal()">Batal</button>
                <button type="submit" class="btn-primary" id="submitPatientBtn">Simpan Pasien</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Kunjungan -->
<div id="visitModal" class="modal-glass">
    <div class="modal-content-glass">
        <div class="modal-header-glass"><h3><i class="fas fa-stethoscope"></i> Tambah Kunjungan</h3><button class="close-modal" onclick="closeVisitModal()">&times;</button></div>
        <form action="{{ route('visits.store') }}" method="POST">
            @csrf
            <div class="modal-body-glass">
                <div class="form-grid">
                    <div class="input-group-custom"><label>Pilih Pasien *</label><select name="patient_id" required><option value="">-- Pilih --</option>@foreach($allPatients as $p)<option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nik }})</option>@endforeach</select></div>
                    <div class="input-group-custom"><label>Tanggal Berobat *</label><input type="date" name="tanggal_berobat" value="{{ date('Y-m-d') }}" required></div>
                    <div class="input-group-custom"><label>Keluhan</label><input type="text" name="keluhan"></div>
                    <div class="input-group-custom"><label>Anamesis</label><input type="text" name="anamesis"></div>
                    <div class="input-group-custom"><label>Pemeriksaan Fisik</label><input type="text" name="pemeriksaan_fisik"></div>
                    <div class="input-group-custom"><label>Pemeriksaan Lab</label><input type="text" name="pemeriksaan_lab"></div>
                    <div class="input-group-custom"><label>Diagnostik</label><input type="text" name="diagnostik"></div>
                    <div class="input-group-custom"><label>Terapi</label><input type="text" name="terapi"></div>
                    <div class="input-group-custom"><label>Riwayat Alergi</label><input type="text" name="riwayat_alergi"></div>
                </div>
            </div>
            <div class="modal-footer-glass">
                <button type="button" class="btn-secondary" onclick="closeVisitModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan Kunjungan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Data untuk chart
    const chartLabels = @json($chartData['months']);
    const chartValues = @json($chartData['visits']);
    
    new Chart(document.getElementById('visitChart').getContext('2d'), {
        type: 'line',
        data: { 
            labels: chartLabels, 
            datasets: [{ 
                label: 'Kunjungan', 
                data: chartValues, 
                borderColor: '#2d9c5a', 
                backgroundColor: 'rgba(45,156,90,0.1)', 
                borderWidth: 3, 
                tension: 0.4, 
                fill: true 
            }] 
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: true,
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1 } 
                } 
            } 
        }
    });
    
    // Set tanggal
    document.getElementById('currentDate').innerText = new Date().toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    
    // Menu navigation
    const menuItems = document.querySelectorAll('.menu-item');
    const pages = {
        dashboard: document.getElementById('dashboardPage'),
        patient: document.getElementById('patientPage'),
        visit: document.getElementById('visitPage'),
        history: document.getElementById('historyPage')
    };
    
    menuItems.forEach(item => { 
        item.addEventListener('click', () => { 
            const page = item.dataset.page; 
            menuItems.forEach(m => m.classList.remove('active')); 
            item.classList.add('active'); 
            Object.keys(pages).forEach(k => pages[k].classList.add('hidden')); 
            pages[page].classList.remove('hidden'); 
            const titles = {
                dashboard: 'Dashboard Overview',
                patient: 'Data Pasien',
                visit: 'Data Kunjungan',
                history: 'Riwayat Kunjungan'
            };
            document.getElementById('pageTitle').innerText = titles[page];
            
            if (page === 'visit') {
                updateActiveFiltersDisplay();
            }
        }); 
    });
    
    // Patient CRUD Functions
    function openPatientModal(patientId = null) {
        const modal = document.getElementById('patientModal');
        const form = document.getElementById('patientForm');
        const title = document.getElementById('patientModalTitle');
        const submitBtn = document.getElementById('submitPatientBtn');
        
        if (patientId) {
            // Edit mode
            title.innerHTML = '<i class="fas fa-edit"></i> Edit Pasien';
            document.getElementById('formMethod').value = 'PUT';
            form.action = `/patients/${patientId}`;
            document.getElementById('patientId').value = patientId;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Pasien';
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Loading...';
            
            // Fetch patient data
            fetch(`/patients/${patientId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nik').value = data.nik;
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('umur').value = data.umur;
                    document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
                    document.getElementById('tinggi').value = data.tinggi || '';
                    document.getElementById('berat').value = data.berat || '';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Pasien';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data pasien');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Pasien';
                });
        } else {
            // Create mode
            title.innerHTML = '<i class="fas fa-user-md"></i> Tambah Pasien Baru';
            document.getElementById('formMethod').value = 'POST';
            form.action = "{{ route('patients.store') }}";
            document.getElementById('patientId').value = '';
            document.getElementById('patientForm').reset();
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Pasien';
        }
        
        modal.style.display = 'flex';
    }
    
    function editPatient(id) {
        openPatientModal(id);
    }
    
    function deletePatient(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus pasien "${name}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/patients/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function closePatientModal() {
        document.getElementById('patientModal').style.display = 'none';
        document.getElementById('patientForm').reset();
    }
    
    // Handle patient form submission with AJAX
    document.getElementById('patientForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const method = document.getElementById('formMethod').value;
        const patientId = document.getElementById('patientId').value;
        const submitBtn = document.getElementById('submitPatientBtn');
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Menyimpan...';
        
        let url = form.action;
        let requestMethod = 'POST';
        
        if (method === 'PUT') {
            requestMethod = 'PUT';
            // For PUT request, we need to send the data properly
            formData.append('_method', 'PUT');
        }
        
        fetch(url, {
            method: requestMethod,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification('success', data.message);
                closePatientModal();
                
                // Reload the page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                // Show error message
                showNotification('error', data.message || 'Terjadi kesalahan');
                submitBtn.disabled = false;
                submitBtn.innerHTML = method === 'PUT' ? '<i class="fas fa-save"></i> Update Pasien' : '<i class="fas fa-save"></i> Simpan Pasien';
                
                // Display validation errors
                if (data.errors) {
                    let errorMsg = 'Validasi gagal:\n';
                    for (let key in data.errors) {
                        errorMsg += `- ${data.errors[key].join(', ')}\n`;
                    }
                    alert(errorMsg);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Gagal menyimpan data');
            submitBtn.disabled = false;
            submitBtn.innerHTML = method === 'PUT' ? '<i class="fas fa-save"></i> Update Pasien' : '<i class="fas fa-save"></i> Simpan Pasien';
        });
    });
    
    function showNotification(type, message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = type === 'success' ? 'alert-success' : 'alert-error';
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '400px';
        notification.style.boxShadow = '0 5px 20px rgba(0,0,0,0.1)';
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Search patient functionality
    document.getElementById('searchPatient')?.addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#patientTableBody tr');
        
        rows.forEach(row => {
            if (row.cells) {
                const nik = row.cells[0]?.innerText.toLowerCase() || '';
                const nama = row.cells[1]?.innerText.toLowerCase() || '';
                if (nik.includes(searchText) || nama.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
    
    // Filter functions for visits
    let currentFilters = {
        patient_name: '',
        diagnosis: '',
        date_from: '',
        date_to: ''
    };
    
    function applyFilters() {
        currentFilters = {
            patient_name: document.getElementById('filterPatientName').value,
            diagnosis: document.getElementById('filterDiagnosis').value,
            date_from: document.getElementById('filterDateFrom').value,
            date_to: document.getElementById('filterDateTo').value
        };
        
        const tbody = document.getElementById('visitsTableBody');
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:40px;"><span class="loading-spinner"></span> Memuat data...</td></tr>';
        
        fetchFilteredVisits();
        updateActiveFiltersDisplay();
    }
    
    function resetFilters() {
        document.getElementById('filterPatientName').value = '';
        document.getElementById('filterDiagnosis').value = '';
        document.getElementById('filterDateFrom').value = '';
        document.getElementById('filterDateTo').value = '';
        
        currentFilters = {
            patient_name: '',
            diagnosis: '',
            date_from: '',
            date_to: ''
        };
        
        fetchFilteredVisits();
        updateActiveFiltersDisplay();
    }
    
    function fetchFilteredVisits() {
        const params = new URLSearchParams(currentFilters);
        
        fetch(`/api/visits/filter?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                renderVisitsTable(data.visits);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('visitsTableBody').innerHTML = '<tr><td colspan="7" style="text-align:center; padding:40px; color:#dc2626;"><i class="fas fa-exclamation-circle"></i> Gagal memuat数据</td></tr>';
            });
    }
    
    function renderVisitsTable(visits) {
        const tbody = document.getElementById('visitsTableBody');
        
        if (!visits || visits.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:40px;"><i class="fas fa-inbox"></i> Tidak ada data kunjungan</td></tr>';
            return;
        }
        
        let html = '';
        visits.forEach((visit, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <strong>${new Date(visit.tanggal_berobat).toLocaleDateString('id-ID')}</strong>
                        <br>
                        <small class="badge-info" style="padding: 2px 8px;">${getRelativeTime(visit.tanggal_berobat)}</small>
                    </td>
                    <td>
                        <strong>${visit.patient?.nama || '-'}</strong>
                        <br>
                        <small>NIK: ${visit.patient?.nik || '-'}</small>
                    </td>
                    <td>${visit.keluhan || '-'}</td>
                    <td>${visit.diagnostik ? `<span class="badge-success" style="padding: 4px 12px;">${visit.diagnostik}</span>` : '-'}</td>
                    <td>${visit.terapi || '-'}</td>
                    <td>
                        <button class="btn-icon" style="padding: 6px 12px; font-size: 0.75rem;" onclick="showVisitDetail(${visit.id})">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
    }
    
    function getRelativeTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) return 'Hari ini';
        if (diffDays === 1) return 'Kemarin';
        if (diffDays < 7) return `${diffDays} hari lalu`;
        if (diffDays < 30) return `${Math.floor(diffDays / 7)} minggu lalu`;
        if (diffDays < 365) return `${Math.floor(diffDays / 30)} bulan lalu`;
        return `${Math.floor(diffDays / 365)} tahun lalu`;
    }
    
    function updateActiveFiltersDisplay() {
        const container = document.getElementById('activeFilters');
        const activeFiltersList = [];
        
        if (currentFilters.patient_name) {
            activeFiltersList.push({ key: 'patient_name', label: 'Nama Pasien', value: currentFilters.patient_name });
        }
        if (currentFilters.diagnosis) {
            activeFiltersList.push({ key: 'diagnosis', label: 'Diagnosa', value: currentFilters.diagnosis });
        }
        if (currentFilters.date_from) {
            activeFiltersList.push({ key: 'date_from', label: 'Dari Tgl', value: formatDate(currentFilters.date_from) });
        }
        if (currentFilters.date_to) {
            activeFiltersList.push({ key: 'date_to', label: 'Sampai Tgl', value: formatDate(currentFilters.date_to) });
        }
        
        if (activeFiltersList.length === 0) {
            container.innerHTML = '';
            return;
        }
        
        let html = '<span style="font-size: 0.75rem; color: #64748b;">Filter aktif:</span>';
        activeFiltersList.forEach(filter => {
            html += `
                <div class="filter-tag">
                    <i class="fas fa-tag"></i>
                    ${filter.label}: ${filter.value}
                    <i class="fas fa-times" onclick="removeFilter('${filter.key}')"></i>
                </div>
            `;
        });
        html += '<span style="font-size: 0.75rem; color: #64748b; margin-left: auto;">Total: ' + document.querySelectorAll('#visitsTableBody tr').length + ' data</span>';
        
        container.innerHTML = html;
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID');
    }
    
    function removeFilter(key) {
        switch(key) {
            case 'patient_name':
                document.getElementById('filterPatientName').value = '';
                currentFilters.patient_name = '';
                break;
            case 'diagnosis':
                document.getElementById('filterDiagnosis').value = '';
                currentFilters.diagnosis = '';
                break;
            case 'date_from':
                document.getElementById('filterDateFrom').value = '';
                currentFilters.date_from = '';
                break;
            case 'date_to':
                document.getElementById('filterDateTo').value = '';
                currentFilters.date_to = '';
                break;
        }
        fetchFilteredVisits();
        updateActiveFiltersDisplay();
    }
    
    function showVisitDetail(visitId) {
        fetch(`/visits/${visitId}`)
            .then(response => response.json())
            .then(data => {
                const content = `
                    <div style="display: grid; gap: 20px;">
                        <div style="background: #f8fafc; padding: 15px; border-radius: 16px;">
                            <h4 style="color: #1b5e3a; margin-bottom: 10px;"><i class="fas fa-user"></i> Informasi Pasien</h4>
                            <p><strong>Nama:</strong> ${data.patient?.nama || '-'}</p>
                            <p><strong>NIK:</strong> ${data.patient?.nik || '-'}</p>
                        </div>
                        <div style="background: #f8fafc; padding: 15px; border-radius: 16px;">
                            <h4 style="color: #1b5e3a; margin-bottom: 10px;"><i class="fas fa-calendar"></i> Informasi Kunjungan</h4>
                            <p><strong>Tanggal Berobat:</strong> ${new Date(data.tanggal_berobat).toLocaleDateString('id-ID')}</p>
                            <p><strong>Keluhan:</strong> ${data.keluhan || '-'}</p>
                        </div>
                        <div style="background: #f8fafc; padding: 15px; border-radius: 16px;">
                            <h4 style="color: #1b5e3a; margin-bottom: 10px;"><i class="fas fa-stethoscope"></i> Pemeriksaan</h4>
                            <p><strong>Anamesis:</strong> ${data.anamesis || '-'}</p>
                            <p><strong>Pemeriksaan Fisik:</strong> ${data.pemeriksaan_fisik || '-'}</p>
                            <p><strong>Pemeriksaan Lab:</strong> ${data.pemeriksaan_lab || '-'}</p>
                        </div>
                        <div style="background: #f8fafc; padding: 15px; border-radius: 16px;">
                            <h4 style="color: #1b5e3a; margin-bottom: 10px;"><i class="fas fa-diagnoses"></i> Diagnosis & Terapi</h4>
                            <p><strong>Diagnostik:</strong> ${data.diagnostik || '-'}</p>
                            <p><strong>Terapi:</strong> ${data.terapi || '-'}</p>
                            <p><strong>Riwayat Alergi:</strong> ${data.riwayat_alergi || '-'}</p>
                        </div>
                    </div>
                `;
                
                let detailModal = document.getElementById('visitDetailModal');
                if (!detailModal) {
                    detailModal = document.createElement('div');
                    detailModal.id = 'visitDetailModal';
                    detailModal.className = 'modal-glass';
                    detailModal.innerHTML = `
                        <div class="modal-content-glass">
                            <div class="modal-header-glass">
                                <h3><i class="fas fa-file-medical"></i> Detail Kunjungan</h3>
                                <button class="close-modal" onclick="closeDetailModal()">&times;</button>
                            </div>
                            <div class="modal-body-glass" id="visitDetailContent"></div>
                        </div>
                    `;
                    document.body.appendChild(detailModal);
                }
                
                document.getElementById('visitDetailContent').innerHTML = content;
                detailModal.style.display = 'flex';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail kunjungan');
            });
    }
    
    function closeDetailModal() {
        const modal = document.getElementById('visitDetailModal');
        if (modal) modal.style.display = 'none';
    }
    
    // Enter key support for filter
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    });
    
    // Modal functions
    function openVisitModal() { document.getElementById('visitModal').style.display = 'flex'; }
    function closeVisitModal() { document.getElementById('visitModal').style.display = 'none'; }
    
    // Close modal when clicking outside
    window.onclick = function(e) { 
        if(e.target.classList.contains('modal-glass')) 
            e.target.style.display = 'none'; 
    }
</script>
</body>
</html>