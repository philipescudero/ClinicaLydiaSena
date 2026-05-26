<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildPsicoAnamnesis extends Model
{
    protected $fillable = ['patient_id', 'sections'];
    protected $casts = ['sections' => 'array'];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }
}
