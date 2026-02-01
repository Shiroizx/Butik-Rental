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
        Schema::create('clothes_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clothes_id')->constrained('clothes')->onDelete('cascade');
            $table->enum('ukuran', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']);
            $table->integer('stok')->default(0);
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi ukuran pada satu pakaian
            $table->unique(['clothes_id', 'ukuran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothes_sizes');
    }
};
