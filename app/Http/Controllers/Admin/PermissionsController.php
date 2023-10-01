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

    public function index()
    {
        $roles = Role::select('id', 'name', 'description')
            ->with(['permissions' => function ($query) {
                $query->select(['id', 'name', 'description']);
            }])
            ->get();

        return Inertia::render('Admin/Perms/Index', [
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
