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
            // Ubah kedua kolom foreign key ini agar bisa menerima nilai NULL
            $table->unsignedBigInteger('pembimbing_instansi_id')->nullable()->change();
            $table->unsignedBigInteger('pembimbing_kominfo_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            // Jika migrasi di-rollback, kembalikan aturan menjadi tidak boleh null
            $table->unsignedBigInteger('pembimbing_instansi_id')->nullable(false)->change();
            $table->unsignedBigInteger('pembimbing_kominfo_id')->nullable(false)->change();
        });
    }
};