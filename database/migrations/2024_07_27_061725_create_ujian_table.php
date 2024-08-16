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
        {
            Schema::create('ujian', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->unsignedBigInteger('paket_soal_id');
                $table->unsignedBigInteger('kelas_id');
                $table->dateTime('waktu_mulai');
                $table->integer('durasi');
                $table->integer('poin_benar');
                $table->integer('poin_salah');
                $table->integer('poin_tidak_jawab');
                $table->text('keterangan')->nullable();
                $table->json('kelas');
                $table->boolean('tampilkan_nilai')->default(false);
                $table->boolean('tampilkan_hasil')->default(false);
                $table->boolean('gunakan_token')->default(false);
                $table->unsignedBigInteger('mata_pelajaran_id');
                $table->timestamps();

                // Foreign key constraints

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};
