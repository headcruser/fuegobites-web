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
        Schema::create('cotizaciones_partidas', function (Blueprint $table) {
            $table->id();
            $table->integer('posicion')->default(0);
            $table->unsignedBigInteger('id_cotizacion')->nullable();
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->text('concepto')->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->string('moneda')->nullable();
            $table->timestamps();

            // $table->foreign('id_cotizacion')->references('id')->on('cotizaciones');
            $table->foreign('id_producto')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_partidas');
    }
};
