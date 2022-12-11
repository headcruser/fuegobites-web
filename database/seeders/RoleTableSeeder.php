<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Fluent;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            new Fluent([
                'name'          => 'administrador',
                'description'   => 'Administrador',
                'guard_name'    => 'web',
            ]),
        ];

        foreach ($roles as $rol) {
            Role::updateOrCreate([
                'name'   => $rol->name,
            ],[
                'description'   => $rol->description,
                'guard_name'    => $rol->guard_name,
                'description'   => $rol->description,
            ]);
        }
    }
}
