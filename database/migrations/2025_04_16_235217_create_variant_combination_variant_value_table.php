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
        Schema::create('variant_combination_variant_value', function (Blueprint $table) {
            $table->id();
            // pastikan kolom yang digunakan sebagai foreign key sudah ada dan memiliki tipe data yang sesuai
            $table->foreignId('variant_combination_id')->constrained('product_variant_combinations')->onDelete('cascade');
            $table->foreignId('variant_option_id')->constrained('variant_values')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_combination_variant_value');
    }
};
