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
        Schema::create('provinsi', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
        });

        Schema::create('kabupaten', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Kabupaten', 'Kota'])->default('Kabupaten');
            $table->string('name');
            $table->string('code');
            $table->string('full_code');
            $table->unsignedBigInteger('provinsi_id');
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('full_code');
            $table->unsignedBigInteger('kabupaten_id');
            $table->foreign('kabupaten_id')->references('id')->on('kabupaten')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('kelurahan', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('full_code');
            $table->string('pos_code');
            $table->unsignedBigInteger('kecamatan_id');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->cascadeOnUpdate()->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinsi');
        Schema::dropIfExists('kabupaten');
        Schema::dropIfExists('kecamatan');
        Schema::dropIfExists('kelurahan');
    }
};
