<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
    {
        $productos = Producto::select(['id', 'nombre', 'descripcion', 'codigo', 'precio', 'imagen'])
            ->toBase()
            ->where('visible', 1)
            ->get();

        $defaultProducto = asset('img/default-product.png');

        return view('home', [
            'productos'         => $productos,
            'defaultProducto'   => $defaultProducto,
        ]);
    }
}
