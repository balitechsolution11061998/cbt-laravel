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
        Schema::create('temp_rcv', function (Blueprint $table) {

            $table->integer('receive_no');
            $table->date('receive_date');
            $table->date('created_date');
            $table->string('receive_id', 50);
            $table->integer('order_no');
            $table->integer('ref_no')->nullable();
            $table->string('order_type', 10)->nullable();
            $table->string('status_ind', 5)->nullable();
            $table->date('approval_date');
            $table->string('approval_id', 50)->nullable();
            $table->integer('store');
            $table->string('store_name', 20);
            $table->integer('sku');
            $table->string('sku_desc', 191);
            $table->string('upc', 20)->nullable();
            $table->integer('qty_expected')->nullable();
            $table->integer('qty_received')->nullable();
            $table->integer('unit_cost')->nullable();
            $table->integer('unit_retail')->nullable();
            $table->double('vat_cost', 8, 2)->nullable();
            $table->integer('unit_cost_disc')->nullable();
            $table->integer('supplier');
            $table->string('sup_name', 191);
            $table->string('comment_desc', 191)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_rcv');
    }
};
