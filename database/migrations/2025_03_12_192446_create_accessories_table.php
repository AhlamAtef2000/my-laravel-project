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
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // accessory name
            $table->decimal('price', 8, 2); // price
            $table->enum('type', ['brooch', 'embroidery', 'patch', 'badge'])->default('brooch');
            $table->timestamps();

            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // foreign key for product
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
