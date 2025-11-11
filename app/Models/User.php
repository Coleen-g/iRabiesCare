<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'plain_password_encrypted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return isset($this->role) && $this->role === 'admin';
    }

    /**
     * One-to-one relation to Patient record
     */
    public function patient()
    {
        return $this->hasOne(\App\Models\Patient::class, 'user_id');
    }

    /**
     * Patients assigned to this health staff (many-to-many via health_staff_assignments)
     */
    public function assignedPatients()
    {
        return $this->belongsToMany(\App\Models\Patient::class, 'health_staff_assignments', 'health_staff_id', 'patient_id')
                    ->withTimestamps()
                    ->withPivot(['id', 'assigned_by', 'assigned_at']);
    }

    /**
     * One-to-one relation to VaccinationSchedule record
     */
    public function vaccinationSchedule()
    {
        return $this->hasOne(\App\Models\VaccinationSchedule::class, 'user_id');
    }

    /**
     * Decrypted plain password accessor (for admin use only).
     */
    public function getPlainPasswordAttribute()
    {
        if (empty($this->plain_password_encrypted)) return null;
        try {
            return Crypt::decryptString($this->plain_password_encrypted);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Send the password reset notification.
     * This ensures the Password broker can notify the user with a reset token.
     */
    public function sendPasswordResetNotification($token)
    {
        // Use an application notification that explicitly includes the
        // user's email in the reset URL so the reset form can prefill it.
        // Wrap the notify call so SMTP/authentication failures don't bubble
        // up and break the password reset request flow. If mail fails,
        // log the error and add a fallback info entry with the reset URL
        // so the developer/admin can retrieve the link while debugging.
        try {
            $this->notify(new \App\Notifications\ResetPasswordWithEmail($token));
        } catch (\Throwable $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage(), [
                'user_id' => $this->id ?? null,
                'email' => $this->email ?? null,
                'exception' => $e,
            ]);

            // Build a reset URL and log it as an info-level fallback so
            // developers can copy it for testing while SMTP is being fixed.
            try {
                $email = $this->getEmailForPasswordReset();
                $url = url(route('password.reset', [
                    'token' => $token,
                    'email' => $email,
                ], false));
                \Log::info('Fallback password reset URL: ' . $url, ['user_id' => $this->id]);
            } catch (\Throwable $inner) {
                // If building the URL fails for any reason, still swallow it.
                \Log::warning('Unable to build fallback password reset URL: ' . $inner->getMessage());
            }
        }
    }
}
