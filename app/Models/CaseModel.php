<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'patient_id',
        'date_reported',
        'status',
        'description',
        'reported_by',
        'exposure_date',
        'exposure_type',
        'wounds_location',
        'category',
        'animal_species',
        'animal_status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
