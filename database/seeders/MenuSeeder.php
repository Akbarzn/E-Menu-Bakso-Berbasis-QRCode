<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
          Menu::create([
        'category_id' => 1,
        'nama_menu' => 'Bakso Urat',
        'harga' => 15000,
        'stok' => 50,
        'status' => true,
    ]);

    Menu::create([
        'category_id' => 2,
        'nama_menu' => 'Es Teh Manis',
        'harga' => 5000,
        'stok' => 100,
        'status' => true,
    ]);
    }
}
