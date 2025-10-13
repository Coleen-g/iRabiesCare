<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'age', 'gender', 'address', 'contact_number'];

    // Relationships
    public function cases() {
        return $this->hasMany(CaseRecord::class, 'patient_id');
    }

    public function vaccinations() {
        return $this->hasMany(Vaccination::class, 'patient_id');
    }
}
