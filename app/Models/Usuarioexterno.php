<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuarioexterno extends Model
{
    use HasFactory;
    protected $table = 'usuarioexternos';
    
    protected $fillable = [
        'nombre',
        'telefono',
        'ingreso_id',
    ];
}
