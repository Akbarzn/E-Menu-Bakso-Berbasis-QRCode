<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    //
    public function index(){
        // $menus = Menu::where('status',true)->get();
        // return view('public.menu',compact('menus'));
    
       $makanan = Menu::whereHas('category', fn ($q) => $q->where('nama_kategori', 'makanan'))
        ->where('status', true)
        ->get();

    $minuman = Menu::whereHas('category', fn ($q) => $q->where('nama_kategori', 'minuman'))
        ->where('status', true)
        ->get();

    return view('public.menu', compact('makanan', 'minuman'));
    }
}
