<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingresos extends Model
{
    use HasFactory;
    protected $table = 'ingresos';
    protected $primaryKey = 'ingreso_id';
    protected $fillable = [
        'usuario_id',
        'sucursal_id',
        'fecha',
        'total',
        'estado',
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class, 'sucursal_id', 'sucursal_id');
    }
}
