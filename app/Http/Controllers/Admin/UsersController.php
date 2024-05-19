<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Notifications\DatosAcceso;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:gestionar_usuarios']);
    }

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->input('search'), function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->paginate($request->input('perPage') ?? 10);


        foreach ($users as $user) {
            $user->append('name_role_user');
        }

        return Inertia::render('Admin/User/Index', [
            'users'     => $users,
            'filters'   => $request->only(['search', 'perPage']),
        ]);
    }

    public function create()
    {
        $roles = Role::query()->pluck('description', 'id');

        return Inertia::render('Admin/User/Create', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        $user = new User();

        $user->fill([
            'name'              => $request->input('name'),
            'email'             => $request->input('email'),
            'email_verified_at' => date('Y-m-d'),
        ]);

        $user->password = Hash::make($request->input('password'));

        $user->save();

        if ($request->has('roles')) {
            $user->assignRole($request->input('roles'));
            $user->save();
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imgFile = Image::make($image->getRealPath());
            $nameFile = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = storage_path("app/public/users/{$user->id}");

            if (!Storage::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775);
            }

            $imgFile->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
            })->save("{$destinationPath}/{$nameFile}");

            $user->photo = $nameFile;
            $user->save();
        }

        if ($request->has('enviar_datos')) {
            try {
                $user->notify(new DatosAcceso($user, $request->input('password')));
            } catch (\Throwable $th) {
                Log::error('No se pudo enviar datos de acceso.  Error: ' . $th->getMessage());
            }
        }

        return redirect()->route('admin.users.index')->with([
            'message' => "Registro creado correctamente"
        ]);
    }

    public function edit(User $usuario)
    {
        return Inertia::render('Admin/User/Edit', [
            'roles' => Role::query()->pluck('description', 'id'),
            'user'  => $usuario->load(['roles']),
        ]);
    }

    public function update(User $usuario, Request $request)
    {
        $request->validate([
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'nullable',
        ]);

        $usuario->fill([
            'name'              => $request->input('name'),
            'email'             => $request->input('email'),
            'email_verified_at' => date('Y-m-d'),
        ]);

        $usuario->save();

        // VERIFICAR SI EL CAMPO ESTA PRESENTE Y NO ES UNA CADENA VACIA
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->input('password'));
            $usuario->save();
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imgFile = Image::make($image->getRealPath());
            $nameFile = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = storage_path("app/public/users/{$usuario->id}");

            # VERIFICAR SI LA RUTA EXISTE
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775);
            }

            # VERIFICAR SI EL ARCHIVO ANTERIOR EXISTE
            if (File::exists("{$destinationPath}/{$usuario->photo}")) {
                File::delete("{$destinationPath}/{$usuario->photo}");
            }

            $imgFile->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
            })->save("{$destinationPath}/{$nameFile}");

            $usuario->photo = $nameFile;
            $usuario->save();
        }

        if ($request->has('roles')) {
            $usuario->syncRoles($request->input('roles'));
            $usuario->save();
        }

        if ($request->has('enviar_datos') && $request->has('password')) {
            try {
                $usuario->notify(new DatosAcceso($usuario, $request->input('password')));
            } catch (\Throwable $th) {
                Log::error('No se pudo enviar datos de acceso.  Error: ' . $th->getMessage());
            }
        }

        return redirect()->route('admin.users.index')->with([
            'message' => "Registro actualizado correctamente"
        ]);
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()
            ->route('admin.users.index')
            ->with([
                'message' => 'Registro eliminado correctamente'
            ]);
    }
}
