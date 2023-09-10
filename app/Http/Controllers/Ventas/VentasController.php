<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VentasController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Venta::query()->where('pagado', 0)->get();

        return Inertia::render('Ventas/Index', [
            'ventas' => $ventas
        ]);
    }

    public function store(Request $request)
    {
        $venta = new Venta();

        $venta->fill([
            'nombre'        => $request->input('nombre'),
            'descripcion'   => $request->input('descripcion'),
            'fecha'         => now(),
            'total'         => $request->input('total'),
            'pagado'        => $request->input('pagado'),
        ]);

        $venta->save();

        return to_route('ventas.registro.index');
    }
}
