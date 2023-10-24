<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReporteVentaController extends Controller
{
    public function index(Request $request)
    {
        $tipo = 'dia';
        $fecha = Carbon::today();
        $fecha_antes = Carbon::today();
        $fecha_despues = Carbon::today();
        $total_efectivo = 0;
        $total_pagado = 0;
        $total_transferencia = 0;
        $paginacion = 10;

        $ventaQuery = $ventas = Venta::query()
            ->select([
                'nombre',
                'descripcion',
                'fecha',
                'fecha_pago',
                'forma_pago',
                'cantidad',
                'total',
                'pagado',
            ])
            ->where('pagado', 1);


        if ($request->has('tipo') && $request->filled('tipo')) {
            $tipo = $request->input('tipo');
        }

        if ($request->has('fecha') && $request->filled('tipo')) {
            $fecha = Carbon::parse($request->input('fecha'));
        }

        if ($request->has('paginacion') && $request->filled('paginacion')) {
            $paginacion = $request->input('paginacion');
        }

        if ($tipo == 'dia') {
            $fecha_antes = $fecha->clone();
            $fecha_despues = $fecha->clone();
        }

        if ($tipo == 'semanal') {
            $fecha_antes =  $fecha->clone()->startOfWeek();
            $fecha_despues = $fecha->clone()->endOfWeek();
        }

        if ($tipo == 'mensual') {
            $fecha_antes = $fecha->clone()->subMonth();
            $fecha_despues = $fecha->clone()->addMonth();
        }

        if ($tipo == 'anual') {
            $fecha_antes = $fecha->clone()->subYear();
            $fecha_despues = $fecha->clone()->addYear();
        }

        $ventas = (clone $ventaQuery)
            ->whereBetween('fecha_pago', [
                $fecha_antes,
                $fecha_despues,
            ])
            ->orderBy('fecha_pago', 'desc')
            ->paginate($paginacion);


        $ventas->appends($request->all());

        $total_pagado = (clone $ventaQuery)
            ->whereBetween('fecha_pago', [
                $fecha_antes,
                $fecha_despues,
            ])
            ->sum('total');

        $total_efectivo = (clone $ventaQuery)
            ->where('forma_pago', 'Efectivo')
            ->whereBetween('fecha_pago', [
                $fecha_antes,
                $fecha_despues,
            ])
            ->sum('total');

        $total_transferencia = (clone $ventaQuery)
            ->where('forma_pago', 'Transferencia')
            ->whereBetween('fecha_pago', [
                $fecha->clone()->startOfYear()->format('Y-m-d H:i:s'),
                $fecha->clone()->endOfYear()->format('Y-m-d H:i:s')
            ])
            ->sum('total');

        return Inertia::render('Ventas/ReporteVenta/Index', [
            'total_pagado'          => $total_pagado,
            'total_efectivo'        => $total_efectivo,
            'total_transferencia'   => $total_transferencia,
            'tipo'                  => $tipo,
            'ventas'                => $ventas,
            'fecha'                 => $fecha,
            'fecha_antes'           => $fecha_antes,
            'fecha_despues'         => $fecha_despues,
            'paginacion'            => $paginacion,
        ]);
    }
}
