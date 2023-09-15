<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Carbon\Carbon;
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
        $request->validate([
            'nombre'        => 'required',
            'total'         => 'numeric',
            'cantidad'      => 'numeric',
            'forma_pago'    => 'required',
            'fecha'         => 'date_format:d/m/Y',
            'descripcion'   => 'nullable',
        ]);


        $venta = new Venta();

        $venta->fill([
            'nombre'        => $request->input('nombre'),
            'descripcion'   => $request->input('descripcion'),
            'fecha'         => Carbon::createFromFormat('d/m/Y', $request->input('fecha')),
            'total'         => $request->input('total'),
            'cantidad'      => $request->input('cantidad'),
            'pagado'        => $request->input('pagado'),
            'forma_pago'    => $request->input('forma_pago'),
            'fecha_pago'    => $request->input('pagado') ? now() : null
        ]);

        $venta->save();

        return redirect()->route('ventas.registro.index')->with([
            'message' => "Registro creado correctamente"
        ]);
    }


    public function update(Venta $venta, Request $request)
    {
        $request->validate([
            'nombre'        => 'required',
            'total'         => 'numeric',
            'cantidad'      => 'numeric',
            'forma_pago'    => 'nullable',
            'fecha'         => 'date_format:d/m/Y',
            'descripcion'   => 'nullable',
        ]);

        $venta->fill([
            'nombre'        => $request->input('nombre'),
            'descripcion'   => $request->input('descripcion'),
            'fecha'         => Carbon::createFromFormat('d/m/Y', $request->input('fecha')),
            'total'         => $request->input('total'),
            'cantidad'      => $request->input('cantidad'),

            'pagado'        => $request->input('pagado'),
            'forma_pago'    => $request->input('pagado') ? $request->input('forma_pago') : null,
            'fecha_pago'    => $request->input('pagado') ? now() : null
        ]);

        $venta->save();

        return redirect()->route('ventas.registro.index')->with([
            'message' => "Registro actualizado correctamente"
        ]);
    }

    public function destroy(Venta $venta)
    {
        $venta->detalles()->delete();

        $venta->delete();

        return redirect()
            ->route('ventas.registro.index')
            ->with([
                'message' => 'Registro eliminado correctamente'
            ]);
    }
}
