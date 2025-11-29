<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $fillable = ['publicId', 'nomorMeja', 'qrCode', 'status', 'terisi_sejak'];

    // ✅ Tambahkan ini buat casting
    protected $casts = [
        'terisi_sejak' => 'datetime',
    ];
}
