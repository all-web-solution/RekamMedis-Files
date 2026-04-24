@extends('layout.master')

@section('page-title', 'Data Kunjungan')
@section('page-description', 'Kelola data kunjungan pasien')

@section('content')
<div class="top-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 12px; flex-wrap: wrap;">
    <button type="button" class="btn-icon" onclick="openModal()">
        <i class="fas fa-plus"></i> Kunjungan Baru
    </button>
</div>

@if(session('success'))
<div class="alert-success">
    <i class="fas fa-check-circle fa-lg"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="alert-error">
    <i class="fas fa-exclamation-circle fa-lg"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="export-toolbar">
    <div class="export-toolbar-title">
        <div class="export-toolbar-icon">
            <i class="fas fa-file-pdf"></i>
        </div>
        <div>
            <h4>Export Data Kunjungan</h4>
            <p>Unduh laporan PDF seluruh data kunjungan atau data sesuai filter aktif.</p>
        </div>
    </div>

    <div class="export-toolbar-actions">
        <a href="{{ route('visits.export.pdf', ['all' => 1]) }}" class="btn-export-pdf">
            <i class="fas fa-file-pdf"></i> Export Semua PDF
        </a>

        <a href="{{ route('visits.export.pdf', request()->except('page')) }}" class="btn-export-filtered">
            <i class="fas fa-filter"></i> Export Hasil Filter
        </a>
    </div>
</div>

<div class="filter-section">
    <h4><i class="fas fa-filter"></i> Filter Kunjungan</h4>

    <form method="GET" action="{{ route('visits') }}">
        <div class="filter-grid">
            <div class="filter-group">
                <label><i class="fas fa-user"></i> Pasien</label>
                <select name="patient_id" class="filter-select">
                    <option value="">Semua Pasien</option>
                    @foreach($patients as $patientOption)
                        <option value="{{ $patientOption->id }}" @selected(request('patient_id') == $patientOption->id)>
                            {{ $patientOption->nama }} - {{ $patientOption->nik }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label><i class="fas fa-id-card"></i> Nama / NIK</label>
                <input
                    type="text"
                    name="patient_name"
                    value="{{ request('patient_name') }}"
                    class="filter-input"
                    placeholder="Cari nama atau NIK..."
                >
            </div>

            <div class="filter-group">
                <label><i class="fas fa-stethoscope"></i> Diagnosa</label>
                <input
                    type="text"
                    name="diagnosis"
                    value="{{ request('diagnosis') }}"
                    class="filter-input"
                    placeholder="Cari diagnosa..."
                >
            </div>

            <div class="filter-group">
                <label><i class="fas fa-calendar-alt"></i> Dari Tanggal</label>
                <input
                    type="date"
                    name="date_from"
                    value="{{ request('date_from') }}"
                    class="filter-input"
                >
            </div>

            <div class="filter-group">
                <label><i class="fas fa-calendar-alt"></i> Sampai Tanggal</label>
                <input
                    type="date"
                    name="date_to"
                    value="{{ request('date_to') }}"
                    class="filter-input"
                >
            </div>

            <div class="filter-buttons">
                <a href="{{ route('visits') }}" class="btn-secondary">
                    <i class="fas fa-undo-alt"></i> Reset
                </a>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </div>
    </form>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        <h3><i class="fas fa-list"></i> Daftar Kunjungan</h3>
        <span class="badge-count">
            <i class="fas fa-notes-medical"></i>
            Total: {{ method_exists($visits, 'total') ? $visits->total() : $visits->count() }} Kunjungan
        </span>
    </div>

    <div class="table-wrapper">
        <table class="table-modern">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> No</th>
                    <th><i class="fas fa-calendar"></i> Tanggal</th>
                    <th><i class="fas fa-user"></i> Pasien</th>
                    <th><i class="fas fa-stethoscope"></i> Keluhan</th>
                    <th><i class="fas fa-microscope"></i> Diagnostik</th>
                    <th><i class="fas fa-prescription"></i> Terapi</th>
                    <th><i class="fas fa-cogs"></i> Aksi</th>
                </tr>
            </thead>
            <tbody id="visitTable">
                @forelse($visits as $index => $visit)
                <tr id="row-{{ $visit->id }}">
                    <td>{{ method_exists($visits, 'firstItem') ? $visits->firstItem() + $index : $index + 1 }}</td>
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
                    <td>{{ Str::limit($visit->terapi ?? '-', 50) }}</td>
                    <td>
                        <button type="button" class="btn-icon" onclick="showVisit({{ $visit->id }})" style="background: var(--primary); padding: 8px 12px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:60px;">
                        <i class="fas fa-inbox" style="font-size: 48px; color: var(--gray-300); margin-bottom: 15px; display: block;"></i>
                        <span style="color: var(--gray-400);">Belum ada kunjungan</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($visits, 'links'))
        <div class="pagination-wrapper">
            {{ $visits->links() }}
        </div>
    @endif
</div>

