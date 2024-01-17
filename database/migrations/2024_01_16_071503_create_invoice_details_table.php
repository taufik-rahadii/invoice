<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->references('id')->on('invoices')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignUuid('product_id')->references('id')->on('products')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignUuid('product_price_id')->references('id')->on('product_prices')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('total_price')->unsigned();
            $table->bigInteger('qty')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
