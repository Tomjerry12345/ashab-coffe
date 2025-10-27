<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'meja_id',
        'order_key',
        'nama',
        'harga',
        'jumlah',
        'catatan',
        "payment_method",
        'status'
    ];
}
