<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthStaff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'health_staffs';

    protected $fillable = [
        'user_id',
        'full_name',
        'gender',
        'date_of_birth',
        'contact_number',
        'email',
        'address',
        'position',
        'department',
        'license_number',
        'employment_status',
        'assigned_facility',
        'username',
        'role',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
