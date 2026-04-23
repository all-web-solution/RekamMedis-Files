<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    protected $table = 'visits';
    protected $fillable = [
        'patient_id',
        'tanggal_berobat',
        'keluhan',
        'anamesis',
        'pemeriksaan_fisik',
        'pemeriksaan_lab',
        'diagnostik',
        'terapi',
        'riwayat_alergi'
    ];

    protected $casts = [
        'tanggal_berobat' => 'date'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
