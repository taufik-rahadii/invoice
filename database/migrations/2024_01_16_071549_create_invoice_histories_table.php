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
        Schema::create('invoice_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->references('id')->on('invoices')->onDelete('CASCADE');
            $table->foreignUuid('invoice_status_id')->references('id')->on('invoice_statuses')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('remaining_total_payment')->unsigned()->nullable();
            $table->foreignId('modified_by')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_histories');
    }
};
