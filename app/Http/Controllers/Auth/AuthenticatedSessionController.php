<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Exibe a visão de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Lida com uma solicitação de autenticação recebida.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirecionamento limpo e direto, sem olhar para o passado
        if ($user && $user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        // Se for paciente, vai para a área dele obrigatoriamente
        return redirect()->route('patient.area');
    }

    /**
     * Destrói uma sessão autenticada (Logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Altere de return redirect('/') para:
        return redirect()->route('login');
    }
}