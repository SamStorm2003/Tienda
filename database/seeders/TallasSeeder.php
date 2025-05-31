<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TallasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tallas')->insert([
            ['nombre' => 'S'],
            ['nombre' => 'M'],
            ['nombre' => 'L'],
            ['nombre' => 'XXXS'],
            ['nombre' => 'XXS'],
            ['nombre' => 'XS'],
            ['nombre' => 'XL'],
            ['nombre' => 'XXL'],
            ['nombre' => '3XL'],
            ['nombre' => '4XL'],
            ['nombre' => '5XL'],
            ['nombre' => 'Talla única'],
            ['nombre' => 'Niño 2'],
            ['nombre' => 'Niño 3'],
            ['nombre' => 'Niño 4'],
            ['nombre' => 'Niño 5'],
            ['nombre' => 'Niño 6'],
            ['nombre' => 'Niño 7'],
            ['nombre' => 'Niño 8'],
            ['nombre' => 'Niño 9'],
            ['nombre' => 'Niño 10'],
            ['nombre' => 'Niño 11'],
            ['nombre' => 'Niño 12'],
            ['nombre' => 'Niño 13'],
            ['nombre' => 'Niño 14'],
            ['nombre' => 'Joven S'],
            ['nombre' => 'Joven M'],
            ['nombre' => 'Joven L'],
            ['nombre' => 'Joven XL'],
            ['nombre' => 'Joven XXL'],
        ]);
    }
}
