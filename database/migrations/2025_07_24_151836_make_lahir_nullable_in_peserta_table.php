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
        Schema::table('peserta', function (Blueprint $table) {
            // Ubah tipe data kolom 'lahir' agar bisa menerima nilai NULL.
            // Tipe data aslinya kemungkinan adalah 'varchar' atau 'date'.
            // Sesuaikan jika berbeda.
            $table->string('lahir')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            // Kembalikan menjadi tidak boleh null jika di-rollback
            $table->string('lahir')->nullable(false)->change();
        });
    }
};