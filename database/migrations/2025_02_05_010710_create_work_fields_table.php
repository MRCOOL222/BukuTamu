<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('work_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Hanya menyimpan nama bidang
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_fields');
    }

};
