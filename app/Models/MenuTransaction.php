<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuTransaction extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'menu_id', 'jumlah', 'catatan'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
