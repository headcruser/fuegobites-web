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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->nullable();
            $table->date('fecha');

            $table->string('nombre')->nullable();
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_asesor')->nullable();

            $table->string('saludo')->default('Estimado');
            $table->longText('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('pie')->nullable();

            $table->string('moneda')->default('MXN')->nullable();
            $table->string('tipo_cambio')->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->decimal('total', 12, 2)->default(0);

            $table->string('status')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_asesor')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
