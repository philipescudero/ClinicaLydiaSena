<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    // Permite que o Laravel salve esses campos no banco
    protected $fillable = [
        'patient_id', 
        'date', 
        'clinical_evolution', 
        'therapeutic_objectives'
    ];

    // Garante que a data seja tratada como um objeto de data
    protected $casts = [
        'date' => 'date'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}