<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dob',
        'gender',
        'contact',
        'email',
        'address',
        'user_id',
        'exposure_date',
        'exposure_type',
        'animal',
        'vaccination_status',
        'last_dose_date',
        'clinic',
        'emergency_contact',
    ];

    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'patient_id');
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
