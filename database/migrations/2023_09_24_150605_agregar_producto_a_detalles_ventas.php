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
        Schema::table('ventas_detalles', function (Blueprint $table) {
            $table->unsignedBigInteger('id_producto')->nullable()->after('descripcion');

            $table->foreign('id_producto')
                ->references('id')
                ->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas_detalles', function (Blueprint $table) {
            $table->dropForeign(['id_producto']);

            $table->dropColumn('id_producto');
        });
    }
};
