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
        Schema::create('transactions', function (Blueprint $table) {
          $table->id();
        $table->string('kode_transaksi')->unique();
        $table->enum('metode_pembayaran', ['manual', 'midtrans'])->default('manual');
        $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
        $table->text('catatan')->nullable();
        $table->decimal('total_harga', 12, 2)->default(0);
        $table->string('snap_url')->nullable();
        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
