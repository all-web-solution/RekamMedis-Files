@extends('layout.master')

@section('page-title', 'Detail Pasien')
@section('page-description', 'Informasi lengkap data diri dan riwayat kunjungan pasien')

@section('content')
    <style>
        .profile-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            transform: rotate(45deg);
            pointer-events: none;
            z-index: 0;
        }

        .profile-avatar,
        .profile-name,
        .profile-nik,
        .profile-action-group {
            position: relative;
            z-index: 2;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .profile-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .profile-nik {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .info-card {
            background: var(--white);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .info-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-bg);
        }

        .info-title i {
            color: var(--primary);
            margin-right: 10px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: var(--gray-50);
            border-radius: 16px;
            margin-bottom: 12px;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            background: var(--primary-bg);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
        }

        .info-label {
            font-size: 12px;
            color: var(--gray-500);
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .stats-mini {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 24px;
        }

        .stat-mini-card {
            background: var(--white);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-mini-number {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
        }

        .stat-mini-label {
            font-size: 12px;
            color: var(--gray-500);
            margin-top: 5px;
        }

        .diagnosis-tag {
            display: inline-block;
            background: var(--primary-bg);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 12px;
            margin: 5px;
            font-size: 13px;
            font-weight: 500;
        }

        .visit-card {
            background: var(--white);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .visit-card:hover {
            border-color: var(--primary-light);
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.1);
        }

        .visit-date {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 12px;
        }

        .visit-diagnosis {
            background: var(--primary-bg);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }

        .btn-back {
            background: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--gray-50);
            border-color: var(--primary);
            color: var(--primary);
        }
    </style>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('patients') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pasien
        </a>
    </div>

    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user-circle"></i>
        </div>

        <div class="profile-name">{{ $patient->nama }}</div>

        <div class="profile-nik">
            <i class="fas fa-id-card"></i> NIK: {{ $patient->nik ?: '-' }}
        </div>

        <div class="profile-action-group">
            <a href="{{ route('visits', ['patient_id' => $patient->id, 'open_modal' => 1]) }}"
                class="btn-profile-action btn-profile-primary">
                <i class="fas fa-plus"></i> Tambah Kunjungan
            </a>

            <a href="{{ route('patients.visits.export', $patient) }}" class="btn-profile-action btn-profile-export">
                <i class="fas fa-file-export"></i> Export Riwayat
            </a>

            <form method="POST" action="{{ route('patients.destroy', $patient->id) }}" class="delete-inline-form"
                data-confirm-delete="true" data-title="Hapus pasien?"
                data-text="Pasien {{ $patient->nama }} dan seluruh riwayat kunjungannya akan dihapus permanen."
                data-confirm-text="Ya, hapus pasien">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn-profile-action btn-danger-native">
                    <i class="fas fa-trash"></i> Hapus Pasien
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="stats-mini">
        <div class="stat-mini-card">
            <div class="stat-mini-number">{{ $totalVisits }}</div>
            <div class="stat-mini-label">Total Kunjungan</div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-number">{{ $visits->where('tanggal_berobat', '>=', now()->subMonths(12))->count() }}
            </div>
            <div class="stat-mini-label">Kunjungan 1 Tahun</div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-number">{{ $visits->where('tanggal_berobat', '>=', now()->subMonths(1))->count() }}</div>
            <div class="stat-mini-label">Kunjungan 1 Bulan</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="info-card">
                <div class="info-title">
                    <i class="fas fa-user-circle"></i> Data Diri
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-id-card"></i></div>
                    <div>
                        <div class="info-label">NIK</div>
                        <div class="info-value">{{ $patient->nik ?: '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-user"></i></div>
                    <div>
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $patient->nama }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-birthday-cake"></i></div>
                    <div>
                        <div class="info-label">Umur</div>
                        <div class="info-value">{{ $patient->umur }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-venus-mars"></i></div>
                    <div>
                        <div class="info-label">Jenis Kelamin</div>
                        <div class="info-value">
                            @if ($patient->jenis_kelamin == 'Laki-laki')
                                <i class="fas fa-mars"></i> Laki-laki
                            @else
                                <i class="fas fa-venus"></i> Perempuan
                            @endif
                        </div>
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
                    <div class="info-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <div class="info-label">Tanggal Registrasi</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($patient->created_at)->format('d F Y') }}</div>
                    </div>
                </div>
            </div>

            @if ($commonDiagnosis->count() > 0)
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-chart-pie"></i> Diagnosa Paling Sering
                    </div>
                    <div style="padding: 10px 0;">
                        @foreach ($commonDiagnosis as $diagnosis => $count)
                            <div class="diagnosis-tag">
                                {{ $diagnosis ?: 'Tidak terdiagnosa' }} ({{ $count }}x)
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($visitsPerYear->count() > 0)
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-chart-line"></i> Statistik per Tahun
                    </div>

                    @foreach ($visitsPerYear as $year => $count)
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span style="font-size: 13px; color: var(--gray-600);">Tahun {{ $year }}</span>
                                <span style="font-weight: 600; color: var(--primary);">{{ $count }} kunjungan</span>
                            </div>
                            <div style="background: var(--gray-200); height: 8px; border-radius: 10px; overflow: hidden;">
                                <div
                                    style="background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%); width: {{ min(100, ($count / max($visitsPerYear->max(), 1)) * 100) }}%; height: 100%; border-radius: 10px;">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="info-card">
                <div class="info-title">
                    <i class="fas fa-notes-medical"></i> Riwayat Kunjungan
                    <span style="float: right; font-size: 13px; font-weight: normal;">
                        Total: {{ $totalVisits }} kunjungan
                    </span>
                </div>

                @if ($visits->count() > 0)
                    @foreach ($visits as $visit)
                        <div class="visit-card">
                            <div
                                style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                                <div>
                                    <span class="visit-date">
                                        <i class="fas fa-calendar-day"></i>
                                        {{ \Carbon\Carbon::parse($visit->tanggal_berobat)->format('d F Y') }}
                                    </span>

                                    @if ($visit->diagnostik)
                                        <span class="visit-diagnosis" style="margin-left: 8px;">
                                            <i class="fas fa-microscope"></i> {{ $visit->diagnostik }}
                                        </span>
                                    @endif
                                </div>

                                <button type="button" class="btn-icon" onclick="showVisitDetail({{ $visit->id }})"
                                    style="padding: 6px 12px; background: var(--primary);">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div style="margin-bottom: 12px;">
                                        <div style="font-size: 11px; color: var(--gray-500); margin-bottom: 4px;">
                                            <i class="fas fa-stethoscope"></i> Keluhan
                                        </div>
                                        <div style="font-size: 14px; color: var(--gray-700);">
                                            {{ $visit->keluhan ?: '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div style="margin-bottom: 12px;">
                                        <div style="font-size: 11px; color: var(--gray-500); margin-bottom: 4px;">
                                            <i class="fas fa-prescription"></i> Terapi
                                        </div>
                                        <div style="font-size: 14px; color: var(--gray-700);">
                                            {{ $visit->terapi ? Str::limit($visit->terapi, 100) : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($visit->pemeriksaan_fisik || $visit->pemeriksaan_lab)
                                <div
                                    style="background: var(--gray-50); padding: 12px; border-radius: 12px; margin-top: 10px;">
                                    <div style="font-size: 11px; color: var(--gray-500); margin-bottom: 8px;">
                                        <i class="fas fa-heartbeat"></i> Pemeriksaan
                                    </div>

                                    <div class="row">
                                        @if ($visit->pemeriksaan_fisik)
                                            <div class="col-md-6">
                                                <div style="font-size: 12px;"><strong>Fisik:</strong>
                                                    {{ $visit->pemeriksaan_fisik }}</div>
                                            </div>
                                        @endif

                                        @if ($visit->pemeriksaan_lab)
                                            <div class="col-md-6">
                                                <div style="font-size: 12px;"><strong>Lab:</strong>
                                                    {{ $visit->pemeriksaan_lab }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 60px;">
                        <i class="fas fa-inbox"
                            style="font-size: 48px; color: var(--gray-300); margin-bottom: 15px; display: block;"></i>
                        <p style="color: var(--gray-400);">Belum ada riwayat kunjungan</p>
                        <a href="{{ route('visits', ['patient_id' => $patient->id, 'open_modal' => 1]) }}"
                            class="btn-primary" style="margin-top: 15px;">
                            <i class="fas fa-plus"></i> Tambah Kunjungan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="visitDetailModal" class="modal-glass">
        <div class="modal-content-glass" style="max-width: 600px;">
            <div class="modal-header-glass">
                <h3><i class="fas fa-file-medical"></i> Detail Kunjungan</h3>
                <button type="button" class="close-modal" onclick="closeVisitDetailModal()">&times;</button>
            </div>

            <div class="modal-body-glass" id="visitDetailContent"></div>

            <div class="modal-footer-glass">
                <button type="button" class="btn-secondary" onclick="closeVisitDetailModal()">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const visitBaseUrl = "{{ url('/visits') }}";

        function showVisitDetail(id) {
            fetch(`${visitBaseUrl}/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil detail kunjungan.');
                    }

                    return response.json();
                })
                .then(data => {
                    const date = data.tanggal_berobat ?
                        new Date(data.tanggal_berobat).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        }) :
                        '-';

                    document.getElementById('visitDetailContent').innerHTML = `
                <div class="detail-item"><strong><i class="fas fa-calendar"></i> Tanggal:</strong> <span>${date}</span></div>
                <div class="detail-item"><strong><i class="fas fa-user"></i> Pasien:</strong> <span>${data.patient?.nama || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-stethoscope"></i> Keluhan:</strong> <span>${data.keluhan || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-notes-medical"></i> Anamesis:</strong> <span>${data.anamesis || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-heartbeat"></i> Pemeriksaan Fisik:</strong> <span>${data.pemeriksaan_fisik || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-flask"></i> Pemeriksaan Lab:</strong> <span>${data.pemeriksaan_lab || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-microscope"></i> Diagnostik:</strong> <span>${data.diagnostik || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-prescription"></i> Terapi:</strong> <span>${data.terapi || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-allergies"></i> Riwayat Alergi:</strong> <span>${data.riwayat_alergi || '-'}</span></div>
            `;

                    document.getElementById('visitDetailModal').style.display = 'flex';
                })
                .catch(error => {
                    alert(error.message);
                });
        }

        function closeVisitDetailModal() {
            document.getElementById('visitDetailModal').style.display = 'none';
        }

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('visitDetailModal')) {
                closeVisitDetailModal();
            }
        });
    </script>
@endpush
