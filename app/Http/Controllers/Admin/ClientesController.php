<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:gestionar_clientes']);
    }

    public function index(Request $request)
    {
        $clientes = Cliente::query()
            ->when($request->input('search'), function ($q, $search) {
                $q->where('nombre', 'like', "%{$search}%");
            })
            ->paginate($request->input('perPage') ?? 10);

        return Inertia::render('Admin/Clientes/Index', [
            'clientes'     => $clientes,
            'filters'       => $request->only(['search', 'perPage']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Clientes/Create', []);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre'  => 'required',
            'email'   => 'required',
        ];

        $this->validate($request, $rules);

        # CREAR CLIENTE
        $cliente = Cliente::create($request->except('_token'));

        return redirect()->route('admin.clientes.show', $cliente)->with([
            'message'  => 'Se creo el registro correctamente'
        ]);
    }

    public function show(Cliente $cliente)
    {
        return Inertia::render('Admin/Clientes/Panel', [
            'cliente' => $cliente
        ]);
    }

    public function edit(Cliente $cliente)
    {
        return Inertia::render('Admin/Clientes/Edit', [
            'cliente'       => $cliente,
        ]);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $rules = [
            'nombre'  => 'required',
        ];

        $this->validate($request, $rules);

        $cliente->fill($request->except('_token'));
        $cliente->save();

        return redirect()->route('admin.clientes.show', $cliente)->with([
            'message'  => 'Se actualizó el registro correctamente'
        ]);
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('admin.clientes.index')->with([
            'message' => "Registro eliminado correctamente"
        ]);
    }

    public function xeditable(Request $request)
    {
        $cliente = Cliente::find($request->pk);
        $cliente[$request->name] = $request->value;
        $cliente->save();

        return response()->json([
            'cliente' => $cliente
        ]);
    }

    public function select2(Request $request)
    {
        $nombre  = $request->input('search');
        $page = $request->input('page');

        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $collection = Cliente::where('nombre', 'like', "%{$nombre}%")
            ->skip($offset)
            ->take($resultCount)
            ->get();

        $count = Cliente::where('nombre', 'like', "%{$nombre}%")->orderBy('codigo')->count();
        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        return response()->json([
            'results'       => $collection,
            'pagination'    => [
                'more' => $morePages
            ]
        ]);
    }
}
