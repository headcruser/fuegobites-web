<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Producto::query()->exists()) {
            Producto::factory()->createMany([
                [
                    'nombre'        => 'VASO NORMAL',
                    'codigo'        => 'VN',
                    'descripcion'   => 'Vaso con diferentes gomitas',
                    'precio'        => 25,
                    'visible'       => true,
                ],
                [
                    'nombre'        => 'VASO LOCO',
                    'codigo'        => 'VL',
                    'descripcion'   => 'Vaso con gomitas, chicolines, skincles rellenos',
                    'precio'        => 35,
                    'visible'       => true,
                ],
                [
                    'nombre'        => 'VASO GUMMY',
                    'codigo'        => 'VL',
                    'descripcion'   => 'Vaso con solo con un tipo de gomitas',
                    'precio'        => 30,
                    'visible'       => true,
                ],
                [
                    'nombre'        => 'VASO SKITTLES',
                    'codigo'        => 'VS',
                    'descripcion'   => 'Vaso con solo Skittles',
                    'precio'        => 35,
                    'visible'       => true,
                ],
                [
                    'nombre'        => 'VASO CACAHUATE',
                    'codigo'        => 'VC',
                    'descripcion'   => 'Vaso con cacahuates japoneses',
                    'precio'        => 35,
                    'visible'       => true,
                ],
            ]);
        }
    }
}
