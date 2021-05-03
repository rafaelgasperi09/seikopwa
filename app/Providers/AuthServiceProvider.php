<?php

namespace App\Providers;

use App\Equipo;
use App\FormularioRegistro;
use App\Policies\EquipoPolicy;
use App\Policies\FormularioRegistroPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Equipo::class => EquipoPolicy::class,
        FormularioRegistro::class => FormularioRegistroPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
