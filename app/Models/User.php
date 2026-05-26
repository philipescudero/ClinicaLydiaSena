<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atributos que podem ser preenchidos em massa.
     * Adicionamos 'role' para diferenciar Lydia dos pacientes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Esta linha é obrigatória aqui
    ];

    /**
     * Atributos que devem ser ocultados em arrays (como JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Helper para verificar se o usuário é administrador.
     * Útil para proteger as rotas operacionais da Lydia.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function pagamentos()
    {
        return $this->hasMany(PatientPayment::class, 'patient_id'); 
    }
    
}