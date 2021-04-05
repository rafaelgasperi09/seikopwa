<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::transaction(function (){
            $this->command->info('Creando Roles...');
            $this->call(CreateRolesSeed::class);
            $this->command->info('Creando Administrador...');
            $this->call(CreateAdminUserSeed::class);
            $this->command->info('Creando Usuarios...');

        });
    }
}
