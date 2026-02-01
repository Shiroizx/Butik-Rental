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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->foreignId('rental_detail_id')->constrained('rental_details')->onDelete('cascade');
            $table->date('tanggal_kembali_aktual');
            $table->enum('kondisi_baju', ['baik', 'kotor', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->text('catatan_kondisi')->nullable();
            $table->foreignId('diterima_oleh')->constrained('employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
