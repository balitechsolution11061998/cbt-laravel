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
        Schema::create('temp_po', function (Blueprint $table) {
            $table->integer('order_no');
            $table->smallInteger('ship_to');
            $table->integer('supplier');
            $table->smallInteger('terms');
            $table->smallInteger('status_ind');
            $table->date('written_date');
            $table->date('not_before_date');
            $table->date('not_after_date');
            $table->date('approval_date');
            $table->string('approval_id', 191)->collation('utf8mb4_unicode_ci');
            $table->date('cancelled_date')->nullable();
            $table->string('canceled_id', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('cancelled_amt')->nullable();
            $table->double('total_cost')->nullable();
            $table->double('total_retail')->nullable();
            $table->double('outstand_cost')->nullable();
            $table->double('total_discount')->default(0);
            $table->string('comment_desc', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->smallInteger('buyer')->nullable();
            $table->integer('sku');
            $table->string('upc', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->double('unit_cost')->nullable();
            $table->integer('unit_retail')->nullable();
            $table->double('vat_cost')->default(0);
            $table->double('luxury_cost')->default(0);
            $table->integer('qty_ordered')->default(0);
            $table->integer('qty_received')->nullable();
            $table->integer('unit_discount')->default(0);
            $table->double('unit_permanent_discount')->default(0);
            $table->string('sku_desc', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('purchase_uom', 20)->collation('utf8mb4_unicode_ci')->nullable();
            $table->smallInteger('supp_pack_size')->nullable();
            $table->smallInteger('permanent_disc_pct')->nullable();
            $table->string('tag_code', 5)->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_po');
    }
};
