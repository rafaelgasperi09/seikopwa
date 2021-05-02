<?php

namespace App\Providers;

use App\FormularioData;
use App\Observers\FormularioDataObserver;
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
    }
}
