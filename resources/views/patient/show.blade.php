{{-- resources/views/patient-detail.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EmeraldMed | Detail Pasien - {{ $patient->nama }}</title>
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
        
        /* Sidebar Styles */
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
            text-decoration: none;
        }
        .menu-item:hover { background: rgba(255,255,255,0.1); transform: translateX(5px); }
        .menu-item.active { background: linear-gradient(135deg, #2d9c5a, #1b5e3a); color: white; }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 25px 35px;
        }
        
        /* Top Bar */
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
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1b5e3a, #2d9c5a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-back {
            background: linear-gradient(135deg, #64748b, #475569);
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        .btn-back:hover { transform: translateY(-2px); }
        
        /* Cards */
        .profile-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #2d9c5a, #1b5e3a);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
        }
        .profile-info h2 {
            font-size: 1.8rem;
            color: #1b5e3a;
            margin-bottom: 5px;
        }
        .profile-info .nik {
            color: #64748b;
            font-size: 0.9rem;
        }
        .stats-mini {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }
        .stat-mini-item {
            background: #f8fafc;
            padding: 10px 20px;
            border-radius: 12px;
            text-align: center;
        }
        .stat-mini-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d9c5a;
        }
        .stat-mini-label {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e8f5e9;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .info-icon {
            width: 40px;
            height: 40px;
            background: #e8f5e9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d9c5a;
            font-size: 18px;
        }
        .info-label {
            font-size: 0.7rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-weight: 600;
            color: #1e293b;
            margin-top: 2px;
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
        .card-header-modern h3 {
            color: #1b5e3a;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
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
        .table-modern tr:hover {
            background: #f8fafc;
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .diagnosis-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .diagnosis-tag {
            background: #e8f5e9;
            padding: 8px 16px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            min-width: 150px;
        }
        .diagnosis-name {
            font-weight: 500;
            color: #1b5e3a;
        }
        .diagnosis-count {
            background: #2d9c5a;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2d9c5a, #1b5e3a);
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
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
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #64748b;
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
        <a href="{{ route('dashboard') }}" class="menu-item">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('dashboard') }}#patientPage" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Data Pasien</span>
        </a>
        <a href="{{ route('dashboard') }}#visitPage" class="menu-item">
            <i class="fas fa-notes-medical"></i>
            <span>Kunjungan</span>
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-bar">
        <div style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ route('dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="page-title">Detail Pasien</h1>
        </div>
        <button class="btn-primary" onclick="openVisitModal()">
            <i class="fas fa-plus"></i> Tambah Kunjungan
        </button>
    </div>

    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="profile-info">
                <h2>{{ $patient->nama }}</h2>
                <div class="nik">NIK: {{ $patient->nik }}</div>
                <div class="stats-mini">
                    <div class="stat-mini-item">
                        <div class="stat-mini-number">{{ $totalVisits }}</div>
                        <div class="stat-mini-label">Total Kunjungan</div>
                    </div>
                    <div class="stat-mini-item">
                        <div class="stat-mini-number">{{ $firstVisit ? date('d/m/Y', strtotime($firstVisit->tanggal_berobat)) : '-' }}</div>
                        <div class="stat-mini-label">Kunjungan Pertama</div>
                    </div>
                    <div class="stat-mini-item">
                        <div class="stat-mini-number">{{ $lastVisit ? date('d/m/Y', strtotime($lastVisit->tanggal_berobat)) : '-' }}</div>
                        <div class="stat-mini-label">Kunjungan Terakhir</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="info-label">Umur</div>
                    <div class="info-value">{{ $patient->umur }} tahun</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-venus-mars"></i></div>
                <div>
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">{{ $patient->jenis_kelamin }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-ruler"></i></div>
                <div>
                    <div class="info-label">Tinggi Badan</div>
                    <div class="info-value">{{ $patient->tinggi ?? '-' }} cm</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-weight-scale"></i></div>
                <div>
                    <div class="info-label">Berat Badan</div>
                    <div class="info-value">{{ $patient->berat ?? '-' }} kg</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-id-card"></i></div>
                <div>
                    <div class="info-label">NIK</div>
                    <div class="info-value">{{ $patient->nik }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar-plus"></i></div>
                <div>
                    <div class="info-label">Terdaftar Sejak</div>
                    <div class="info-value">{{ $patient->created_at->format('d F Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Kunjungan per Tahun -->
    @if($visitsPerYear->count() > 0)
    <div class="card-modern">
        <div class="card-header-modern">
            <h3><i class="fas fa-chart-bar"></i> Statistik Kunjungan per Tahun</h3>
        </div>
        <canvas id="yearlyChart" height="150"></canvas>
    </div>
    @endif

    <!-- Diagnostik Terbanyak -->
    @if($commonDiagnosis->count() > 0 && $commonDiagnosis->first() > 0)
    <div class="card-modern">
        <div class="card-header-modern">
            <h3><i class="fas fa-chart-pie"></i> Diagnostik Terbanyak</h3>
        </div>
        <div class="diagnosis-list">
            @foreach($commonDiagnosis as $diagnosis => $count)
                @if($diagnosis)
                <div class="diagnosis-tag">
                    <span class="diagnosis-name">{{ $diagnosis }}</span>
                    <span class="diagnosis-count">{{ $count }}x</span>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Riwayat Kunjungan -->
    <div class="card-modern">
        <div class="card-header-modern">
            <h3><i class="fas fa-history"></i> Riwayat Kunjungan</h3>
        </div>
        <div class="table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Berobat</th>
                        <th>Keluhan</th>
                        <th>Diagnostik</th>
                        <th>Terapi</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $index => $visit)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($visit->tanggal_berobat)->format('d/m/Y') }}</strong>
                            <br>
                            <small class="badge badge-info">{{ \Carbon\Carbon::parse($visit->tanggal_berobat)->diffForHumans() }}</small>
                        </td>
                        <td>{{ $visit->keluhan ?? '-' }}</td>
                        <td>
                            {{ $visit->diagnostik ?? '-' }}
                            @if($visit->pemeriksaan_lab)
                                <br><small class="badge badge-info">Lab: {{ $visit->pemeriksaan_lab }}</small>
                            @endif
                        </td>
                        <td>{{ $visit->terapi ?? '-' }}</td>
                        <td>
                            <button class="btn-primary" style="padding: 6px 12px; font-size: 0.75rem;" onclick="showVisitDetail({{ $visit->id }})">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 60px;">
                            <i class="fas fa-notes-medical" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                            Belum ada riwayat kunjungan
                            <br>
                            <button class="btn-primary" style="margin-top: 15px;" onclick="openVisitModal()">
                                <i class="fas fa-plus"></i> Tambah Kunjungan Pertama
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kunjungan -->
<div id="visitModal" class="modal-glass">
    <div class="modal-content-glass">
        <div class="modal-header-glass">
            <h3><i class="fas fa-stethoscope"></i> Tambah Kunjungan Baru</h3>
            <button class="close-modal" onclick="closeVisitModal()">&times;</button>
        </div>
        <form action="{{ route('visits.store') }}" method="POST">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
            <div class="modal-body-glass">
                <div class="form-grid">
                    <div class="input-group-custom">
                        <label>Tanggal Berobat *</label>
                        <input type="date" name="tanggal_berobat" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="input-group-custom">
                        <label>Keluhan</label>
                        <input type="text" name="keluhan" placeholder="Keluhan pasien...">
                    </div>
                    <div class="input-group-custom">
                        <label>Anamesis</label>
                        <input type="text" name="anamesis" placeholder="Riwayat penyakit...">
                    </div>
                    <div class="input-group-custom">
                        <label>Pemeriksaan Fisik</label>
                        <input type="text" name="pemeriksaan_fisik" placeholder="Hasil pemeriksaan fisik...">
                    </div>
                    <div class="input-group-custom">
                        <label>Pemeriksaan Lab</label>
                        <input type="text" name="pemeriksaan_lab" placeholder="Hasil laboratorium...">
                    </div>
                    <div class="input-group-custom">
                        <label>Diagnostik</label>
                        <input type="text" name="diagnostik" placeholder="Diagnosis...">
                    </div>
                    <div class="input-group-custom">
                        <label>Terapi</label>
                        <input type="text" name="terapi" placeholder="Terapi/obat...">
                    </div>
                    <div class="input-group-custom">
                        <label>Riwayat Alergi</label>
                        <input type="text" name="riwayat_alergi" placeholder="Alergi pasien...">
                    </div>
                </div>
            </div>
            <div class="modal-footer-glass">
                <button type="button" class="btn-primary" style="background:#f1f5f9; color:#475569;" onclick="closeVisitModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan Kunjungan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Kunjungan -->
<div id="visitDetailModal" class="modal-glass">
    <div class="modal-content-glass">
        <div class="modal-header-glass">
            <h3><i class="fas fa-file-medical"></i> Detail Kunjungan</h3>
            <button class="close-modal" onclick="closeVisitDetailModal()">&times;</button>
        </div>
        <div class="modal-body-glass" id="visitDetailContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>

<script>
    // Chart untuk kunjungan per tahun
    @if($visitsPerYear->count() > 0)
    const yearlyLabels = @json($visitsPerYear->keys());
    const yearlyData = @json($visitsPerYear->values());
    
    new Chart(document.getElementById('yearlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: yearlyLabels,
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: yearlyData,
                backgroundColor: 'rgba(45, 156, 90, 0.3)',
                borderColor: '#2d9c5a',
                borderWidth: 2,
                borderRadius: 8
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
    @endif

    // Modal functions
    function openVisitModal() {
        document.getElementById('visitModal').style.display = 'flex';
    }
    
    function closeVisitModal() {
        document.getElementById('visitModal').style.display = 'none';
    }
    
    function showVisitDetail(visitId) {
        // Fetch visit detail via AJAX
        fetch(`/visits/${visitId}`)
            .then(response => response.json())
            .then(data => {
                const content = `
                    <div style="display: grid; gap: 20px;">
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
                document.getElementById('visitDetailContent').innerHTML = content;
                document.getElementById('visitDetailModal').style.display = 'flex';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail kunjungan');
            });
    }
    
    function closeVisitDetailModal() {
        document.getElementById('visitDetailModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(e) {
        if (e.target.classList.contains('modal-glass')) {
            e.target.style.display = 'none';
        }
    }
</script>
</body>
</html>