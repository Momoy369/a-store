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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Relasi dengan tabel products
            $table->string('name'); // Nama varian, misalnya 'Size', 'Color', dll
            $table->string('value'); // Nilai varian, misalnya 'Small', 'Red', dll
            $table->integer('stock'); // Stok varian produk
            $table->decimal('price', 10, 2)->nullable(); // Harga tambahan untuk varian (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
