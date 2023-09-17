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
        Schema::create('ventas_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_venta');
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->integer('cantidad')->default(0);
            $table->decimal('precio', 16, 2)->default(0);
            $table->decimal('total', 16, 2)->default(0);
            $table->boolean('pagado')->default(false);
            $table->boolean('completado')->default(false);
            $table->timestamps();

            $table->foreign('id_venta')
                ->references('id')
                ->on('ventas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_detalles');
    }
};
