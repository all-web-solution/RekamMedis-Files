@extends('layout.master')

@section('page-title', 'Data Kunjungan')
@section('page-description', 'Kelola data kunjungan pasien')

@section('content')
<div class="top-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <button class="btn-icon" onclick="openModal()">
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

<div class="filter-section">
    <h4><i class="fas fa-filter"></i> Filter Kunjungan</h4>
    <div class="filter-grid">
        <div class="filter-group">
            <label><i class="fas fa-user"></i> Nama Pasien</label>
            <input type="text" id="filterPatient" class="filter-input" placeholder="Cari nama...">
        </div>
        <div class="filter-group">
            <label><i class="fas fa-stethoscope"></i> Diagnosa</label>
            <input type="text" id="filterDiagnosis" class="filter-input" placeholder="Cari diagnosa...">
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> Dari Tanggal</label>
            <input type="date" id="filterFrom" class="filter-input">
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> Sampai Tanggal</label>
            <input type="date" id="filterTo" class="filter-input">
        </div>
        <div class="filter-buttons">
            <button class="btn-secondary" onclick="resetFilter()">
                <i class="fas fa-undo-alt"></i> Reset
            </button>
            <button class="btn-primary" onclick="applyFilter()">
                <i class="fas fa-search"></i> Filter
            </button>
        </div>
    </div>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        <h3><i class="fas fa-list"></i> Daftar Kunjungan</h3>
        <span style="background: var(--primary-bg); color: var(--primary); padding: 5px 12px; border-radius: 20px; font-size: 12px;">
            <i class="fas fa-notes-medical"></i> Total: {{ $visits->count() }} Kunjungan
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
                    <td>{{ $index + 1 }}</td>
                    <td><i class="fas fa-calendar-day" style="color: var(--primary); margin-right: 8px;"></i>{{ \Carbon\Carbon::parse($visit->tanggal_berobat)->format('d/m/Y') }}</td>
                    <td><i class="fas fa-user-circle" style="color: var(--primary); margin-right: 8px;"></i><strong>{{ $visit->patient->nama ?? '-' }}</strong></td>
                    <td>{{ Str::limit($visit->keluhan ?? '-', 50) }}</td>
                    <td>{{ Str::limit($visit->diagnostik ?? '-', 50) }}</td>
                    <td>{{ Str::limit($visit->terapi ?? '-', 50) }}</td>
                    <td>
                        <button class="btn-icon" onclick="showVisit({{ $visit->id }})" style="background: var(--primary); padding: 8px 12px;">
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
</div>

<!-- Modal Form (sama seperti sebelumnya) -->
<div id="modal" class="modal-glass">
    <div class="modal-content-glass" style="max-width: 700px;">
        <div class="modal-header-glass">
            <h3><i class="fas fa-stethoscope"></i> Tambah Kunjungan</h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
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
                            <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nik }})</option>
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
                        <textarea name="anamesis" rows="2" placeholder="Riwayat penyakit..."></textarea>
                    </div>
                    <div class="input-group-custom">
                        <label><i class="fas fa-heartbeat"></i> Pemeriksaan Fisik</label>
                        <textarea name="pemeriksaan_fisik" rows="2" placeholder="Hasil pemeriksaan fisik..."></textarea>
                    </div>
                    <div class="input-group-custom">
                        <label><i class="fas fa-flask"></i> Pemeriksaan Lab</label>
                        <input type="text" name="pemeriksaan_lab" placeholder="Hasil lab">
                    </div>
                    <div class="input-group-custom">
                        <label><i class="fas fa-microscope"></i> Diagnostik</label>
                        <input type="text" name="diagnostik" placeholder="Diagnosa">
                    </div>
                    <div class="input-group-custom">
                        <label><i class="fas fa-prescription"></i> Terapi</label>
                        <textarea name="terapi" rows="2" placeholder="Terapi / pengobatan..."></textarea>
                    </div>
                    <div class="input-group-custom">
                        <label><i class="fas fa-allergies"></i> Riwayat Alergi</label>
                        <input type="text" name="riwayat_alergi" placeholder="Riwayat alergi">
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

