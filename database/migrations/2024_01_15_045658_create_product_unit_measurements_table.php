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
        Schema::create('product_unit_measurements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('piece_per_unit');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignUuid('product_unit_id')->references('id')->on('product_unit_measurements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('product_unit_id');
            $table->dropColumn('product_unit_id');
        });
        Schema::dropIfExists('product_unit_measurements');
    }
};
