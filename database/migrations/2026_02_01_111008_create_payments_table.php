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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->enum('metode_bayar', ['cash', 'transfer_bank', 'e-wallet', 'kartu_kredit', 'kartu_debit']);
            $table->enum('status_bayar', ['pending', 'lunas', 'gagal', 'refund'])->default('pending');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
