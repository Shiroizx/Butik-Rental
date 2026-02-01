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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->foreignId('return_id')->nullable()->constrained('returns')->onDelete('cascade');
            $table->enum('jenis_denda', ['keterlambatan', 'kerusakan', 'kehilangan', 'lainnya']);
            $table->decimal('jumlah_denda', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->enum('status_denda', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->date('tanggal_bayar_denda')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
