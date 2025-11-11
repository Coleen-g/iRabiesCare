<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccinationSchedule extends Model
{
    protected $fillable = [
        'user_id', 'schedule_1', 'schedule_2', 'schedule_3',
        'schedule_1_status', 'schedule_2_status', 'schedule_3_status',
        'schedule_1_remarks', 'schedule_2_remarks', 'schedule_3_remarks',
        'overall_remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
