<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lugares extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'categoria', 'latitud', 'longitud', 'url_foto', 'url_video'
    ];
}
