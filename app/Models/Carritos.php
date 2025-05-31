<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carritos extends Model
{
    use HasFactory;
    protected $table = 'carritos';
    protected $primaryKey = 'carrito_id';
    protected $fillable = [
        'usuario_id',    
        'fecha_creacion', 
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
    public function detalles()
    {
        return $this->hasMany(DetalleCarrito::class, 'carrito_id', 'carrito_id');
    }
}
