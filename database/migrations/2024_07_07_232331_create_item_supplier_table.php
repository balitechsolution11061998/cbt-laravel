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
        // Schema::create('item_supplier', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->integer('supplier')->unsigned();
        //     $table->string('sup_name', 191)->nullable()->collation('utf8mb4_unicode_ci');
        //     $table->integer('sku');
        //     $table->string('sku_desc', 191)->nullable()->collation('utf8mb4_unicode_ci');
        //     $table->string('upc', 191)->nullable()->collation('utf8mb4_unicode_ci');
        //     $table->double('unit_cost')->nullable();
        //     $table->string('create_id', 225)->nullable()->collation('utf8mb4_unicode_ci');
        //     $table->string('create_date', 225)->nullable()->collation('utf8mb4_unicode_ci');
        //     $table->string('last_update_id', 225)->nullable()->collation('utf8mb4_unicode_ci');
        //     $table->string('last_update_date', 225)->nullable()->collation('utf8mb4_unicode_ci');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_supplier');
    }
};
