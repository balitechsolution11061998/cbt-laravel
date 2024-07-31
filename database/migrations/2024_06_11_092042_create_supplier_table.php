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


        Schema::create('supplier', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('supp_code')->nullable();
            $table->string('supp_name', 191)->nullable();
            $table->integer('terms')->nullable();
            $table->string('contact_name', 191)->nullable();
            $table->string('contact_phone', 191)->nullable();
            $table->string('contact_fax', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('address_1', 191)->nullable();
            $table->string('address_2', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('post_code', 255)->nullable();
            $table->char('tax_ind', 191)->nullable();
            $table->string('tax_no', 191)->nullable();
            $table->char('retur_ind', 191)->nullable();
            $table->char('consig_ind', 191)->nullable();
            $table->char('status', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
