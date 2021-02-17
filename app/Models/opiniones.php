<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class opiniones extends Model
{
    use HasFactory;
    protected $fillable = ['mejor_mes', 'alojamiento', 'accesibilidad', 'precio', 'clima', 'comida', 'conexion_internet', 'id_lugar'];
}
