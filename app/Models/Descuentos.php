<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descuentos extends Model
{
    use HasFactory;
    protected $primaryKey = 'descuento_id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'valor',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];
}
