<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupones extends Model
{
    use HasFactory;
    protected $table = 'cupones';
    protected $primaryKey = 'cupon_id';
    protected $fillable = [
        'codigo',
        'descripcion',
        'descuento',
        'fecha_expiracion',
        'tipo_objeto',
        'id_objeto',
    ];

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'id_objeto', 'producto_id');
    }
}
