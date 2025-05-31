<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategorias extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'categoria_id'];
    protected $primaryKey = 'categoria_id';

    public function productos()
    {
        return $this->hasMany(Productos::class, 'subcategoria_id');
    }
    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }
}
