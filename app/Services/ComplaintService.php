<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\ComplaintUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ComplaintService
{
    const PAGE_SIZE = 10;

    /**
     * Return a base query scoped to what $user is allowed to see.
     */
    public static function scopeForUser(User $user): Builder
    {
        $base = Complaint::with(['student', 'hostel', 'category']);

        return match ($user->role) {
            User::ROLE_ADMIN   => $base,
            User::ROLE_PORTER  => $user->hostel_id
                ? $base->where('hostel_id', $user->hostel_id)
                : $base->whereRaw('0 = 1'),   // no hostel → nothing
            User::ROLE_STUDENT => $base->where('student_id', $user->id),
            default            => $base->whereRaw('0 = 1'),
        };
    }

    /**
     * Apply status / category / hostel / search filters from query-string.
     */
    public static function applyFilters(Builder $query, Request $request, bool $allowHostel = false): Builder
    {
        if ($status = $request->query('status')) {
            if (in_array($status, ['PENDING', 'IN_PROGRESS', 'RESOLVED'])) {
                $query->where('status', $status);
            }
        }

        if ($category = $request->query('category')) {
            if (is_numeric($category)) {
                $query->where('category_id', (int) $category);
            }
        }

        if ($allowHostel && $hostel = $request->query('hostel')) {
            if (is_numeric($hostel)) {
                $query->where('hostel_id', (int) $hostel);
            }
        }

        if ($q = trim($request->query('q', ''))) {
            $query->where(function (Builder $sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhereHas('student', fn (Builder $u) =>
                        $u->where('first_name', 'like', "%{$q}%")
                          ->orWhere('last_name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%")
                    );
                if (is_numeric(ltrim($q, '#'))) {
                    $sub->orWhere('id', (int) ltrim($q, '#'));
                }
            });
        }

        return $query;
    }

    /**
     * Append a history row and keep the complaint status in sync.
     */
    public static function addUpdate(Complaint $complaint, User $user, string $message, string $status): void
    {
        ComplaintUpdate::create([
            'complaint_id'      => $complaint->id,
            'updated_by'        => $user->id,
            'message'           => $message,
            'status_changed_to' => $status,
        ]);

        if ($status !== $complaint->status) {
            $complaint->status      = $status;
            $complaint->resolved_at = ($status === Complaint::STATUS_RESOLVED) ? now() : null;
        }
        $complaint->save();
    }

    /** Aggregate counts for dashboard cards. */
    public static function getStats(Builder $query): array
    {
        $clone = clone $query;
        return [
            'total'       => (clone $clone)->count(),
            'pending'     => (clone $clone)->where('status', Complaint::STATUS_PENDING)->count(),
            'in_progress' => (clone $clone)->where('status', Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => (clone $clone)->where('status', Complaint::STATUS_RESOLVED)->count(),
        ];
    }
}
