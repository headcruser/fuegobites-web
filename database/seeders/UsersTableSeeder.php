<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Fluent;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuarios = [
            new Fluent([
                'name'              => 'Daniel Martinez Sierra',
                'email'             => 'headcruser@gmail.com'
            ])
        ];

        foreach ($usuarios as $usuario) {
            $condition = [
                ['email', '=', $usuario->email],
            ];

            if (!User::where($condition)->exists()) {
                if (App::isLocal()) {
                    User::factory()->create([
                        'name'  => $usuario->name,
                        'email' => $usuario->email,
                    ]);
                } else {
                    User::factory()->admin()->create([
                        'name'  => $usuario->name,
                        'email' => $usuario->email,
                    ]);
                }
            }
        }
    }
}
