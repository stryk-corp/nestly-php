<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    // ---------------------------------------------------------------- Roles
    const ROLE_STUDENT = 'STUDENT';
    const ROLE_PORTER  = 'PORTER';
    const ROLE_ADMIN   = 'ADMIN';

    const ROLE_LABELS = [
        self::ROLE_STUDENT => 'Student',
        self::ROLE_PORTER  => 'Porter',
        self::ROLE_ADMIN   => 'Administrator',
    ];

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
        'role', 'phone', 'hostel_id',
        'is_approved', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_approved'       => 'boolean',
        'is_active'         => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    // Normalise email to lowercase before save
    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    // ---------------------------------------------------------------- Accessors
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}") ?: $this->email;
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLE_LABELS[$this->role] ?? $this->role;
    }

    // ---------------------------------------------------------------- Role helpers
    public function isStudent(): bool { return $this->role === self::ROLE_STUDENT; }
    public function isPorter(): bool  { return $this->role === self::ROLE_PORTER; }
    public function isAdmin(): bool   { return $this->role === self::ROLE_ADMIN; }

    // ---------------------------------------------------------------- Relationships
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'student_id');
    }

    public function complaintUpdates(): HasMany
    {
        return $this->hasMany(ComplaintUpdate::class, 'updated_by');
    }
}
