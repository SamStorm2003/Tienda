<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'telefono', 'correo', 'direccion', 'ciudad'];
    protected $primaryKey = 'proveedor_id';

    public function productos()
    {
        return $this->hasMany(Productos::class, 'proveedor_id');
    }
}
