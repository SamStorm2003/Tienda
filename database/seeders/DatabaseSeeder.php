<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
$this->call(RoleSeeder::class);
$this->call(EnviosSeeder::class);
$this->call(CuponSeeder::class);
$this->call(UserSeeder::class);
$this->call([
  CategoriasSeeder::class,
  SubcategoriasSeeder::class,
  ProveedoresSeeder::class,
  ColoresSeeder::class,
  TallasSeeder::class,
 // ProductosSeeder::class,
  //ProductosDetallesSeeder::class,
  DescuentosSeeder::class,
  //ProductosDescuentosSeeder::class,
]);
//        User::factory()->create([
  //          'name' => 'Test User',
    //        'email' => 'test@example.com',
      //  ]);
    }
}
