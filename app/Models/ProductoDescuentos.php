<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoDescuentos extends Model
{
    use HasFactory;
    protected $table = 'productos_descuentos';
    protected $primaryKey = 'producto_descuento_id';

    protected $fillable = [
        'producto_id',
        'descuento_id',
        'fecha_asignacion',
    ];

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id', 'producto_id');
    }
    public function descuento()
    {
        return $this->belongsTo(Descuentos::class, 'descuento_id', 'descuento_id');
    }
}
