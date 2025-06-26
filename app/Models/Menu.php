<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'nama_menu', 'deskripsi', 'harga', 'stok', 'image', 'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function menuTransactions()
    {
        return $this->hasMany(MenuTransaction::class);
    }
}
