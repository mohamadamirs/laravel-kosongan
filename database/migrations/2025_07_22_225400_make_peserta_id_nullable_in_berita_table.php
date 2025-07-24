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
        Schema::table('berita', function (Blueprint $table) {
            // Ubah kolom 'peserta_id' agar bisa menerima nilai NULL
            // 'unsignedBigInteger' harus cocok dengan tipe data aslinya
            $table->unsignedBigInteger('peserta_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            // Jika migrasi di-rollback, kembalikan aturan menjadi tidak boleh null
            $table->unsignedBigInteger('peserta_id')->nullable(false)->change();
             $table->string('status')->default('menunggu_persetujuan')->after('paragraf');
        });
    }
};