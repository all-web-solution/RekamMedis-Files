{{-- resources/views/partials/visit-table-body.blade.php --}}
@forelse($visits as $index => $visit)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>
        <strong>{{ \Carbon\Carbon::parse($visit->tanggal_berobat)->format('d/m/Y') }}</strong>
        <br>
        <small class="badge-info" style="padding: 2px 8px; background: #dbeafe; border-radius: 12px;">
            {{ \Carbon\Carbon::parse($visit->tanggal_berobat)->diffForHumans() }}
        </small>
    </td>
    <td>
        <strong>{{ $visit->patient->nama ?? '-' }}</strong>
        <br>
        <small>NIK: {{ $visit->patient->nik ?? '-' }}</small>
    </td>
    <td>{{ Str::limit($visit->keluhan ?? '-', 50) }}</td>
    <td>
        @if($visit->diagnostik)
            <span class="badge-success" style="padding: 4px 12px; background: #dcfce7; border-radius: 20px;">
                {{ $visit->diagnostik }}
            </span>
        @else
            -
        @endif
    </td>
    <td>{{ Str::limit($visit->terapi ?? '-', 50) }}</td>
    <td>
        <button class="btn-icon" style="padding: 6px 12px; font-size: 0.75rem;" onclick="showVisitDetail({{ $visit->id }})">
            <i class="fas fa-eye"></i> Detail
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" style="text-align:center; padding:40px;">
        <i class="fas fa-notes-medical" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
        Belum ada data kunjungan
    </td>
</tr>
@endforelse