<!-- Modal Show -->
<div id="showModal" class="modal-glass">
    <div class="modal-content-glass" style="max-width: 600px;">
        <div class="modal-header-glass">
            <h3><i class="fas fa-file-medical"></i> Detail Kunjungan</h3>
            <button class="close-modal" onclick="closeShowModal()">&times;</button>
        </div>
        <div class="modal-body-glass" id="showContent"></div>
        <div class="modal-footer-glass">
            <button class="btn-secondary" onclick="closeShowModal()">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function applyFilter() {
        const params = new URLSearchParams({
            patient_name: document.getElementById('filterPatient').value,
            diagnosis: document.getElementById('filterDiagnosis').value,
            date_from: document.getElementById('filterFrom').value,
            date_to: document.getElementById('filterTo').value
        });
        
        fetch(`{{ route("visits.filter") }}?${params}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('visitTable');
                tbody.innerHTML = '';
                
                if (data.visits.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:60px;"><i class="fas fa-inbox" style="font-size: 48px; color: var(--gray-300); margin-bottom: 15px; display: block;"></i><span style="color: var(--gray-400);">Tidak ada data</span></td></tr>';
                    return;
                }
                
                data.visits.forEach((visit, index) => {
                    const row = tbody.insertRow();
                    row.insertCell(0).textContent = index + 1;
                    row.insertCell(1).innerHTML = `<i class="fas fa-calendar-day" style="color: var(--primary); margin-right: 8px;"></i>${new Date(visit.tanggal_berobat).toLocaleDateString('id-ID')}`;
                    row.insertCell(2).innerHTML = `<i class="fas fa-user-circle" style="color: var(--primary); margin-right: 8px;"></i><strong>${visit.patient?.nama || '-'}</strong>`;
                    row.insertCell(3).textContent = visit.keluhan?.substring(0, 50) || '-';
                    row.insertCell(4).textContent = visit.diagnostik?.substring(0, 50) || '-';
                    row.insertCell(5).textContent = visit.terapi?.substring(0, 50) || '-';
                    row.insertCell(6).innerHTML = `<button class="btn-icon" onclick="showVisit(${visit.id})" style="background: var(--primary); padding: 8px 12px;"><i class="fas fa-eye"></i></button>`;
                    row.id = `row-${visit.id}`;
                });
            });
    }
    
    function resetFilter() {
        document.getElementById('filterPatient').value = '';
        document.getElementById('filterDiagnosis').value = '';
        document.getElementById('filterFrom').value = '';
        document.getElementById('filterTo').value = '';
        applyFilter();
    }
    
    function openModal() {
        document.getElementById('modal').style.display = 'flex';
    }
    
    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }
    
    function showVisit(id) {
        fetch(`/visits/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('showContent').innerHTML = `
                    <div class="detail-item"><strong><i class="fas fa-calendar"></i> Tanggal:</strong> <span>${new Date(data.tanggal_berobat).toLocaleDateString('id-ID')}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-user"></i> Pasien:</strong> <span>${data.patient?.nama || '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-stethoscope"></i> Keluhan:</strong> <span>${data.keluhan || '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-notes-medical"></i> Anamesis:</strong> <span>${data.anamesis || '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-heartbeat"></i> Pemeriksaan Fisik:</strong> <span>${data.pemeriksaan_fisik || '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-flask"></i> Pemeriksaan Lab:</strong> <span>${data.pemeriksaan_lab || '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-microscope"></i> Diagnostik:</strong> <span>${data.diagnostik || '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-prescription"></i> Terapi:</strong> <span>${data.terapi || '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-allergies"></i> Riwayat Alergi:</strong> <span>${data.riwayat_alergi || '-'}</span></div>
                `;
                document.getElementById('showModal').style.display = 'flex';
            });
    }
    
    function closeShowModal() {
        document.getElementById('showModal').style.display = 'none';
    }
    
    window.onclick = function(event) {
        if (event.target == document.getElementById('modal')) closeModal();
        if (event.target == document.getElementById('showModal')) closeShowModal();
    }
</script>
@endpush