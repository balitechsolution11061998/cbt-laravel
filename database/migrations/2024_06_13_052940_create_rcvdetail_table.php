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
        Schema::create('rcvdetail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rcvhead_id');
            $table->foreign('rcvhead_id')->references('id')->on('rcvhead')->onDelete('cascade');
            $table->integer('receive_no');
            $table->integer('store');
            $table->integer('sku');
            $table->string('upc', 20);
            $table->string('sku_desc', 191);
            $table->integer('qty_expected');
            $table->integer('qty_received');
            $table->integer('unit_cost');
            $table->integer('unit_retail');
            $table->double('vat_cost', 8, 2);
            $table->integer('service_level');
            $table->integer('unit_cost_disc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rcvdetail');
    }
};
