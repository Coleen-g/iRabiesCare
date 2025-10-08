<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'vaccine_type',
        'dose_number',
        'vaccination_date',
        'remarks',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
