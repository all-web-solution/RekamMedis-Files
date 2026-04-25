@extends('layout.master')

@section('page-title', 'Data Pasien')
@section('page-description', 'Kelola data pasien klinik')

@section('content')
    <div class="top-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <button class="btn-icon" onclick="openModal()">
            <i class="fas fa-plus"></i> Pasien Baru
        </button>
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

    @if ($errors->any())
        <div
            style="background-color: #fef2f2; border: 1px solid #fecaca; border-left: 6px solid #ef4444; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.1), 0 2px 4px -2px rgba(239, 68, 68, 0.1); transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div
                    style="background-color: #fee2e2; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 14px;"></i>
                </div>
                <strong style="color: #991b1b; font-size: 15px; font-weight: 600; letter-spacing: -0.01em;">
                    Gagal menyimpan data! Terdapat kesalahan pada input:
                </strong>
            </div>
            <ul
                style="margin: 0 0 0 46px; padding: 0; color: #b91c1c; font-size: 14px; line-height: 1.6; list-style-type: disc;">
                @foreach ($errors->all() as $error)
                    <li style="margin-bottom: 6px; padding-left: 4px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="filter-section">
        <h4><i class="fas fa-search"></i> Cari Pasien</h4>
        <div class="filter-grid">
            <div class="filter-group" style="grid-column: span 3;">
                <label><i class="fas fa-search"></i> Kata Kunci</label>
                <input type="text" id="searchInput" class="filter-input" placeholder="Cari berdasarkan nama atau NIK...">
            </div>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h3><i class="fas fa-list"></i> Daftar Pasien</h3>
            <span
                style="background: var(--primary-bg); color: var(--primary); padding: 5px 12px; border-radius: 20px; font-size: 12px;">
                <i class="fas fa-users"></i> Total: {{ $patients->count() }} Pasien
            </span>
        </div>
        <div class="table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th><i class="fas fa-id-card"></i> NIK</th>
                        <th><i class="fas fa-user"></i> Nama</th>
                        <th><i class="fas fa-birthday-cake"></i> Umur</th>
                        <th><i class="fas fa-venus-mars"></i> JK</th>
                        <th><i class="fas fa-ruler"></i> Tinggi</th>
                        <th><i class="fas fa-weight-scale"></i> Berat</th>
                        <th><i class="fas fa-cogs"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody id="patientTable">
                    @forelse($patients as $patient)
                        <tr id="row-{{ $patient->id }}">
                            <td>
                                @if ($patient->nik)
                                    <span
                                        style="background: var(--primary-bg); color: var(--primary); padding: 4px 8px; border-radius: 8px; font-size: 12px;">{{ $patient->nik }}</span>
                                @else
                                    <span style="color: #9ca3af; font-weight: bold;">-</span>
                                @endif
                            </td>
                            <td><i class="fas fa-user-circle"
                                    style="color: var(--primary); margin-right: 8px;"></i><strong>{{ $patient->nama }}</strong>
                            </td>
                            <td>{{ $patient->umur }} th</td>
                            <td>
                                @if ($patient->jenis_kelamin == 'Laki-laki')
                                    <i class="fas fa-mars" style="color: var(--primary);"></i>
                                @else
                                    <i class="fas fa-venus" style="color: var(--primary);"></i>
                                @endif {{ $patient->jenis_kelamin }}
                            </td>
                            <td>{{ $patient->tinggi ?? '-' }} cm</td>
                            <td>{{ $patient->berat ?? '-' }} kg</td>
                            <td class="action-buttons">
                                <a href="{{ route('patients.show', $patient->id) }}" class="btn-icon"
                                    style="background: var(--primary); padding: 8px 12px; text-decoration: none;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn-warning" onclick="editPatient({{ $patient->id }})"
                                    style="padding: 8px 12px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('patients.destroy', $patient->id) }}"
                                    class="delete-inline-form" data-confirm-delete="true" data-title="Hapus pasien?"
                                    data-text="Pasien {{ $patient->nama }} akan dihapus beserta seluruh riwayat kunjungannya. Data tidak dapat dikembalikan."
                                    data-confirm-text="Ya, hapus pasien">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-danger-native">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:60px;">
                                <i class="fas fa-inbox"
                                    style="font-size: 48px; color: var(--gray-300); margin-bottom: 15px; display: block;"></i>
                                <span style="color: var(--gray-400);">Belum ada data pasien</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (sama seperti sebelumnya) -->
    <div id="modal" class="modal-glass">
        <div class="modal-content-glass">
            <div class="modal-header-glass">
                <h3 id="modalTitle"><i class="fas fa-user-md"></i> Tambah Pasien</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <form id="patientForm" method="POST" action="{{ route('patients.store') }}">
                @csrf
                <input type="hidden" name="_method" id="method" value="{{ old('_method', 'POST') }}">
                <input type="hidden" name="id" id="patientId" value="{{ old('id') }}">

                <div class="modal-body-glass">
                    <div class="form-grid">
                        <div class="input-group-custom">
                            <label><i class="fas fa-id-card"></i> NIK</label>

                            <input type="text" name="nik" id="nik"
                                placeholder="Masukkan 16 Digit NIK (Opsional)" maxlength="16" minlength="16"
                                pattern="[0-9]{16}" title="NIK harus berupa 16 digit angka"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('nik') }}">

                            @error('nik')
                                <small style="color: #dc2626; margin-top: 5px; display: block;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-group-custom">
                            <label><i class="fas fa-user"></i> Nama Lengkap *</label>
                            <input type="text" name="nama" id="nama" required
                                placeholder="Masukkan nama lengkap" value="{{ old('nama') }}">
                            @error('nama')
                                <small style="color: #dc2626; margin-top: 5px; display: block;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-group-custom">
                            <label><i class="fas fa-birthday-cake"></i> Umur *</label>
                            <input type="number" name="umur" id="umur" required placeholder="Usia dalam tahun"
                                value="{{ old('umur') }}">
                            @error('umur')
                                <small style="color: #dc2626; margin-top: 5px; display: block;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-group-custom">
                            <label><i class="fas fa-venus-mars"></i> Jenis Kelamin *</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <small style="color: #dc2626; margin-top: 5px; display: block;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-group-custom">
                            <label><i class="fas fa-ruler"></i> Tinggi Badan (cm)</label>
                            <input type="number" step="0.01" name="tinggi" id="tinggi"
                                placeholder="Contoh: 165" value="{{ old('tinggi') }}">
                            @error('tinggi')
                                <small style="color: #dc2626; margin-top: 5px; display: block;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-group-custom">
                            <label><i class="fas fa-weight-scale"></i> Berat Badan (kg)</label>
                            <input type="number" step="0.01" name="berat" id="berat" placeholder="Contoh: 60"
                                value="{{ old('berat') }}">
                            @error('berat')
                                <small style="color: #dc2626; margin-top: 5px; display: block;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer-glass">
                    <button type="button" class="btn-secondary" onclick="closeModal()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Show -->
    <div id="showModal" class="modal-glass">
        <div class="modal-content-glass">
            <div class="modal-header-glass">
                <h3><i class="fas fa-user-circle"></i> Detail Pasien</h3>
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
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                // Tampilkan modal otomatis
                document.getElementById('modal').style.display = 'flex';

                // Jika error terjadi saat sedang mode Edit (ada old('id'))
                let oldId = "{{ old('id') }}";
                if (oldId) {
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Pasien';
                    document.getElementById('method').value = 'PUT';
                    document.getElementById('patientForm').action = `/patients/${oldId}`;
                    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Update';
                }
            });
        @endif
        // Search filter
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll('#patientTable tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });

        function openModal() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-md"></i> Tambah Pasien';
            document.getElementById('patientForm').reset();
            document.getElementById('patientId').value = '';
            document.getElementById('method').value = 'POST';
            document.getElementById('patientForm').action = '{{ route('patients.store') }}';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Simpan';
            document.getElementById('modal').style.display = 'flex';
        }

        function editPatient(id) {
            fetch(`/patients/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Pasien';
                    document.getElementById('patientId').value = data.id;
                    document.getElementById('nik').value = data.nik;
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('umur').value = data.umur;
                    document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
                    document.getElementById('tinggi').value = data.tinggi;
                    document.getElementById('berat').value = data.berat;
                    document.getElementById('method').value = 'PUT';
                    document.getElementById('patientForm').action = `/patients/${data.id}`;
                    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Update';
                    document.getElementById('modal').style.display = 'flex';
                });
        }

        function showPatient(id) {
            fetch(`/patients/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('showContent').innerHTML = `
                    <div class="detail-item"><strong><i class="fas fa-id-card"></i> NIK:</strong> <span>${data.nik ? data.nik : '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-user"></i> Nama:</strong> <span>${data.nama}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-birthday-cake"></i> Umur:</strong> <span>${data.umur} tahun</span></div>
                    <div class="detail-item"><strong><i class="fas fa-venus-mars"></i> Jenis Kelamin:</strong> <span>${data.jenis_kelamin}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-ruler"></i> Tinggi:</strong> <span>${data.tinggi ? data.tinggi + ' cm' : '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-weight-scale"></i> Berat:</strong> <span>${data.berat ? data.berat + ' kg' : '-'}</span></div>
                    <div class="detail-item"><strong><i class="fas fa-calendar"></i> Dibuat:</strong> <span>${new Date(data.created_at).toLocaleDateString('id-ID')}</span></div>
                `;
                    document.getElementById('showModal').style.display = 'flex';
                });
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
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
