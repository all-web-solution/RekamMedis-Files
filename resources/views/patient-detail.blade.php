@extends('layout.master')
@section('content')
    

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

@endsection
