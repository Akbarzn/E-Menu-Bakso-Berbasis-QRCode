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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('nama_menu');
        $table->text('deskripsi')->nullable();
        $table->decimal('harga', 10, 2);
        $table->integer('stok')->default(0);
        $table->string('image')->nullable();
        $table->boolean('status')->default(true);
        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
