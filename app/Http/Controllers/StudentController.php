<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintUpdate;
use App\Models\Hostel;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Student-facing complaint management.
 */
class StudentController extends Controller
{

    // ---------------------------------------------------------------- Dashboard
    public function dashboard(Request $request)
    {
        $query = ComplaintService::scopeForUser(Auth::user());
        $stats = ComplaintService::getStats(clone $query);
        $complaints = $query->paginate(ComplaintService::PAGE_SIZE)->withQueryString();

        return view('student.dashboard', compact('complaints', 'stats'));
    }

    // ---------------------------------------------------------------- Submit complaint
    public function showSubmit()
    {
        return view('student.submit_complaint', [
            'categories' => ComplaintCategory::where('is_active', true)->get(),
            'hostels'    => Hostel::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'hostel_id'   => ['required', 'exists:hostels,id'],
            'room_number' => ['nullable', 'string', 'max:20'],
            'title'       => ['required', 'string', 'max:120'],
            'category_id' => ['required', 'exists:complaint_categories,id'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $complaint = Complaint::create([
            'student_id'  => $user->id,
            'hostel_id'   => $data['hostel_id'],
            'room_number' => $data['room_number'] ?? null,
            'category_id' => $data['category_id'],
            'title'       => $data['title'],
            'description' => $data['description'],
            'status'      => Complaint::STATUS_PENDING,
        ]);

        ComplaintUpdate::create([
            'complaint_id'      => $complaint->id,
            'updated_by'        => $user->id,
            'message'           => 'Complaint submitted.',
            'status_changed_to' => Complaint::STATUS_PENDING,
        ]);

        return redirect()->route('student.complaint', $complaint->id)
            ->with('success', "Complaint #{$complaint->id} submitted. Its status is Pending.");
    }

    // ---------------------------------------------------------------- View complaint
    public function show(int $id)
    {
        $complaint = ComplaintService::scopeForUser(Auth::user())
            ->findOrFail($id);
        $updates = $complaint->updates()->with('author')->get();

        return view('student.complaint_detail', compact('complaint', 'updates'));
    }

    // ---------------------------------------------------------------- Rate complaint
    public function showRate(int $id)
    {
        $complaint = ComplaintService::scopeForUser(Auth::user())->findOrFail($id);

        if ($complaint->status !== Complaint::STATUS_RESOLVED || $complaint->student_rating) {
            return redirect()->route('student.complaint', $id)
                ->with('info', 'This complaint cannot be rated right now.');
        }

        return view('student.rate_complaint', compact('complaint'));
    }

    public function rate(Request $request, int $id)
    {
        $complaint = ComplaintService::scopeForUser(Auth::user())->findOrFail($id);

        if ($complaint->status !== Complaint::STATUS_RESOLVED || $complaint->student_rating) {
            return redirect()->route('student.complaint', $id)
                ->with('info', 'This complaint cannot be rated right now.');
        }

        $data = $request->validate([
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string', 'max:500'],
        ]);

        $complaint->update([
            'student_rating'   => $data['rating'],
            'student_feedback' => $data['feedback'] ?? '',
        ]);

        return redirect()->route('student.complaint', $id)
            ->with('success', 'Thank you for your feedback.');
    }
}
