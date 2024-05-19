<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ClientesController;
use App\Http\Controllers\Admin\ProductosController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Ventas\CotizacionesController;
use App\Http\Controllers\Ventas\ReporteVentaController;
use App\Http\Controllers\Ventas\RegistroPedidosController;
use App\Http\Controllers\Ventas\PedidosPendientesController;

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
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
        Route::controller(PermissionsController::class)->group(function () {
            Route::name('perms.')->prefix('permisos')->group(function () {
                Route::get('panel-permisos', 'panel')->name('panel');
                Route::post('perms-save', 'perms_save')->name('save');
            });

            Route::resource('perms', PermissionsController::class)->parameters([
                'perms' => 'permission'
            ])->names('perms');
        });


        # NOTE: PRODUCTOS
        Route::resource('productos', ProductosController::class)->parameters([
            'productos' => 'producto'
        ])->names('productos');

        # NOTE: CLIENTES
        Route::prefix('clientes')
            ->name('clientes.')
            ->controller(ClientesController::class)
            ->group(function () {
                Route::post('xeditable', 'xeditable')->name('xeditable');
                Route::post('select2', 'select2')->name('select2');
            });

        Route::resource('clientes', ClientesController::class)->parameters([
            'clientes' => 'cliente'
        ])->names('clientes');
    });

    # NOTE: VENTAS
    Route::name('ventas.')->prefix('ventas')->group(function () {

        # NOTE: COTIZACIONES
        Route::name('cotizaciones.')
            ->prefix('cotizaciones')
            ->controller(CotizacionesController::class)
            ->group(function () {
                Route::post('cambiar-cliente/{cotizacion}', 'cambiar_cliente')->name('cambiar-cliente');
                Route::post('agregar-partida/{cotizacion}', 'agregar_partida')->name('agregar-partida');
                Route::put('actualizar-partida/{partida}', 'actualizar_partida')->name('actualizar-partida');
                Route::delete('eliminar-partida/{partida}', 'eliminar_partida')->name('eliminar-partida');
                Route::post('enviar-cotizacion/{cotizacion}', 'enviar_cotizacion')->name('enviar-cotizacion');
            });

        Route::resource('cotizaciones', CotizacionesController::class)->parameters([
            'cotizaciones' => 'cotizacion'
        ])
            ->names('cotizaciones')
            ->except('store');


        # NOTE: REGISTRO PEDIDOS
        Route::prefix('registro')
            ->name('registro.')
            ->controller(RegistroPedidosController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('{venta}', 'update')->name('update');
                Route::delete('{venta}', 'destroy')->name('destroy');
            });


        # NOTE: VER REGISTROS PENDIENTES
        Route::name('pedidos.')
            ->prefix('pedidos')
            ->controller(PedidosPendientesController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });

        # NOTE: VER REPORTE DE VENTAS
        Route::name('reporte.')
            ->prefix('reporte')
            ->controller(ReporteVentaController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });
    });
});
