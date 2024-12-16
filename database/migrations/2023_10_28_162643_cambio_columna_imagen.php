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
        Schema::table('productos', function (Blueprint $table) {
            $table->longText('imagen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Crear una columna temporal para almacenar los datos convertidos
        Schema::table('productos', function (Blueprint $table) {
            $table->binary('imagen_temp')->nullable();
        });

        // Convertir los datos y transferirlos a la columna temporal
        DB::statement("
            UPDATE productos
            SET imagen_temp = CONVERT(varbinary(max), imagen)
        ");

        // Eliminar la columna original
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });

        // Renombrar la columna temporal
        Schema::table('productos', function (Blueprint $table) {
            $table->renameColumn('imagen_temp', 'imagen');
        });
    }
};
