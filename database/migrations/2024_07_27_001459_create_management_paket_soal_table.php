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
        // Create 'soal' table for questions
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paket_soal_id'); // Reference to paket_soal table
            $table->enum('jenis', ['pilihan_ganda', 'gambar']); // Question type: multiple choice or image
            $table->text('pertanyaan')->nullable(); // Text-based question (optional if image-based)
            $table->string('pertanyaan_image')->nullable(); // Image for the question (if applicable)
            $table->text('pertanyaan_a')->nullable(); // Option A for multiple choice
            $table->text('pertanyaan_b')->nullable(); // Option B
            $table->text('pertanyaan_c')->nullable(); // Option C
            $table->text('pertanyaan_d')->nullable(); // Option D
            $table->text('jawaban_benar')->nullable(); // Correct answer for both types
            $table->timestamps(); // Timestamps for created_at and updated_at

            // Foreign key constraint to paket_soal
            $table->foreign('paket_soal_id')->references('id')->on('paket_soal')->onDelete('cascade');
        });

        // Create 'soal_pilihan' table for multiple-choice answer options (used only for 'pilihan_ganda' type)
        Schema::create('soal_pilihan', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('soal_id'); // Reference to 'soal' table
            $table->text('jawaban')->nullable(); // Text-based answer for multiple choice
            $table->string('jawaban_image')->nullable(); // Image-based answer (optional)
            $table->timestamps(); // Timestamps for created_at and updated_at

            // Foreign key constraint to soal
            $table->foreign('soal_id')->references('id')->on('soal')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the 'soal_pilihan' table first due to foreign key constraint
        Schema::dropIfExists('soal_pilihan');
        // Then drop the 'soal' table
        Schema::dropIfExists('soal');
    }
};
