<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->index(['patient_id', 'tanggal_berobat'], 'visits_patient_date_idx');
            $table->index('tanggal_berobat', 'visits_date_idx');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->index('nama', 'patients_nama_idx');
        });
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex('visits_patient_date_idx');
            $table->dropIndex('visits_date_idx');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex('patients_nama_idx');
        });
    }
};
