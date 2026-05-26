<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // O nome do campo que vem do formulário Blade
            'login_identifier' => ['required', 'string'], 
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Utilizamos o login_identifier exatamente como o usuário digitou
        $credentials = [
            'email'    => $this->login_identifier, 
            'password' => $this->password
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            // ... erro de autenticação
        }
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login_identifier' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
            'login_identifier' => __('auth.failed')
        ]);
    }

    public function throttleKey(): string
    {
        // Usamos o identificador de login para o controle de tentativas (rate limit)
        return Str::transliterate(Str::lower($this->string('login_identifier')).'|'.$this->ip());
    }
}