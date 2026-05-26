<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildNeuroAnamnesis extends Model
{
    // Força o nome exato da tabela que está no banco
    protected $table = 'child_neuro_anamnesis'; 

    protected $fillable = [
        'patient_id',
        'sections'
    ];

    protected $casts = [
        'sections' => 'array'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}