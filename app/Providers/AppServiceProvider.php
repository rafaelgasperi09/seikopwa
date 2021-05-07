<?php

namespace App\Providers;

use App\FormularioData;
use App\FormularioRegistro;
use App\FormularioRegistroEstatus;
use App\Observers\FormularioDataObserver;
use App\Observers\FormularioRegistroObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        FormularioData::observe(FormularioDataObserver::class);
        FormularioRegistro::observe(FormularioRegistroObserver::class);

    }
}
