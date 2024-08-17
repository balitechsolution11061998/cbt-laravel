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

        Schema::create('ujian_histories_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_history_id');
            $table->unsignedBigInteger('soal_id');
            $table->text('jawaban_siswa');
            $table->text('jawaban_benar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_history_details');
    }
};
