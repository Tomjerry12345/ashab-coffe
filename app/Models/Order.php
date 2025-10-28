<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'publicId',
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
