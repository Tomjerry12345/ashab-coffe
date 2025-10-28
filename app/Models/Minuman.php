<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minuman extends Model
{
    use HasFactory;

    protected $table = 'minumans'; // karena plural irregular
    protected $fillable = [
        'publicIdMinuman',
        'publicIdModel3dMinuman',
        'namaMinuman',
        'hargaMinuman',
        'stokMinuman',
        'fotoMinuman',
        'model3D',
    ];
}
