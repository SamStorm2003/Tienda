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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('notificacion_id'); 
            $table->unsignedBigInteger('usuario_id'); 
            $table->text('mensaje'); 
            $table->timestamp('fecha_envio')->useCurrent(); 
            $table->boolean('leido')->default(false); 
            $table->foreign('usuario_id')->references('id')->on('users'); 

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
