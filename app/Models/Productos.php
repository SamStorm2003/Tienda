<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;
    protected $primaryKey = 'producto_id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'sku',
        'precio',
        'descuento_porcentaje',
        'imagen_url',
        'subcategoria_id',
        'proveedor_id',
        'temporada',
        'genero',
    ];

    public function subcategoria()
    {
        return $this->belongsTo(Subcategorias::class, 'subcategoria_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'proveedor_id', 'proveedor_id');
    }
}
