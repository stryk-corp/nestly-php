<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    protected $fillable = ['hostel_id', 'room_number', 'capacity'];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /** Students currently living in this room */
    public function residents(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Find a room with free space in $hostel, or create a new one.
     * Mirrors Django's assign_room() with a DB transaction.
     */
    public static function assignRoom(Hostel $hostel): self
    {
        return DB::transaction(function () use ($hostel) {
            $room = self::where('hostel_id', $hostel->id)
                ->withCount('residents')
                ->having('residents_count', '<', DB::raw('capacity'))
                ->orderBy('id')
                ->first();

            if ($room) {
                return $room;
            }

            // Auto-generate the next room number
            $number = $hostel->rooms()->count() + 1;
            while (self::where('hostel_id', $hostel->id)
                ->where('room_number', sprintf('R-%03d', $number))
                ->exists()) {
                $number++;
            }

            return self::create([
                'hostel_id'   => $hostel->id,
                'room_number' => sprintf('R-%03d', $number),
                'capacity'    => 4,
            ]);
        });
    }
}
