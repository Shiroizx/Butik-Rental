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
        Schema::table('clothes_sizes', function (Blueprint $table) {
            // Using raw statement to modify column type effectively
            // This changes ENUM to VARCHAR(50)
            $table->string('ukuran', 50)->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('clothes_sizes', function (Blueprint $table) {
             // Reverting might be tricky if data doesn't fit ENUM, but for fallback:
             // $table->enum('ukuran', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'])->change();
        });
    }
};
