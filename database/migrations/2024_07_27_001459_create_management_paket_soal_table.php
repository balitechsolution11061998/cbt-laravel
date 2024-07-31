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
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paket_soal_id');
            $table->enum('jenis', ['pilihan_ganda', 'essai']);
            $table->text('pertanyaan');
            $table->text('pertanyaan_a')->nullable(); // No `after` clause needed
            $table->text('pertanyaan_b')->nullable(); // No `after` clause needed
            $table->text('pertanyaan_c')->nullable(); // No `after` clause needed
            $table->text('pertanyaan_d')->nullable(); // No `after` clause needed
            $table->text('media')->nullable();
            $table->text('ulang_media')->nullable();
            $table->string('jawaban_benar')->nullable();
            $table->timestamps();
        });

        Schema::create('soal_pilihan', function (Blueprint $table) {
            $table->id(); // Primary key and auto-increment column
            $table->unsignedBigInteger('soal_id');
            $table->text('jawaban');
            $table->text('media')->nullable(); // Allow null if not required for every choice
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('soal_id')->references('id')->on('soal')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_pilihan');
        Schema::dropIfExists('soal');
    }
};
