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
            $table->string('tujuan');
            $table->string('instansi');
            $table->string('alamat');
            $table->string('no_hp');
            $table->string('foto')->nullable();
            $table->date('tanggal');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']); // Kolom jenis kelamin
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
