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
        Schema::create('izin_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id');
            $table->string('keterangan');
            $table->string('tanggal');
            $table->string('bukti_foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_cuti');
    }
};
