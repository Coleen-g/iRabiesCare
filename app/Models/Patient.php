<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        // user_username and user_password_encrypted mirror linked user credentials
        'user_username',
        'user_password_encrypted',
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
        'wounds_location',
        'animal_status',
        'clinic',
        'emergency_contact',
    ];

    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'patient_id');
    }

    /**
     * Health staff assigned to this patient (many-to-many via health_staff_assignments)
     */
    public function assignedHealthStaff()
    {
        return $this->belongsToMany(\App\Models\User::class, 'health_staff_assignments', 'patient_id', 'health_staff_id')
                    ->withTimestamps()
                    ->withPivot(['id', 'assigned_by', 'assigned_at']);
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Backwards-compatible accessor for last_dose (maps to last_dose_date column)
     */
    public function getLastDoseAttribute()
    {
        return $this->attributes['last_dose_date'] ?? null;
    }

    /**
     * Mutator to allow setting last_dose and store into last_dose_date
     */
    public function setLastDoseAttribute($value)
    {
        $this->attributes['last_dose_date'] = $value;
    }
}
