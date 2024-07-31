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
        Schema::create('pricelist_head', function (Blueprint $table) {
            $table->bigIncrements('id'); // UNSIGNED by default for bigIncrements
            $table->string('pricelist_no', 191);
            $table->string('pricelist_desc', 191)->nullable();
            $table->date('active_date')->nullable();
            $table->integer('supplier_id');
            $table->integer('role_last_app')->nullable();
            $table->integer('role_next_app')->nullable();
            $table->string('approval_id', 191)->nullable();
            $table->string('status', 191)->nullable();
            $table->timestamps(); // This will add both `created_at` and `updated_at` fields

            $table->index('pricelist_no');
            $table->index('supplier_id');
        });

        Schema::create('pricelist_detail', function (Blueprint $table) {
            $table->bigIncrements('id'); // UNSIGNED and AUTO_INCREMENT by default
            $table->unsignedBigInteger('pricelist_head_id');
            $table->unsignedBigInteger('barcode');
            $table->string('item_desc', 191)->nullable();
            $table->integer('old_cost');
            $table->integer('new_cost');
            $table->timestamps(); // This will add both `created_at` and `updated_at` fields

            // Adding indexes
            $table->index('pricelist_head_id');
            $table->index('barcode');
            $table->index('old_cost');
            $table->index('new_cost');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricelists');
        Schema::dropIfExists('pricelists');

    }
};
