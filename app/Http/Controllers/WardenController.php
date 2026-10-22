<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\User;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Warden-facing complaint management.
 * Mirrors Django's warden_dashboard and manage_complaint views.
 */
class WardenController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:WARDEN']);
    }

    // ---------------------------------------------------------------- Dashboard
    public function dashboard(Request $request)
    {
        $scope  = ComplaintService::scopeForUser(Auth::user());
        $stats  = ComplaintService::getStats(clone $scope);
        $query  = ComplaintService::applyFilters(clone $scope, $request);
        $complaints = $query->paginate(ComplaintService::PAGE_SIZE)->withQueryString();
        $categories = ComplaintCategory::orderBy('name')->get();

        return view('warden.dashboard', compact('complaints', 'stats', 'categories'));
    }

    // ---------------------------------------------------------------- Manage a single complaint
    public function show(Request $request, int $id)
    {
        $complaint = ComplaintService::scopeForUser(Auth::user())->findOrFail($id);
        $technicians = User::where('role', User::ROLE_STAFF)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->orderBy('first_name')->get();

        if ($request->isMethod('POST')) {
            return $this->handlePost($request, $complaint, $technicians);
        }

        return view('warden.complaint_manage', [
            'complaint'   => $complaint,
            'updates'     => $complaint->updates()->with('author')->get(),
            'technicians' => $technicians,
            'can_assign'  => $complaint->category->allows_technician,
        ]);
    }

    private function handlePost(Request $request, Complaint $complaint, $technicians)
    {
        $user   = Auth::user();
        $action = $request->input('action', 'update');

        if ($action === 'assign') {
            if (!$complaint->category->allows_technician) {
                return back()->with('error', 'Only facility issues can be assigned to maintenance staff.');
            }
            $request->validate(['technician_id' => ['required', 'exists:users,id']]);
            $tech = User::findOrFail($request->technician_id);
            $complaint->assigned_to = $tech->id;
            $complaint->save();
            ComplaintService::addUpdate($complaint, $user, "Assigned to {$tech->full_name}.", $complaint->status);
            return back()->with('success', "Assigned to {$tech->full_name}.");
        }

        // default: update
        $this->validateUpdate($request, $complaint);
        ComplaintService::addUpdate($complaint, $user, $request->message, $request->status);
        return back()->with('success', 'Update posted.');
    }

    private function validateUpdate(Request $request, Complaint $complaint): void
    {
        $currentOrder = Complaint::STATUS_ORDER[$complaint->status];
        $allowedStatuses = collect(Complaint::STATUS_ORDER)
            ->filter(fn ($order) => $order >= $currentOrder)
            ->keys()->toArray();

        $request->validate([
            'status'  => ['required', 'in:' . implode(',', $allowedStatuses)],
            'message' => ['required', 'string', 'min:3', 'max:1000'],
        ]);
    }
}
