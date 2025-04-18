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
        Schema::create('product_variant_combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->json('variant_value_ids')->nullable()->default(null); // contoh: [1, 4]
            $table->integer('stock');
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('discount_type', ['fixed', 'percent'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_combinations');
    }
};
