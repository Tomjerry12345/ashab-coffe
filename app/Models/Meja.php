<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $fillable = ['publicId', 'nomorMeja', 'qrCode'];
}
