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
        Schema::table('menu_transactions', function (Blueprint $table) {
            //
         $table->unsignedTinyInteger('level_pedas')->nullable()->after('jumlah'); // Tambah kolom baru
        $table->string('jenis')->nullable()->after('level_pedas');
        });
    }
    
    /**
     * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('menu_transactions', function (Blueprint $table) {
             $table->dropColumn('level_pedas');
        $table->dropColumn('jenis');
            //
        });
    }
};
