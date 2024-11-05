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
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Sifariş identifikatoru
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Məhsul identifikatoru
            $table->integer('quantity'); // Məhsulun miqdarı
            $table->decimal('unit_price', 8, 2); // Vahid qiymət
            $table->decimal('total_price', 10, 2); // Ümumi qiymət
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
