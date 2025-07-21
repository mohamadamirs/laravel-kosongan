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
            $table->string('nama')->after('user_id');
            $table->string('lahir')->after('nama');
            $table->string('alamat')->after('lahir');
            $table->foreignId('status')->references('id')->on('surat_masuk')->onDelete('cascade')->after('alamat');
            $table->string('mulai_magang')->after('telepon');
            $table->string('selesai_magang')->after('mulai_magang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            //
        });
    }
};
