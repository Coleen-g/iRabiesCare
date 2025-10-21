<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccinationSchedule extends Model
{
    protected $fillable = [
        'user_id', 'schedule_1', 'schedule_2', 'schedule_3'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
