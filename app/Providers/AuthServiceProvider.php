<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * As mapeações de políticas (policy) para a aplicação.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Registra qualquer serviço de autenticação / autorização.
     */
    /**
     * Registra qualquer serviço de autenticação / autorização.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // REGRAS DE GATE REMOVIDAS DAQUI PARA EVITAR DUPLICIDADE
    }
}