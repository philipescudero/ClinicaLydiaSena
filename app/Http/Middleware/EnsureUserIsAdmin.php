<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário logado tem o role 'admin'
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Se for 'patient' ou qualquer outro, redireciona para a área dele
        return redirect()->route('patient.area');
    }
}