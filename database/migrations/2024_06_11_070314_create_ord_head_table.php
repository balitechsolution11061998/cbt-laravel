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
        Schema::create('ordhead', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_no');
            $table->integer('ship_to');
            $table->integer('supplier');
            $table->integer('terms');
            $table->string('status_ind', 191)->nullable();
            $table->date('written_date')->nullable();
            $table->date('not_before_date')->nullable();
            $table->date('not_after_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('release_date')->nullable();
            $table->string('approval_id', 191)->nullable();
            $table->date('cancelled_date')->nullable();
            $table->string('canceled_id', 191)->nullable();
            $table->integer('cancelled_amt')->nullable();
            $table->integer('total_cost');
            $table->bigInteger('total_retail')->unsigned()->nullable();
            $table->integer('outstand_cost')->nullable();
            $table->integer('total_discount')->nullable();
            $table->string('comment_desc', 191)->nullable();
            $table->integer('buyer')->nullable();
            $table->string('status', 255)->nullable();
            $table->text('reason')->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordhead');
    }
};
