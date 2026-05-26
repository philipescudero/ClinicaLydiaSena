<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function progressNotes()
    {
        return $this->hasMany(ProgressNote::class);
    }   
    // Isso permite que o Laravel salve esses campos em massa
    protected $fillable = [
        'name', 'email', 'cpf', 'phone', 'birth_date', 'gender', 'city_state', 'observations'
    ];

    // Relacionamento: Um paciente tem muitas sessões
    public function sessions()
    {
        return $this->hasMany(PatientSession::class);
    }
    public function treatmentPlans() {
        return $this->hasMany(TreatmentPlan::class);
    }
    public function childNeuroAnamnesis()
    {
        // Um paciente tem apenas uma anamnese neuropsicológica infantil
        return $this->hasOne(ChildNeuroAnamnesis::class);
    }
    public function adultAnamnesis()
    {
        return $this->hasOne(AdultAnamnesis::class);
    }
    public function adultNeuroAnamnesis()
    {
        return $this->hasOne(AdultNeuroAnamnesis::class);
    }
    public function childPsicoAnamnesis()
    {
        return $this->hasOne(ChildPsicoAnamnesis::class);
    }
}