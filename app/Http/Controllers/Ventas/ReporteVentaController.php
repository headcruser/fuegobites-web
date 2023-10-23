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
        $tipo = '';
        $fecha = Carbon::today();
        $fecha_antes = Carbon::today();
        $fecha_despues = Carbon::today();
        $total_efectivo = 0;
        $total_pagado = 0;
        $total_transferencia = 0;

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



        if (!$request->has('tipo') && !$request->filled('tipo')) {
            $tipo = 'dia';
        } else {
            $tipo = $request->input('tipo');
        }

        if ($tipo == 'dia') {
            if ($request->has('fecha')) {
                $fecha = Carbon::parse($request->input('fecha'));
            } else {
                $fecha = Carbon::today();
            }

            $ventas = (clone $ventaQuery)
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfDay()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfDay()->format('Y-m-d H:i:s')
                ])
                ->with(['detalles.producto'])
                ->orderBy('fecha_pago', 'desc')
                ->paginate(25);


            $ventas->appends($request->all());


            $total_pagado = (clone $ventaQuery)
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfDay()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfDay()->format('Y-m-d H:i:s')
                ])
                ->sum('total');


            $total_efectivo = (clone $ventaQuery)
                ->where('forma_pago', 'Efectivo')
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfDay()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfDay()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $total_transferencia = (clone $ventaQuery)
                ->where('forma_pago', 'Transferencia')
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfDay()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfDay()->format('Y-m-d H:i:s')
                ])
                ->sum('total');


            $fecha_antes = Carbon::createFromFormat('Y-m-d', $fecha->format('Y-m-d'))->subDay();
            $fecha_despues = Carbon::createFromFormat('Y-m-d', $fecha->format('Y-m-d'))->addDay();
        }

        if ($tipo == 'semanal') {
            if ($request->has('fecha')) {
                $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha);
            } else {
                $fecha = Carbon::today();
            };

            $ventas = (clone $ventaQuery)
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfWeek()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfWeek()->format('Y-m-d H:i:s')
                ])
                ->with(['detalles.producto'])
                ->orderBy('fecha_pago', 'desc')
                ->paginate(25);


            $ventas->appends($request->all());

            $total_pagado = (clone $ventaQuery)
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfWeek()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfWeek()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $total_efectivo = (clone $ventaQuery)
                ->where('forma_pago', 'Efectivo')
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfWeek()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfWeek()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $total_transferencia = (clone $ventaQuery)
                ->where('forma_pago', 'Transferencia')
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfWeek()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfWeek()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $fecha_antes = Carbon::createFromFormat('Y-m-d', $fecha->format('Y-m-d'))->subDays(7);
            $fecha_despues = Carbon::createFromFormat('Y-m-d', $fecha->format('Y-m-d'))->addDays(7);
        }

        if ($tipo == 'mensual') {
            if ($request->has('fecha')) {
                $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha);
            } else {
                $fecha = Carbon::today();
            }

            $ventas = (clone $ventaQuery)
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfWeek()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfWeek()->format('Y-m-d H:i:s')
                ])
                ->orderBy('fecha_pago', 'desc')
                ->paginate(25);


            $ventas->appends($request->all());

            $total_pagado = (clone $ventaQuery)
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfMonth()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfMonth()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $total_efectivo = (clone $ventaQuery)
                ->where('forma_pago', 'Efectivo')
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfMonth()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfMonth()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $total_transferencia = (clone $ventaQuery)
                ->where('forma_pago', 'Transferencia')
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfMonth()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfMonth()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $fecha_antes = Carbon::createFromFormat('Y-m-d', $fecha->format('Y-m-d'))->subMonth();
            $fecha_despues = Carbon::createFromFormat('Y-m-d', $fecha->format('Y-m-d'))->addMonth();
        }

        if ($tipo == 'anual') {
            if ($request->has('fecha')) {
                $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha);
            } else {
                $fecha = Carbon::today();
            }

            $ventas = (clone $ventaQuery)
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfYear()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfYear()->format('Y-m-d H:i:s')
                ])
                ->with(['detalles.producto'])
                ->orderBy('fecha_pago', 'desc')
                ->paginate(25);


            $ventas->appends($request->all());

            $total_pagado = (clone $ventaQuery)
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfYear()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfYear()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $total_efectivo = (clone $ventaQuery)
                ->where('forma_pago', 'Efectivo')
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfYear()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfYear()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $total_transferencia = (clone $ventaQuery)
                ->where('forma_pago', 'Transferencia')
                ->whereBetween('fecha_pago', [
                    $fecha->clone()->startOfYear()->format('Y-m-d H:i:s'),
                    $fecha->clone()->endOfYear()->format('Y-m-d H:i:s')
                ])
                ->sum('total');

            $fecha_antes = Carbon::createFromFormat('Y-m-d', $fecha->format('Y-m-d'))->subYear();
            $fecha_despues = Carbon::createFromFormat('Y-m-d', $fecha->format('Y-m-d'))->addYear();
        }

        return Inertia::render('Ventas/ReporteVenta/Index', [
            'total_pagado'          => $total_pagado,
            'total_efectivo'        => $total_efectivo,
            'total_transferencia'   => $total_transferencia,
            'tipo'                  => $tipo,
            'ventas'                => $ventas,
            'fecha'                 => $fecha,
            'fecha_antes'           => $fecha_antes,
            'fecha_despues'         => $fecha_despues,
        ]);
    }
}
