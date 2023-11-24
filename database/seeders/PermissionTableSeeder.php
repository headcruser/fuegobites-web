<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Fluent;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            new Fluent([
                'name'          => 'gestionar_usuarios',
                'description'   => 'Permite la gestion de usuarios',
                'guard_name'         => 'web',
            ]),
            new Fluent([
                'name'          => 'gestionar_roles',
                'description'   => 'Permite la gestion de roles',
                'guard_name'         => 'web',
            ]),
            new Fluent([
                'name'          => 'gestionar_permisos',
                'description'   => 'Permite la gestion de permisos',
                'guard_name'    => 'web',
            ]),
            new Fluent([
                'name'          => 'reporte_mensual',
                'description'   => 'Permite ver el reporte mensual',
                'guard_name'    => 'web',
            ]),
            new Fluent([
                'name'          => 'registrar_pedido',
                'description'   => 'Permite registrar un pedido',
                'guard_name'    => 'web',
            ]),
            new Fluent([
                'name'          => 'pedidos_por_hacer',
                'description'   => 'Permite ver los pedidos pendientes',
                'guard_name'    => 'web',
            ]),
            new Fluent([
                'name'          => 'gestionar_productos',
                'description'   => 'Permite gestionar productos',
                'guard_name'    => 'web',
            ]),
            new Fluent([
                'name'          => 'gestionar_clientes',
                'description'   => 'Permite Gestionar clientes',
                'guard_name'    => 'web',
            ]),
            new Fluent([
                'name'          => 'gestionar_cotizaciones',
                'description'   => 'Permite gestionar cotizaciones',
                'guard_name'    => 'web',
            ]),
        ];

        foreach ($permissions as $permission) {

            Permission::updateOrCreate([
                'name'   => $permission->name,
            ], [
                'description'   => $permission->description,
                'guard_name'    => $permission->guard_name,
                'description'   => $permission->description,
            ]);
        }
    }
}
