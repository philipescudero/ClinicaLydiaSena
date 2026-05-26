<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdultNeuroAnamnesis extends Model
{
    protected $table = 'adult_neuro_anamneses';

    protected $fillable = [
        'patient_id',
        'sections'
    ];

    protected $casts = [
        'sections' => 'array'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}