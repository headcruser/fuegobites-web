<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\VentaDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PedidosController extends Controller
{
    public function index(Request $request)
    {
        $starDay = $request->has('fecha_inicio')
            ? Carbon::parse($request->input('fecha_inicio'))
            : now()->addDay(1);

        $endDay = $request->has('fecha_fin')
            ? Carbon::parse($request->input('fecha_fin'))
            : now()->addDay(1);

        $pedidos = VentaDetalle::query()
            ->withWhereHas('venta', function ($q) use ($starDay, $endDay) {
                $q->where('pagado', 0);

                $q->where(function ($query) use ($starDay, $endDay) {
                    $query->whereDate('fecha', '>=', $starDay);
                    $query->whereDate('fecha', '<=', $endDay);
                });
            })
            ->paginate(25);

        $pedidos->appends($request->all());

        $totales = VentaDetalle::toBase()
            ->select(
                DB::raw('sum(ventas_detalles.cantidad) as cantidad'),
                DB::raw('sum(ventas_detalles.total) as total'),
                'productos.nombre',
                'productos.codigo'
            )
            ->join('productos', 'productos.id', '=', 'ventas_detalles.id_producto')
            ->join('ventas', 'ventas.id', '=', 'ventas_detalles.id_venta')
            ->where('ventas.pagado', 0)
            ->where(function ($query) use ($starDay, $endDay) {
                $query->whereDate('fecha', '>=', $starDay);
                $query->whereDate('fecha', '<=', $endDay);
            })
            ->groupBy('productos.nombre', 'productos.codigo')
            ->get();


        return Inertia::render('Ventas/Pedidos/Index', [
            'pedidos'       => $pedidos,
            'totales'       => $totales,
            'fecha_inicio'  => $starDay->format('Y/m/d'),
            'fecha_fin'     => $endDay->format('Y/m/d'),
        ]);
    }
}
