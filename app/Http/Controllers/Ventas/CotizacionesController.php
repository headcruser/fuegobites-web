<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionPartida;
use App\Notifications\EnviarCotizacionCliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

use PDF;
use Auth;
use Carbon\Carbon;

class CotizacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:gestionar_cotizaciones']);
    }

    public function index(Request $request)
    {
        $cotizaciones = Cotizacion::query()
            ->when($request->input('status'), function ($q, $status) {
                $q->where('cotizaciones.status', $status);
            })
            ->with(['cliente', 'vendedor'])
            ->paginate($request->input('perPage') ?? 10);


        return Inertia::render('Ventas/Cotizaciones/Index', [
            'cotizaciones'  => $cotizaciones,
            'filters'       => $request->only(['search', 'perPage']),
        ]);
    }

    public function create()
    {
        $folio = Cotizacion::whereYear('fecha', date('Y'))->count() + 1;

        $cotizacion = Cotizacion::create([
            'folio'         => $folio,
            'forma_pago'    => 'Contado',
            'fecha'         => date('Y-m-d'),
            'nombre'        => 'cotizacion_' . $folio,
            'id_asesor'     => Auth::id(),
            'status'        => 'Nueva',
            'moneda'        => 'MXN',
            'saludo'        => 'Estimado(a)',
            'descripcion'   => 'Le envío la cotización solicitada',
            'pie'           => 'Esperando que sea de su agrado quedo pendiente ante cualquier duda o comentario',
            'observaciones' => 'Se entrega el 50% de anticipo',
            'tipo_cambio'   => 0,
        ]);

        return Inertia::location(route('ventas.cotizaciones.edit', $cotizacion->id));
    }

    public function edit(Cotizacion $cotizacion)
    {
        $cotizacion->load(['vendedor', 'cliente', 'partidas']);


        return Inertia::render('Ventas/Cotizaciones/Edit', [
            'cotizacion'                => $cotizacion,
            'company_name'              => config('company.name'),
            'company_web'               => config('company.web'),
        ]);
    }

    public function show(Cotizacion $cotizacion)
    {
        $cotizacion->load(['partidas', 'vendedor']);

        $pdf = PDF::loadView('ventas.cotizaciones.show_pdf', [
            'cotizacion' => $cotizacion
        ]);

        return $pdf->stream("{$cotizacion->folio_completo}{$cotizacion->folio} {$cotizacion->nombre_cotizacion}.pdf");
    }

    public function update(Cotizacion $cotizacion, Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required'
        ]);

        if ($request->has('fecha')) {
            $request->merge([
                'fecha' => Carbon::createFromFormat('d/m/Y', $request->input('fecha')),
            ]);
        }

        $cotizacion->fill($request->except('_token'));
        $cotizacion->save();

        return Inertia::location(route('ventas.cotizaciones.edit', $cotizacion->id));
    }

    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();

        return Inertia::location(route('ventas.cotizaciones.index'));
    }

    public function cambiar_cliente(Cotizacion $cotizacion, Request $request)
    {
        $request->validate([
            'id_cliente' => ['required', 'exists:clientes,id']
        ]);

        $cotizacion->id_cliente = $request->input('id_cliente');
        $cotizacion->save();

        return Inertia::location(route('ventas.cotizaciones.edit', $cotizacion->id));
    }

    public function agregar_partida(Request $request, Cotizacion $cotizacion)
    {
        $rules = [
            'concepto'      => 'required',
            'descripcion'   => 'required',
            'cantidad'      => ['required', 'min:1'],
            'precio'        => ['required', 'min:0'],
            'moneda'        => 'required',
        ];

        $this->validate($request, $rules);

        $no_partidas = $cotizacion->partidas->count();

        $cotizacion->partidas()->create([
            'posicion'      => $no_partidas + 1,
            'concepto'      => $request->input('concepto'),
            'descripcion'   => $request->input('descripcion'),
            'cantidad'      => $request->input('cantidad'),
            'precio'        => $request->input('precio'),
            'total'         => $request->input('cantidad') * $request->input('precio'),
            'moneda'        => $request->input('moneda'),
        ]);

        $cotizacion->total = $cotizacion->partidas()->sum('total');
        $cotizacion->save();

        return Inertia::location(route('ventas.cotizaciones.edit', $cotizacion->id));
    }

    public function actualizar_partida(Request $request, CotizacionPartida $partida)
    {
        $partida->fill([
            'concepto'      => $request->input('concepto'),
            'descripcion'   => $request->input('descripcion'),
            'cantidad'      => $request->input('cantidad'),
            'precio'        => $request->input('precio'),
            'total'         => $request->input('cantidad') * $request->input('precio'),
            'moneda'        => $request->input('moneda'),
        ]);

        $partida->save();

        $partida->load(['cotizacion']);

        $cotizacion = $partida->cotizacion;
        $cotizacion->total = $cotizacion->partidas->sum('total');
        $cotizacion->save();

        return Inertia::location(route('ventas.cotizaciones.edit', $cotizacion->id));
    }

    public function eliminar_partida(CotizacionPartida $partida)
    {
        $cotizacion = $partida->cotizacion;

        $partida->delete();

        $cotizacion->total = $cotizacion->partidas()->sum('total');
        $cotizacion->save();

        return Inertia::location(route('ventas.cotizaciones.edit', $cotizacion->id));
    }


    public function enviar_cotizacion(Request $request, Cotizacion $cotizacion)
    {
        $rules = [
            'email'         => 'required|email',
            'email_cc'      => 'nullable|array'
        ];


        $this->validate($request, $rules);

        $cotizacion->load(['cliente', 'vendedor']);

        $cliente = new Cliente();
        $cliente->nombre = $request->input('nombre');
        $cliente->email = $request->email;

        $pdf = PDF::loadView('ventas.cotizaciones.show_pdf', ['cotizacion' => $cotizacion]);

        $contactos = array_merge([], $request->input('email_cc', []));

        $adjuntos = [
            "cotizacion_{$cotizacion->folio}.pdf" => $pdf->output()
        ];

        $cliente->notify(new EnviarCotizacionCliente(
            $cotizacion,
            $contactos,
            $adjuntos,
        ));

        return Inertia::location(route('ventas.cotizaciones.edit', $cotizacion->id));
    }
}
