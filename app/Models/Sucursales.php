<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model
{
    use HasFactory;
    protected $table = 'sucursales';
    protected $primaryKey = 'sucursal_id';
    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'telefono',
        'fecha_creacion',
    ];
    public function ingresos()
    {
        return $this->hasMany(Ingresos::class, 'sucursal_id', 'sucursal_id');
    }
}
