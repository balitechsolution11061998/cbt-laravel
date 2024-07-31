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
        //
        Schema::create('jam_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jk')->nullable();
            $table->string('nama_jk')->nullable();
            $table->time('awal_jam_masuk')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('akhir_jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->boolean('lintas_hari')->nullable();
            // Add any other fields as needed

            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('jam_kerja');

    }
};
