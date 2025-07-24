<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            // Hapus foreign key constraint. Nama constraint bisa bervariasi.
            // Nama defaultnya adalah 'nama_tabel_nama_kolom_foreign'.
            // Cek nama constraint Anda di phpMyAdmin jika nama ini tidak berhasil.
            $table->dropForeign('peserta_status_foreign');
        });
    }

    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            // Jika di-rollback, buat kembali foreign key-nya
            $table->foreign('status')->references('id')->on('surat_masuk')->onDelete('cascade');
        });
    }
};