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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('tujuan_bidang')->nullable()->constrained('work_fields')->onDelete('set null'); // Tambah relasi tujuan_bidang
            $table->string('tujuan_pengunjung')->nullable();
            $table->enum('instansi', ['Dinas', 'Non Kedinasan']);
            $table->string('nama_instansi')->nullable();
            $table->string('alamat');
            $table->string('no_hp');
            $table->string('foto')->nullable();
            $table->date('tanggal');
            $table->enum('jenis_kelamin', ['l', 'p']);
            $table->enum('status', ['sedang kunjungan', 'selesai kunjungan'])->default('sedang kunjungan'); // Tambahkan status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
