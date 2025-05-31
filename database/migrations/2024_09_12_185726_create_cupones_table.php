<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cupones', function (Blueprint $table) {
            $table->id('cupon_id'); 
            $table->string('codigo', 20)->unique();
            $table->text('descripcion')->nullable();
            $table->decimal('descuento', 5, 2)->nullable();
            $table->date('fecha_expiracion')->nullable();
            $table->enum('tipo_objeto', ['Producto', 'Categoria', 'Subcategoria'])->default('Producto');
            $table->integer('id_objeto')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupones');
    }
};
