<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'patient_sessions';
    protected $fillable = [
        'patient_id', 
        'session_date', 
        'value', 
        'notes', 
        'status'
    ];

    protected $casts = [
        'session_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}