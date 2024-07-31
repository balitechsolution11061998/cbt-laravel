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
        Schema::create('izin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_izin', 255)->nullable();
            $table->string('nik', 255)->nullable();
            $table->date('tgl_izin_dari')->nullable();
            $table->date('tgl_izin_sampai')->nullable();
            $table->string('status', 255)->nullable();
            $table->string('kode_cuti', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('doc_sid', 255)->nullable();
            $table->string('status_approved', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin');
    }
};
