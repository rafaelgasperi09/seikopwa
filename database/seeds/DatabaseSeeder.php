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
            $this->call(FormularioCargaBateriaSeeder::class);
            $this->command->info('Creando Formulario Baterias...');
            $this->call(FormularioDailyCheckSeeder::class);
            $this->command->info('Creando Formulario Daily Check...');
            $this->call(FormularioMantenimientoPreventivoCombustion::class);
            $this->command->info('Creando Formulario Mant Prev Combustion...');
            $this->call(FormularioMantenimientoPreventivoCounterFC::class);
            $this->command->info('Creando Formulario Mant Prev Counter FC...');
            $this->call(FormularioMantenimientoPreventivoCounterRC::class);
            $this->command->info('Creando Formulario Mant Prev Counter RC...');
                        $this->call(FormularioMantenimientoPreventivoCounterSC::class);
            $this->command->info('Creando Formulario Mant Prev Counter SC...');
            $this->call(FormularioMantenimientoPreventivoPallet::class);
            $this->command->info('Creando Formulario Mant Prev Pallet...');
            $this->call(FormularioMantenimientoPreventivoReach::class);
            $this->command->info('Creando Formulario Mant Prev Reach...');
            $this->call(FormularioMantenimientoPreventivoStockpicker::class);
            $this->command->info('Creando Formulario Mant Prev Stock Picker...');
            $this->call(FormularioInformeServicioTecnico::class);
            $this->command->info('Creando Formulario MInform Servicio TEcnico...');


        });
    }
}
