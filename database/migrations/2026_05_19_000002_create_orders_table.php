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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_harga', 12, 2);
            $table->string('status_pesanan')->default('Pending'); // Pending, Diproses, Dikirim, Selesai, Dibatalkan
            $table->string('status_pembayaran')->default('Pending'); // Pending, Dibayar, Kadaluwarsa, Gagal
            $table->date('tanggal_pengiriman');
            $table->text('alamat');
            $table->text('catatan')->nullable();
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
