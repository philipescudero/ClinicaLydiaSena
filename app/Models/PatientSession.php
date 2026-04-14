<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientSession extends Model
{
    protected $table = 'patient_sessions';

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

    // ADICIONE ESTA PARTE ABAIXO
    protected $casts = [
        'session_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}