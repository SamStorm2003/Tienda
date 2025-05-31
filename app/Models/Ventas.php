<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;
    protected $table = 'ventas';
    protected $primaryKey = 'venta_id';
    protected $fillable = [
        'usuario_id',
        'fecha',
        'total',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
    public function detalles()
    {
        return $this->hasMany(DetalleVentas::class, 'venta_id', 'venta_id');
    }
}
