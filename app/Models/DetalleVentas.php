<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVentas extends Model
{
    use HasFactory;
    protected $table = 'detalle_ventas';
    protected $primaryKey = 'detalle_venta_id';
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'precio_con_descuento',
        'subtotal',
        'costo_envio',
        'direccion_envio',
        'ciudad_envio',
    ]; 

    public function venta()
    {
        return $this->belongsTo(Ventas::class, 'venta_id', 'venta_id');
    }
    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id', 'producto_id');
    }
}
