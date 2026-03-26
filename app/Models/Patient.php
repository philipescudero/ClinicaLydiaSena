<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    // Isso permite que o Laravel salve esses campos em massa
    protected $fillable = [
        'name', 'email', 'cpf', 'phone', 'birth_date', 'gender', 'city_state', 'observations'
    ];

    // Relacionamento: Um paciente tem muitas sessões
    public function sessions()
    {
        return $this->hasMany(PatientSession::class);
    }
}