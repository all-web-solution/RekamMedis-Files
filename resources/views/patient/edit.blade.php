@extends('layout.master')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Edit Pasien</h1>
        <a href="{{ route('patients.index') }}" class="btn-icon"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern"><h3><i class="fas fa-edit"></i> Edit Data Pasien</h3></div>
        <form action="{{ route('patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid" style="padding: 20px;">
                <div class="input-group-custom"><label>Nama Lengkap</label><input type="text" name="nama" value="{{ $patient->nama }}" required></div>
                <div class="input-group-custom"><label>NIK</label><input type="text" name="nik" value="{{ $patient->nik }}" required></div>
                <div class="input-group-custom"><label>Umur</label><input type="number" name="umur" value="{{ $patient->umur }}" required></div>
                <div class="input-group-custom"><label>Jenis Kelamin</label>
                    <select name="jenis_kelamin">
                        <option {{ $patient->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option {{ $patient->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="input-group-custom"><label>Tinggi (cm)</label><input type="number" step="0.01" name="tinggi" value="{{ $patient->tinggi }}"></div>
                <div class="input-group-custom"><label>Berat (kg)</label><input type="number" step="0.01" name="berat" value="{{ $patient->berat }}"></div>
            </div>
            <div class="modal-footer-glass" style="padding: 20px;">
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection