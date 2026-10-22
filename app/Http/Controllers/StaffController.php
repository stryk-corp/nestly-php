<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Maintenance staff portal.
 * Mirrors Django's staff_dashboard, view_complaint_staff, and update_complaint_status views.
 */
class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:STAFF']);
    }

    // ---------------------------------------------------------------- Dashboard
    public function dashboard(Request $request)
    {
        $scope      = ComplaintService::scopeForUser(Auth::user());
        $stats      = ComplaintService::getStats(clone $scope);
        $query      = ComplaintService::applyFilters(clone $scope, $request);
        $complaints = $query->paginate(ComplaintService::PAGE_SIZE)->withQueryString();
        $categories = ComplaintCategory::orderBy('name')->get();

        return view('staff.dashboard', compact('complaints', 'stats', 'categories'));
    }

    // ---------------------------------------------------------------- View / update a complaint
    public function show(Request $request, int $id)
    {
        $complaint = ComplaintService::scopeForUser(Auth::user())->findOrFail($id);

        if ($request->isMethod('POST')) {
            $currentOrder    = Complaint::STATUS_ORDER[$complaint->status];
            $allowedStatuses = collect(Complaint::STATUS_ORDER)
                ->filter(fn ($o) => $o >= $currentOrder)
                ->keys()->toArray();

            $request->validate([
                'status'  => ['required', 'in:' . implode(',', $allowedStatuses)],
                'message' => ['required', 'string', 'min:3', 'max:1000'],
            ]);

            ComplaintService::addUpdate($complaint, Auth::user(), $request->message, $request->status);
            return back()->with('success', 'Update posted.');
        }

        return view('staff.complaint_detail', [
            'complaint' => $complaint,
            'updates'   => $complaint->updates()->with('author')->get(),
        ]);
    }
}
