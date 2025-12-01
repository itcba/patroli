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
    Schema::create('patrols', function (Blueprint $table) {
        $table->id();
        // Data Shift & Anggota
        $table->string('nama_anggota_1');
        $table->string('nama_anggota_2');
        $table->string('nama_anggota_3');
        $table->string('hari');
        $table->date('tanggal');
        $table->time('jam_dinas');
        $table->string('shift');
        $table->string('jabatan');
        $table->string('area');
        $table->string('keterangan_absensi');

        // Detail Patroli (Disimpan sebagai JSON karena dinamis/banyak baris)
        $table->json('patrol_details');

        // Tanda Tangan
        $table->string('esign_name');
        $table->longText('esign_image'); // Base64 string image

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrols');
    }
};
