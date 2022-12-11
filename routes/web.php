<?php

use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\PostController;
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
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    # NOTE: RUTAS ADMINISTRATIVAS
    Route::middleware(['verified'])->name('admin.')->prefix('admin')->group(function () {
        # NOTE: USUARIOS
        Route::resource('usuarios',UsersController::class)->parameters([
            'usuarios' => 'usuario'
        ])->names('users');

        # NOTE: ROLES
        Route::resource('roles',RolesController::class)->parameters([
            'roles' => 'role'
        ])->names('roles');

        # NOTE: PERMISOS
        Route::get('permisos',[PermissionsController::class,'index'])->name('perms.index');
        Route::post('perms-save',[PermissionsController::class,'perms_save'])->name('perms.save');
    });


    Route::inertia('acerca-de','About')->name('about');

    Route::get('image', [PostController::class,'image']);
    Route::post('image', [PostController::class,'upload_image'])->name('image.save');
    Route::resource('posts',PostController::class)->parameters([
        'posts' => 'post'
    ]);
});

