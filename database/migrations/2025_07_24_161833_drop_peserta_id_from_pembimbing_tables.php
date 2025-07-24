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
        // Menghapus foreign key dan kolom dari tabel pembimbing_instansi
        Schema::table('pembimbing_instansi', function (Blueprint $table) {
            // Cek nama foreign key di database Anda jika nama default ini tidak berhasil
            $table->dropForeign('pembimbing_instansi_peserta_id_foreign');
            $table->dropColumn('peserta_id');
        });

        // Menghapus foreign key dan kolom dari tabel pembimbing_kominfo
        Schema::table('pembimbing_kominfo', function (Blueprint $table) {
            $table->dropForeign('pembimbing_kominfo_peserta_id_foreign');
            $table->dropColumn('peserta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Logika untuk mengembalikan kolom jika migrasi di-rollback
        Schema::table('pembimbing_instansi', function (Blueprint $table) {
            $table->unsignedBigInteger('peserta_id');
            $table->foreign('peserta_id')->references('id')->on('peserta')->onDelete('cascade');
        });

        Schema::table('pembimbing_kominfo', function (Blueprint $table) {
            $table->unsignedBigInteger('peserta_id');
            $table->foreign('peserta_id')->references('id')->on('peserta')->onDelete('cascade');
        });
    }
};