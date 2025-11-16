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
     * When a patient is updated, sync key fields into related cases so
     * case records reflect the latest patient information (exposure, animal, wounds, status).
     */
    protected static function booted()
    {
        static::saved(function (Patient $patient) {
            // fields on patient that we copy into cases when changed
            $copyFields = [
                'exposure_date', 'exposure_type', 'animal', 'wounds_location', 'animal_status', 'clinic', 'contact', 'email', 'name'
            ];

            $changed = array_filter($copyFields, function ($f) use ($patient) {
                return $patient->wasChanged($f);
            });

            if (empty($changed)) {
                return;
            }

            $update = [];

            // map patient fields into case fields
            if (in_array('exposure_date', $changed)) {
                $update['exposure_date'] = $patient->exposure_date;
            }
            if (in_array('exposure_type', $changed)) {
                $update['exposure_type'] = $patient->exposure_type;
            }
            if (in_array('animal', $changed)) {
                $update['animal_species'] = $patient->animal;
            }
            if (in_array('wounds_location', $changed)) {
                $update['wounds_location'] = $patient->wounds_location;
            }
            if (in_array('clinic', $changed)) {
                // optionally copy clinic to a case field if existing
                $update['clinic'] = $patient->clinic;
            }
            if (in_array('contact', $changed)) {
                $update['reporter_contact'] = $patient->contact ?? null;
            }
            if (in_array('email', $changed)) {
                $update['reporter_email'] = $patient->email ?? null;
            }
            if (in_array('name', $changed)) {
                $update['patient_name_override'] = $patient->name; // optional field to store snapshot
            }

            // If animal_status changed, infer a case status to keep workflow in sync
            if (in_array('animal_status', $changed)) {
                $as = strtolower(trim((string) $patient->animal_status));
                if (in_array($as, ['dead','died','deceased'])) {
                    $update['status'] = 'closed';
                } elseif (in_array($as, ['recovered','treated','healthy','resolved'])) {
                    $update['status'] = 'resolved';
                } else {
                    $update['status'] = 'pending';
                }
                $update['animal_status'] = $patient->animal_status;
            }

            if (!empty($update)) {
                // update all related cases with the mapped patient data
                $patient->cases()->update($update);
            }
        });
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
