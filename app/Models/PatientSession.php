<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientSession extends Model
{
    // Indica a tabela correta (que renomeamos antes)
    protected $table = 'patient_sessions';

    // CAMPOS LIBERADOS PARA SALVAR (O erro foi por falta disso aqui)
    protected $fillable = [
        'patient_id', 
        'session_date',
        'service_type',
        'value', 
        'notes', 
        'status',
        'is_recurrent',
        'performed'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}