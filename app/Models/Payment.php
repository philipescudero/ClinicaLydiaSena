<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'patient_id',
        'amount',
        'type',
        'payment_date',
        'reference_month',
        'reference_year',
    ];

    // CORREÇÃO: Força o Laravel a transformar a string do banco em um objeto Carbon Date
    protected $casts = [
        'payment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}