<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_berobat');
            $table->text('keluhan')->nullable();
            $table->text('anamesis')->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            $table->text('pemeriksaan_lab')->nullable();
            $table->text('diagnostik')->nullable();
            $table->text('terapi')->nullable();
            $table->text('riwayat_alergi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
