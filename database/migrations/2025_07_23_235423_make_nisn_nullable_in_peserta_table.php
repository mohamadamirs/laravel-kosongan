<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            // Ubah kolom 'nisn' agar bisa menerima nilai NULL
            $table->string('nisn')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            // Kembalikan menjadi tidak boleh null jika di-rollback
            $table->string('nisn')->nullable(false)->change();
        });
    }
};