<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Cliente;


class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('nombre', 'cliente')->firstOrFail();
        $faker = fake();

        while (Cliente::count() < 5) {
            $nombre = $faker->firstName();
            $apellido = $faker->lastName();
            $email = $faker->unique()->safeEmail();

            $user = User::create([
                'name' => $nombre . ' ' . $apellido,
                'email' => $email,
                'password' => bcrypt('12345678'),
                'role_id' => $role->id,
            ]);

            Cliente::create([
                'tipo_documento' => $faker->randomElement(['CC', 'CE', 'PP']),
                'numero_documento' => $faker->unique()->numerify('##########'),
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $faker->numerify('3#########'),
                'email' => $email,
                'direccion' => $faker->address(),
                'ciudad' => $faker->city(),
                'user_id' => $user->id,
            ]);
        }
    }
}
