<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    //
      use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_transaksi', 'metode_pembayaran', 'status', 'catatan', 'total_harga', 'snap_url'
    ];

    public function menuTransactions()
    {
        return $this->hasMany(MenuTransaction::class);
    }
}
