<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::query()
            ->when($request->input('search'), function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
        ->paginate($request->input('perPage') ?? 10);

        return Inertia::render('Admin/Roles/Index', [
            'roles'     => $roles,
            'filters'   => $request->only(['search', 'perPage']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Roles/Create', []);
    }

    public function store(Request $request)
    {
        $rules =[
            'name'          => 'required|unique:roles,name',
            'description'   => 'required',
        ];

        $data = $this->validate($request,$rules);

        Role::create($data);

        return redirect()->route('admin.roles.index')->with([
            'message' => 'Registro agregado correctamente'
        ]);
    }

    public function edit(Role $role)
    {
        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $rules = [
            'name'          => "required|unique:roles,name,{$role->id}",
            'description'   => "required",
        ];

        $data = $this->validate($request,$rules);

        $role->update($data);

        return redirect()->route('admin.roles.index')->with([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(Role $role,Request $request)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with([
            'message' => "Registro eliminado correctamente"
        ]);
    }
}
