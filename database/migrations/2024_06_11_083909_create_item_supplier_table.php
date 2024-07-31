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
        Schema::create('item_supplier', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier');
            $table->string('sup_name', 191)->nullable();
            $table->integer('sku');
            $table->string('sku_desc', 191)->nullable();
            $table->string('upc', 191)->nullable();
            $table->double('unit_cost');
            $table->string('create_id', 225)->nullable();
            $table->string('create_date', 225)->nullable();
            $table->string('last_update_id', 225)->nullable();
            $table->string('last_update_date', 225)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_supplier');
    }
};
