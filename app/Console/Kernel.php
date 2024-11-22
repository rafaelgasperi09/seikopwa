<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ImportarClienteCrm::class,
        \App\Console\Commands\notificarChequeoDiario::class,
        \App\Console\Commands\notificarBateriasSinHidratar::class,
        \App\Console\Commands\ImportarEquipos::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $fec = date('d');

         $schedule->command('notificar:chequeo_diario')
            ->dailyAt('7:30')->dailyAt('15:30')->dailyAt('22:30')
             ->sendOutputTo(storage_path('logs/notificar_chequeo_diario-'.$fec.'.log'));
        $schedule->command('notificar:baterias_no_hidratadas')
             ->dailyAt('07:30')
             ->sendOutputTo(storage_path('logs/notificar_baterias_no_hidratadas-'.$fec.'.log'));
        $schedule->command('importar:equipos')
             ->dailyAt('06:30')->dailyAt('12:00')
             ->sendOutputTo(storage_path('logs/importar-equipos'.$fec.'.log'));;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
