<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Image;
use Inertia\Inertia;

class ProductosController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::query()
            ->when($request->input('search'), function ($q, $search) {
                $q->where('nombre', 'like', "%{$search}%");
            })
            ->paginate($request->input('perPage') ?? 10);

        return Inertia::render('Admin/Producto/Index', [
            'productos'     => $productos,
            'filters'       => $request->only(['search', 'perPage']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Producto/Create', []);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre'            => 'required|unique:productos,nombre',
            'codigo'            => 'required|unique:productos,codigo',
            'precio'            => 'required',
            'descripcion'       => 'nullable',
            'imagen'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $data = $this->validate($request, $rules);

        $producto = Producto::create($data);

        if ($request->hasFile('imagen')) {
            $image = $request->file('imagen');
            $imgFile = Image::make($image->getRealPath());

            $imgFile->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
            });

            $producto->imagen = (string) $imgFile->encode('data-url');
            $producto->save();
        }

        return redirect()->route('admin.productos.index')->with([
            'message' => 'Registro agregado correctamente'
        ]);
    }

    public function edit(Producto $producto)
    {
        return Inertia::render('Admin/Producto/Edit', [
            'producto' => $producto
        ]);
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre'            => "required|unique:productos,nombre,{$producto->id}",
            'codigo'            => "required|unique:productos,codigo,{$producto->id}",
            'precio'            => 'required',
            'descripcion'       => 'nullable',
            'imagen'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $producto->update($data);

        if ($request->hasFile('imagen')) {
            $image = $request->file('imagen');
            $imgFile = Image::make($image->getRealPath());

            $imgFile->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
            });

            $producto->imagen = (string) $imgFile->encode('data-url');
            $producto->save();
        } else {
            if ($request->has('remover_imagen')) {
                $producto->imagen = null;
                $producto->save();
            }
        }

        return redirect()->route('admin.productos.index')->with([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('admin.productos.index')->with([
            'message' => "Registro eliminado correctamente"
        ]);
    }
}
