<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCarrito extends Model
{
    use HasFactory;
    protected $table = 'detalle_carritos';
    protected $primaryKey = 'detalle_carrito_id';
    protected $fillable = [
        'carrito_id',
        'producto_detalle_id',
        'cantidad',
        'precio_con_descuento',
        'subtotal',
        'costo_envio',
        'direccion_envio',
        'ciudad_envio',
    ];
    public function carrito()
    {
        return $this->belongsTo(Carritos::class, 'carrito_id', 'carrito_id');
    }

    public function productoDetalle()
    {
        return $this->belongsTo(Productos_detalles::class, 'producto_detalle_id', 'producto_detalle_id');
    }
}
