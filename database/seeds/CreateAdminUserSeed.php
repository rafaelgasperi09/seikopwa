<?php

use Illuminate\Database\Seeder;

class CreateAdminUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Register a new user
        $user = \Cartalyst\Sentinel\Laravel\Facades\Sentinel::registerAndActivate([
            'email'      => 'admin@admin.com',
            'first_name' => 'Administrador',
            'last_name'  => 'del Sistema',
            'password'   => '!Xga798b',
        ]);

        $role = \Cartalyst\Sentinel\Laravel\Facades\Sentinel::findRoleByName('Programador');

        $role->users()->attach($user);
    }
}
