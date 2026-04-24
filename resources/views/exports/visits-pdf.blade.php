<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Kunjungan</title>
    <style>
        @page {
            margin: 18px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 10px;
            line-height: 1.4;
        }

        .header {
            border-bottom: 3px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #047857;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 11px;
            color: #4b5563;
        }

        .meta-grid {
            width: 100%;
            margin: 12px 0 16px;
            border-collapse: collapse;
        }

        .meta-grid td {
            padding: 5px 8px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }

        .meta-label {
            width: 145px;
            background: #ecfdf5;
            color: #047857;
            font-weight: bold;
        }

        .summary-box {
            display: block;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 9px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            font-weight: bold;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.data-table th {
            background: #059669;
            color: white;
            padding: 7px 5px;
            border: 1px solid #047857;
            font-size: 8.5px;
            text-align: left;
        }

        table.data-table td {
            padding: 6px 5px;
            border: 1px solid #d1d5db;
            vertical-align: top;
            font-size: 8px;
            word-wrap: break-word;
        }

        table.data-table tr:nth-child(even) td {
            background: #f9fafb;
        }

        .empty {
            text-align: center;
            color: #6b7280;
            padding: 24px;
            border: 1px solid #d1d5db;
        }

        .footer {
            margin-top: 14px;
            font-size: 8px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">Klinik Sehat</div>
        <div class="subtitle">Laporan Data Kunjungan Pasien</div>
    </div>

    <table class="meta-grid">
        <tr>
            <td class="meta-label">Jenis Laporan</td>
            <td>{{ $summary['is_all'] ? 'Seluruh Data Kunjungan' : 'Data Kunjungan Berdasarkan Filter' }}</td>
            <td class="meta-label">Tanggal Cetak</td>
            <td>{{ \Carbon\Carbon::parse($summary['generated_at'])->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Rentang Tanggal</td>
            <td>
                @if($summary['date_from'] || $summary['date_to'])
                    {{ $summary['date_from'] ? \Carbon\Carbon::parse($summary['date_from'])->format('d/m/Y') : 'Awal' }}
                    -
                    {{ $summary['date_to'] ? \Carbon\Carbon::parse($summary['date_to'])->format('d/m/Y') : 'Akhir' }}
                @else
                    Semua tanggal
                @endif
            </td>
            <td class="meta-label">Total Kunjungan</td>
            <td>{{ $summary['total'] }} kunjungan</td>
        </tr>
        <tr>
            <td class="meta-label">Filter Pasien</td>
            <td>{{ $summary['patient_name'] ?: '-' }}</td>
            <td class="meta-label">Filter Diagnosa</td>
            <td>{{ $summary['diagnosis'] ?: '-' }}</td>
        </tr>
    </table>

    <div class="summary-box">
        Total data yang diekspor: {{ $summary['total'] }} kunjungan
    </div>

    @if($visits->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 28px;">No</th>
                    <th style="width: 60px;">Tanggal</th>
                    <th style="width: 95px;">NIK</th>
                    <th style="width: 120px;">Pasien</th>
                    <th>Keluhan</th>
                    <th>Anamesis</th>
                    <th>Pemeriksaan Fisik</th>
                    <th>Pemeriksaan Lab</th>
                    <th>Diagnostik</th>
                    <th>Terapi</th>
                    <th>Riwayat Alergi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visits as $index => $visit)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $visit->tanggal_berobat ? \Carbon\Carbon::parse($visit->tanggal_berobat)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $visit->patient->nik ?? '-' }}</td>
                        <td>{{ $visit->patient->nama ?? '-' }}</td>
                        <td>{{ $visit->keluhan ?: '-' }}</td>
                        <td>{{ $visit->anamesis ?: '-' }}</td>
                        <td>{{ $visit->pemeriksaan_fisik ?: '-' }}</td>
                        <td>{{ $visit->pemeriksaan_lab ?: '-' }}</td>
                        <td>{{ $visit->diagnostik ?: '-' }}</td>
                        <td>{{ $visit->terapi ?: '-' }}</td>
                        <td>{{ $visit->riwayat_alergi ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">
            Tidak ada data kunjungan untuk diekspor.
        </div>
    @endif

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Sistem Manajemen Klinik Sehat.
    </div>
</body>
</html>
