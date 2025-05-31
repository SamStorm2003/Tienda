<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envios extends Model
{
    use HasFactory;
    protected $table = 'envios';
    protected $primaryKey = 'id_envio';
    protected $fillable = [
        'ciudad',
        'pais',
        'precio',
    ];
}
