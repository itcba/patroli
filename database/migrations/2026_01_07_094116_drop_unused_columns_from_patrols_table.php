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
        Schema::table('patrols', function (Blueprint $table) {
            $table->dropColumn(['nama_anggota_2', 'nama_anggota_3', 'keterangan_absensi', 'esign_name']);
        });
    }

    public function down(): void
    {
        Schema::table('patrols', function (Blueprint $table) {
            $table->string('nama_anggota_2');
            $table->string('nama_anggota_3');
            $table->string('keterangan_absensi');
            $table->string('esign_name');
        });
    }
};