<div id="modal" class="modal-glass">
    <div class="modal-content-glass" style="max-width: 700px;">
        <div class="modal-header-glass">
            <h3><i class="fas fa-stethoscope"></i> Tambah Kunjungan</h3>
            <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
        </div>

        <form action="{{ route('visits.store') }}" method="POST">
            @csrf

            <div class="modal-body-glass">
                <div class="form-grid">
                    <div class="input-group-custom">
                        <label><i class="fas fa-user"></i> Pasien *</label>
                        <select name="patient_id" required>
                            <option value="">Pilih Pasien</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" @selected(request('patient_id') == $p->id)>
                                    {{ $p->nama }} ({{ $p->nik }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group-custom">
                        <label><i class="fas fa-calendar"></i> Tanggal Berobat *</label>
                        <input type="date" name="tanggal_berobat" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="input-group-custom">
                        <label><i class="fas fa-stethoscope"></i> Keluhan</label>
                        <textarea name="keluhan" rows="2" placeholder="Keluhan pasien..."></textarea>
                    </div>

                    <div class="input-group-custom">
                        <label><i class="fas fa-notes-medical"></i> Anamesis</label>
                        <textarea name="anamesis" rows="2" placeholder="Anamesis..."></textarea>
                    </div>

                    <div class="input-group-custom">
                        <label><i class="fas fa-heartbeat"></i> Pemeriksaan Fisik</label>
                        <textarea name="pemeriksaan_fisik" rows="2" placeholder="Hasil pemeriksaan fisik..."></textarea>
                    </div>

                    <div class="input-group-custom">
                        <label><i class="fas fa-flask"></i> Pemeriksaan Lab</label>
                        <textarea name="pemeriksaan_lab" rows="2" placeholder="Hasil pemeriksaan lab..."></textarea>
                    </div>

                    <div class="input-group-custom">
                        <label><i class="fas fa-microscope"></i> Diagnostik</label>
                        <textarea name="diagnostik" rows="2" placeholder="Diagnostik..."></textarea>
                    </div>

                    <div class="input-group-custom">
                        <label><i class="fas fa-prescription"></i> Terapi</label>
                        <textarea name="terapi" rows="2" placeholder="Terapi..."></textarea>
                    </div>

                    <div class="input-group-custom" style="grid-column: span 2;">
                        <label><i class="fas fa-allergies"></i> Riwayat Alergi</label>
                        <textarea name="riwayat_alergi" rows="2" placeholder="Riwayat alergi..."></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer-glass">
                <button type="button" class="btn-secondary" onclick="closeModal()">
                    <i class="fas fa-times"></i> Batal
                </button>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="detailModal" class="modal-glass">
    <div class="modal-content-glass">
        <div class="modal-header-glass">
            <h3><i class="fas fa-file-medical"></i> Detail Kunjungan</h3>
            <button type="button" class="close-modal" onclick="closeDetailModal()">&times;</button>
        </div>

        <div class="modal-body-glass" id="detailContent"></div>

        <div class="modal-footer-glass">
            <button type="button" class="btn-secondary" onclick="closeDetailModal()">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const visitsBaseUrl = "{{ url('/visits') }}";

    function openModal() {
        document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function showVisit(id) {
        fetch(`${visitsBaseUrl}/${id}`, {
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
            const date = data.tanggal_berobat
                ? new Date(data.tanggal_berobat).toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                })
                : '-';

            document.getElementById('detailContent').innerHTML = `
                <div class="detail-item"><strong><i class="fas fa-calendar"></i> Tanggal:</strong> <span>${date}</span></div>
                <div class="detail-item"><strong><i class="fas fa-user"></i> Pasien:</strong> <span>${data.patient?.nama || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-id-card"></i> NIK:</strong> <span>${data.patient?.nik || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-stethoscope"></i> Keluhan:</strong> <span>${data.keluhan || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-notes-medical"></i> Anamesis:</strong> <span>${data.anamesis || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-heartbeat"></i> Pemeriksaan Fisik:</strong> <span>${data.pemeriksaan_fisik || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-flask"></i> Pemeriksaan Lab:</strong> <span>${data.pemeriksaan_lab || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-microscope"></i> Diagnostik:</strong> <span>${data.diagnostik || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-prescription"></i> Terapi:</strong> <span>${data.terapi || '-'}</span></div>
                <div class="detail-item"><strong><i class="fas fa-allergies"></i> Riwayat Alergi:</strong> <span>${data.riwayat_alergi || '-'}</span></div>
            `;

            document.getElementById('detailModal').style.display = 'flex';
        })
        .catch(error => {
            alert(error.message);
        });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }

    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('modal')) {
            closeModal();
        }

        if (event.target === document.getElementById('detailModal')) {
            closeDetailModal();
        }
    });

    @if(request()->boolean('open_modal'))
        document.addEventListener('DOMContentLoaded', function() {
            openModal();
        });
    @endif
</script>
@endpush
