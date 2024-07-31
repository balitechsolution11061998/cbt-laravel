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
        Schema::create('rcvhead', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('receive_no');
            $table->date('receive_date');
            $table->date('created_date');
            $table->string('receive_id', 191)->collation('utf8mb4_unicode_ci');
            $table->integer('order_no');
            $table->string('ref_no', 191)->collation('utf8mb4_unicode_ci');
            $table->string('order_type', 191)->collation('utf8mb4_unicode_ci');
            $table->string('status_ind', 191)->collation('utf8mb4_unicode_ci');
            $table->date('approval_date');
            $table->string('approval_id', 191)->collation('utf8mb4_unicode_ci');
            $table->integer('store');
            $table->string('store_name', 191)->collation('utf8mb4_unicode_ci');
            $table->integer('supplier');
            $table->string('sup_name', 191)->collation('utf8mb4_unicode_ci');
            $table->string('comment_desc', 191)->collation('utf8mb4_unicode_ci');
            $table->string('status', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->bigInteger('sub_total')->nullable();
            $table->bigInteger('sub_total_vat_cost')->nullable();
            $table->double('average_service_level')->nullable();;
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rcvhead');
    }
};
