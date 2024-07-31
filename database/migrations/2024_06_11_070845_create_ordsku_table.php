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
        Schema::create('ordsku', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ordhead_id');
            $table->integer('order_no');
            $table->integer('sku');
            $table->string('sku_desc', 191)->nullable();
            $table->string('upc', 25);
            $table->string('tag_code', 191)->nullable();
            $table->integer('unit_cost');
            $table->integer('unit_retail');
            $table->double('vat_cost')->nullable();
            $table->integer('luxury_cost')->nullable();
            $table->integer('qty_ordered')->nullable();
            $table->integer('qty_fulfilled')->nullable();
            $table->integer('qty_received')->nullable();
            $table->integer('unit_discount')->nullable();
            $table->integer('unit_permanent_discount')->nullable();
            $table->string('purchase_uom', 191)->nullable();
            $table->integer('supp_pack_size')->nullable();
            $table->integer('permanent_disc_pct')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordsku');
    }
};
