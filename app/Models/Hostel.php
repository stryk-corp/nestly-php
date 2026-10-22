<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hostel extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Users linked to this hostel (students and porters) */
    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** Complaints lodged in this hostel */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
