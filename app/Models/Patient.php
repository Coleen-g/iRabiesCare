<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'gender',
        'address',
        'contact_number'
    ];

    // 🔹 Relationship: A patient can have many case records
    public function caseRecords()
    {
        return $this->hasMany(CaseRecord::class);
    }

    public function vaccinations()
{
    return $this->hasMany(Vaccination::class);
}

}

