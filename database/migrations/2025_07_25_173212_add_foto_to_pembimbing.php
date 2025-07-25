<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembimbing_instansi', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('bidang');
        });

        Schema::table('pembimbing_kominfo', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('bidang');
        });
    }

    public function down(): void
    {
        Schema::table('pembimbing_instansi', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('pembimbing_kominfo', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};