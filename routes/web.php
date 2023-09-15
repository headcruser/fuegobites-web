<?php

use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ProductosController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Ventas\VentasController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::inertia('acerca-de', 'About')->name('about');

    Route::inertia('profile', 'Profile')->name('profile');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    # NOTE: RUTAS ADMINISTRATIVAS
    Route::name('admin.')->prefix('admin')->group(function () {
        # NOTE: USUARIOS
        Route::resource('usuarios', UsersController::class)->parameters([
            'usuarios' => 'usuario'
        ])->names('users');

        # NOTE: ROLES
        Route::resource('roles', RolesController::class)->parameters([
            'roles' => 'role'
        ])->names('roles');

        # NOTE: PERMISOS
        Route::get('permisos', [PermissionsController::class, 'index'])->name('perms.index');
        Route::post('perms-save', [PermissionsController::class, 'perms_save'])->name('perms.save');

        # NOTE: PRODUCTOS
        Route::resource('productos', ProductosController::class)->parameters([
            'productos' => 'producto'
        ])->names('productos');
    });

    Route::name('ventas.')->prefix('ventas')->group(function () {
        Route::get('registro', [VentasController::class, 'index'])->name('registro.index');
        Route::post('registro', [VentasController::class, 'store'])->name('registro.store');
    });
});
