<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:gestionar_permisos']);
    }

    public function index(Request $request)
    {
        $perms = Permission::query()
            ->when($request->input('search'), function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->paginate($request->input('perPage') ?? 10);

        return Inertia::render('Admin/Perms/Index', [
            'perms' => $perms,
            'filters'   => $request->only(['search', 'perPage']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Perms/Create', []);
    }

    public function store(Request $request)
    {
        $rules = [
            'name'          => 'required',
            'description'   => 'required',
        ];

        $data = $this->validate($request, $rules);

        Permission::create($data);

        return redirect()->route('admin.perms.index')->with([
            'message' => 'Registro agregado correctamente'
        ]);
    }

    public function edit(Permission $permission)
    {
        return Inertia::render('Admin/Perms/Edit', [
            'permission' => $permission
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $rules = [
            'name'          => "required|unique:permissions,name,{$permission->id}",
            'description'   => "required",
        ];

        $data = $this->validate($request, $rules);

        $permission->update($data);

        return redirect()->route('admin.perms.index')->with([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(Permission $permission)
    {
        $permission->roles()->delete();
        $permission->delete();

        return redirect()->route('admin.perms.index')->with([
            'message' => "Registro eliminado correctamente"
        ]);
    }

    public function panel()
    {
        $roles = Role::select('id', 'name', 'description')
            ->with(['permissions' => function ($query) {
                $query->select(['id', 'name', 'description']);
            }])
            ->get();

        return Inertia::render('Admin/Perms/Panel', [
            'roles' => $roles,
            'perms' => Permission::get(),
        ]);
    }

    public function perms_save(Request $request)
    {
        $request->validate([
            'id_role' => ['required', 'numeric'],
            'id_perm' => ['required', 'numeric'],
        ]);


        $role = Role::findOrFail($request->input('id_role'));
        $permiso = Permission::findOrFail($request->input('id_perm'));

        if ($role->permissions->where('id', $request->input('id_perm'))->count() == 0) {
            $role->givePermissionTo($permiso);
        } else {
            $role->revokePermissionTo($permiso);
        }

        return $role->perms;
    }
}
