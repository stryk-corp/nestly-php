<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    // ---------------------------------------------------------------- Constants
    const STATUS_PENDING     = 'PENDING';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const STATUS_RESOLVED    = 'RESOLVED';

    const STATUS_ORDER = [
        self::STATUS_PENDING     => 0,
        self::STATUS_IN_PROGRESS => 1,
        self::STATUS_RESOLVED    => 2,
    ];

    const STATUS_LABELS = [
        self::STATUS_PENDING     => 'Pending',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_RESOLVED    => 'Resolved',
    ];

    protected $fillable = [
        'student_id', 'hostel_id', 'room_number', 'category_id',
        'title', 'description', 'status',
        'student_rating', 'student_feedback', 'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // ---------------------------------------------------------------- Default ordering (latest first)
    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    // ---------------------------------------------------------------- Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ComplaintUpdate::class)->orderBy('created_at');
    }

    // ---------------------------------------------------------------- Accessors
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
