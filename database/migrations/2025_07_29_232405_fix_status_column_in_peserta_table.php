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
        // Kita hanya akan fokus pada menghapus kolom lama dan membuat yang baru
        Schema::table('peserta', function (Blueprint $table) {
            // Langkah 1: Hapus kolom 'status' yang lama (yang bertipe integer).
            $table->dropColumn('status');
        });

        Schema::table('peserta', function (Blueprint $table) {
            // Langkah 2: Tambahkan kembali kolom 'status' dengan tipe data STRING yang benar.
            $table->string('status', 50)->default('registrasi')->after('alamat');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            // Logika untuk mengembalikan jika di-rollback
            $table->dropColumn('status');
        });

        Schema::table('peserta', function (Blueprint $table) {
            // Buat kembali kolom lama yang bertipe integer
            // Sesuaikan dengan tipe data asli Anda jika berbeda
            $table->bigInteger('status')->unsigned()->after('alamat');
        });
    }
};
