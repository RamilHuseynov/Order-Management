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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Müştəri identifikatoru, silindikdə sifariş də silinir
            $table->decimal('total_amount', 10, 2)->default(0); // Sifarişin ümumi məbləği
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Sifarişin statusu
            $table->boolean('is_paid')->default(false); // Ödənilib-ödənilmədiyi
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
