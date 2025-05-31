<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleIngresos extends Model
{
    use HasFactory;
    protected $table = 'detalle_ingresos';
    protected $primaryKey = 'detalle_ingreso_id';
    protected $fillable = [
        'ingreso_id',
        'producto_id',
        'color_id',
        'talla_id',
        'vendedor_id',
        'cantidad',
        'precio_unitario',
        'precio_con_descuento',
        'subtotal',
    ];
    public function ingreso()
    {
        return $this->belongsTo(Ingresos::class, 'ingreso_id', 'ingreso_id');
    }

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
