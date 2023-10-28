<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $productos = Producto::select(['id', 'nombre', 'descripcion', 'codigo', 'precio', 'imagen'])
            ->toBase()
            ->where('preparado', 1)
            ->get();

        return view('home', [
            'productos' => $productos,
        ]);
    }
}
