<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdultAnamnesis extends Model
{
    protected $table = 'adult_anamneses';

    protected $fillable = [
        'patient_id',
        'sections'
    ];

    protected $casts = [
        'sections' => 'array' // Garante que o Laravel converta JSON em Array automaticamente
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}