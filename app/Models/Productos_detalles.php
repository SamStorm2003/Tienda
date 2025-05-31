<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos_detalles extends Model
{
    use HasFactory;

    protected $table = 'productos_detalles';
    protected $primaryKey = 'producto_detalle_id';
    protected $fillable = [
        'producto_id',
        'color_id',
        'talla_id',
        'stock',
        'fecha_actualizacion'
    ];
    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id', 'producto_id');
    }

    public function color()
    {
        return $this->belongsTo(Colores::class, 'color_id', 'color_id');
    }

    public function talla()
    {
        return $this->belongsTo(Tallas::class, 'talla_id', 'talla_id');
    }
}
