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
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('pembimbing_instansi_id');
            $table->foreignId('pembimbing_kominfo_id');
            $table->string('nisn')->unique();
            $table->string('gambar');
            $table->string('asal_sekolah');
            $table->string('kelas');
            $table->string('jurusan');
            $table->string('telepon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};
