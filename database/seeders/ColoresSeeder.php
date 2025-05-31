<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colores')->insert([
            ['nombre' => 'Azul Marino'],
            ['nombre' => 'Rojo Vibrante'],
            ['nombre' => 'Negro Clásico'],
        ]);
    }
}
