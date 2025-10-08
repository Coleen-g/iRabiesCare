<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'bite_date',
        'bite_category',
        'bite_location',
        'animal_type',
        'outcome',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
