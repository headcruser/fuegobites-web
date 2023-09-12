<?php

namespace App\Http\Controllers\Admin;

use Image;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
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
            'password'          => Hash::make($request->input('password')),
            'email_verified_at' => date('Y-m-d'),
        ]);

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
        $usuario->fill([
            'name'              => $request->input('name'),
            'email'             => $request->input('email'),
            'password'          => Hash::make($request->input('password')),
            'email_verified_at' => date('Y-m-d'),
        ]);

        $usuario->save();

        $usuario->syncRoles($request->input('roles'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return Response
     */
    public function destroy(Request $request, User $usuario)
    {
        $usuario->delete();

        return redirect()
            ->route('admin.users.index')
            ->with([
                'message' => 'Registro eliminado correctamente'
            ]);
    }
}
