<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colores extends Model
{
    use HasFactory;
    protected $table = 'colores';
    protected $primaryKey = 'color_id';
    protected $fillable = ['nombre'];
}
