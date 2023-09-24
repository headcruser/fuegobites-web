<?php

namespace App\Http\Controllers\Ventas;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class VentasController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Venta::query()
            ->when($request->input('pagado'), function ($q, $value) {
                $q->whereIn('pagado', $value);
            }, function ($q) {
                $q->where('pagado', 0);
            })
            ->with(['detalles.producto'])
            ->paginate(25);

        $productos = Producto::toBase()->select(['id', 'nombre', 'codigo', 'descripcion', 'precio', 'imagen'])->get();

        return Inertia::render('Ventas/RegistroVenta/Index', [
            'ventas'        => $ventas,
            'productos'     => $productos,
            'filters'       => $request->only(['pagado']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required',
            'fecha'             => 'date_format:d/m/Y',
            'descripcion'       => 'nullable',
            'detalles_ventas'   => 'required|array',
            'forma_pago'        => 'nullable',
        ]);

        $detalles_ventas = $request->collect('detalles_ventas');

        if ($detalles_ventas->isEmpty()) {
            throw ValidationException::withMessages([
                'producto'   => 'Debes agregar un producto',
            ]);
        }

        try {
            DB::beginTransaction();

            $venta = new Venta();

            $venta->fill([
                'nombre'        => $request->input('nombre'),
                'descripcion'   => $request->input('descripcion'),
                'fecha'         => Carbon::createFromFormat('d/m/Y', $request->input('fecha')),
                'total'         => $detalles_ventas->sum('total'),
                'cantidad'      => $detalles_ventas->sum('cantidad'),
                'pagado'        => $request->input('pagado'),
                'forma_pago'    => $request->input('pagado') ? $request->input('forma_pago') : null,
                'fecha_pago'    => $request->input('pagado') ? now() : null
            ]);

            $venta->save();

            $venta->detalles()->createMany($detalles_ventas->toArray());


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw ValidationException::withMessages([
                'message'   => 'Ocurrio un error al insertar el pedido',
                'error'     => $th->getMessage(),
            ]);
        }


        return redirect()->route('ventas.registro.index')->with([
            'message' => "Registro creado correctamente"
        ]);
    }


    public function update(Venta $venta, Request $request)
    {
        $request->validate([
            'nombre'            => 'required',
            'forma_pago'        => 'nullable',
            'fecha'             => 'date_format:d/m/Y',
            'detalles_ventas'   => 'required|array',
            'descripcion'       => 'nullable',
        ]);

        $detalles_ventas = $request->collect('detalles_ventas');

        if ($detalles_ventas->isEmpty()) {
            throw ValidationException::withMessages([
                'producto'   => 'Debes agregar un producto',
            ]);
        }

        try {
            DB::beginTransaction();

            $venta->detalles()->delete();

            $venta->fill([
                'nombre'        => $request->input('nombre'),
                'descripcion'   => $request->input('descripcion'),
                'fecha'         => Carbon::createFromFormat('d/m/Y', $request->input('fecha')),

                'total'         => $detalles_ventas->sum('total'),
                'cantidad'      => $detalles_ventas->sum('cantidad'),

                'pagado'        => $request->input('pagado'),
                'forma_pago'    => $request->input('pagado') ? $request->input('forma_pago') : null,
                'fecha_pago'    => $request->input('pagado') ? now() : null
            ]);

            $venta->save();

            $venta->detalles()->createMany($detalles_ventas->toArray());

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw ValidationException::withMessages([
                'message'   => 'Ocurrio un error al insertar el pedido',
                'error'     => $th->getMessage(),
            ]);
        }

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
