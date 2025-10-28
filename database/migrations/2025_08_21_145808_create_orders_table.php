<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meja_id');
            $table->string('order_key'); // ID unik per transaksi
            $table->string('nama');      // Nama makanan/minuman
            $table->decimal('harga', 10, 2); // Harga per item
            $table->integer('jumlah');    // Qty
            $table->text('catatan');
            $table->string('payment_method');
            $table->enum('status', ['pending', 'cooking', 'finished', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index('meja_id');
            $table->index('order_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
