@extends('layout.master')

@section('page-title', 'Dashboard')
@section('page-description', 'Overview dan statistik klinik')

@section('content')
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

    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <h3><i class="fas fa-users"></i> Total Pasien</h3>
                <div class="stat-number">{{ $totalPatients }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <h3><i class="fas fa-notes-medical"></i> Total Kunjungan</h3>
                <div class="stat-number">{{ $totalVisits }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <h3><i class="fas fa-calendar-week"></i> Kunjungan Bulan Ini</h3>
                <div class="stat-number">{{ $monthlyVisits }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-calendar-week"></i>
            </div>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h3><i class="fas fa-chart-line"></i> Statistik Kunjungan Per Bulan</h3>
            <span
                style="background: var(--primary-bg); color: var(--primary); padding: 5px 12px; border-radius: 20px; font-size: 12px;">
                <i class="fas fa-chart-simple"></i> 12 Bulan Terakhir
            </span>
        </div>
        <div class="chart-container">
            <canvas id="visitChart" height="100"></canvas>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h3><i class="fas fa-clock"></i> Kunjungan Terakhir</h3>
            <a href="{{ route('visits') }}" class="btn-icon">
                <i class="fas fa-plus"></i> Kunjungan Baru
            </a>
        </div>
        <div class="table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar"></i> Tanggal</th>
                        <th><i class="fas fa-user"></i> Pasien</th>
                        <th><i class="fas fa-stethoscope"></i> Keluhan</th>
                        <th><i class="fas fa-microscope"></i> Diagnostik</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentVisits as $visit)
                        <tr>
                            <td>
                                <i class="fas fa-calendar-day" style="color: var(--primary); margin-right: 8px;"></i>
                                {{ \Carbon\Carbon::parse($visit->tanggal_berobat)->format('d/m/Y') }}
                            </td>
                            <td>
                                <i class="fas fa-user-circle" style="color: var(--primary); margin-right: 8px;"></i>
                                <strong>{{ $visit->patient->nama ?? '-' }}</strong>
                            </td>
                            <td>{{ Str::limit($visit->keluhan ?? '-', 50) }}</td>
                            <td>{{ Str::limit($visit->diagnostik ?? '-', 50) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:60px;">
                                <i class="fas fa-inbox"
                                    style="font-size: 48px; color: var(--gray-300); margin-bottom: 15px; display: block;"></i>
                                <span style="color: var(--gray-400);">Belum ada kunjungan</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ctx = document.getElementById('visitChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: @json($visitsData),
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#059669',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 10
                        }
                    },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#059669',
                        bodyColor: '#374151',
                        borderColor: '#059669',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e7eb'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
