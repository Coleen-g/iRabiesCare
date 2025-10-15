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
    ];

    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'patient_id');
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'patient_id');
    }
}
