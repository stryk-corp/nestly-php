<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Porter-facing complaint management.
 * Porters manage complaints for their assigned hostel.
 */
class PorterController extends Controller
{

    // ---------------------------------------------------------------- Dashboard
    public function dashboard(Request $request)
    {
        $scope      = ComplaintService::scopeForUser(Auth::user());
        $stats      = ComplaintService::getStats(clone $scope);
        $query      = ComplaintService::applyFilters(clone $scope, $request);
        $complaints = $query->paginate(ComplaintService::PAGE_SIZE)->withQueryString();
        $categories = ComplaintCategory::orderBy('name')->get();

        return view('porter.dashboard', compact('complaints', 'stats', 'categories'));
    }

    // ---------------------------------------------------------------- Manage a single complaint
    public function show(Request $request, int $id)
    {
        $complaint = ComplaintService::scopeForUser(Auth::user())->findOrFail($id);

        if ($request->isMethod('POST')) {
            $this->validateAndUpdate($request, $complaint);
            return back()->with('success', 'Complaint updated.');
        }

        return view('porter.complaint_manage', [
            'complaint' => $complaint,
            'updates'   => $complaint->updates()->with('author')->get(),
        ]);
    }

    private function validateAndUpdate(Request $request, Complaint $complaint): void
    {
        $currentOrder    = Complaint::STATUS_ORDER[$complaint->status];
        $allowedStatuses = collect(Complaint::STATUS_ORDER)
            ->filter(fn ($order) => $order >= $currentOrder)
            ->keys()->toArray();

        $request->validate([
            'status'  => ['required', 'in:' . implode(',', $allowedStatuses)],
            'message' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        ComplaintService::addUpdate($complaint, Auth::user(), $request->message, $request->status);
    }
}
