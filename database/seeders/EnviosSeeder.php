<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnviosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $envios = [
            ['ciudad' => 'La Paz', 'pais' => 'Bolivia', 'precio' => 20.00],
            ['ciudad' => 'Oruro', 'pais' => 'Bolivia', 'precio' => 30.00],
            ['ciudad' => 'Cochabamba', 'pais' => 'Bolivia', 'precio' => 25.00],
            ['ciudad' => 'Santa Cruz', 'pais' => 'Bolivia', 'precio' => 35.00],
            ['ciudad' => 'Potosí', 'pais' => 'Bolivia', 'precio' => 40.00],
            ['ciudad' => 'Tarija', 'pais' => 'Bolivia', 'precio' => 45.00],
            ['ciudad' => 'Sucre', 'pais' => 'Bolivia', 'precio' => 50.00],
            ['ciudad' => 'Beni', 'pais' => 'Bolivia', 'precio' => 55.00],
            ['ciudad' => 'Pando', 'pais' => 'Bolivia', 'precio' => 60.00],
            ['ciudad' => 'Riberalta', 'pais' => 'Bolivia', 'precio' => 70.00],
            ['ciudad' => 'Santiago', 'pais' => 'Chile', 'precio' => 80.00],
            ['ciudad' => 'São Paulo', 'pais' => 'Brasil', 'precio' => 90.00],
        ];

        DB::table('envios')->insert($envios);
    }
}
