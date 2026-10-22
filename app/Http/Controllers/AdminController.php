<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Hostel;
use App\Models\User;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Administrator control panel.
 */
class AdminController extends Controller
{

    // ================================================================ Dashboard
    public function dashboard(Request $request)
    {
        $scope  = ComplaintService::scopeForUser(Auth::user());
        $stats  = ComplaintService::getStats(clone $scope);
        $query  = ComplaintService::applyFilters(clone $scope, $request, allowHostel: true);
        $complaints = $query->paginate(ComplaintService::PAGE_SIZE)->withQueryString();

        $perHostel = Hostel::withCount([
            'complaints',
            'complaints as pending_count'     => fn ($q) => $q->where('status', Complaint::STATUS_PENDING),
            'complaints as in_progress_count' => fn ($q) => $q->where('status', Complaint::STATUS_IN_PROGRESS),
            'complaints as resolved_count'    => fn ($q) => $q->where('status', Complaint::STATUS_RESOLVED),
        ])->orderByDesc('complaints_count')->orderBy('name')->get();

        $userCounts = collect(User::ROLE_LABELS)->mapWithKeys(
            fn ($label, $role) => [$label => User::where('role', $role)->count()]
        );

        return view('admin.dashboard', [
            'complaints'        => $complaints,
            'stats'             => $stats,
            'per_hostel'        => $perHostel,
            'hostels'           => Hostel::orderBy('name')->get(),
            'categories'        => ComplaintCategory::orderBy('name')->get(),
            'awaiting_approval' => User::where('is_approved', false)->count(),
            'user_counts'       => $userCounts,
        ]);
    }

    // ================================================================ Single complaint
    public function showComplaint(Request $request, int $id)
    {
        $complaint = ComplaintService::scopeForUser(Auth::user())->findOrFail($id);

        if ($request->isMethod('POST')) {
            $currentOrder    = Complaint::STATUS_ORDER[$complaint->status];
            $allowedStatuses = collect(Complaint::STATUS_ORDER)
                ->filter(fn ($o) => $o >= $currentOrder)->keys()->toArray();
            $request->validate([
                'status'  => ['required', 'in:' . implode(',', $allowedStatuses)],
                'message' => ['required', 'string', 'min:3', 'max:1000'],
            ]);
            ComplaintService::addUpdate($complaint, Auth::user(), $request->message, $request->status);
            return back()->with('success', 'Update posted.');
        }

        return view('admin.complaint_manage', [
            'complaint' => $complaint,
            'updates'   => $complaint->updates()->with('author')->get(),
        ]);
    }

    // ================================================================ Users
    public function users(Request $request)
    {
        $query = User::with(['hostel'])->orderByDesc('created_at');

        if ($role = $request->query('role')) {
            if (array_key_exists($role, User::ROLE_LABELS)) {
                $query->where('role', $role);
            }
        }
        if ($state = $request->query('state')) {
            match ($state) {
                'pending'  => $query->where('is_approved', false),
                'inactive' => $query->where('is_active', false),
                default    => null,
            };
        }
        if ($q = trim($request->query('q', ''))) {
            $query->where(fn ($sub) =>
                $sub->where('email', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
            );
        }

        $users = $query->paginate(ComplaintService::PAGE_SIZE)->withQueryString();

        return view('admin.users.index', [
            'users'             => $users,
            'roles'             => User::ROLE_LABELS,
            'awaiting_approval' => User::where('is_approved', false)->count(),
        ]);
    }

    public function showUserForm(Request $request, int $id = null)
    {
        $editUser = $id ? User::findOrFail($id) : null;
        return view('admin.users.form', [
            'editUser' => $editUser,
            'hostels'  => Hostel::where('is_active', true)->orderBy('name')->get(),
            'roles'    => User::ROLE_LABELS,
        ]);
    }

    public function saveUser(Request $request, int $id = null)
    {
        $editUser = $id ? User::findOrFail($id) : null;
        $isCreate = ($editUser === null);

        $rules = [
            'email'      => ['required', 'email', "unique:users,email" . ($id ? ",{$id}" : '')],
            'first_name' => ['required', 'string', 'max:150'],
            'last_name'  => ['required', 'string', 'max:150'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'role'       => ['required', 'in:STUDENT,PORTER,ADMIN'],
            'hostel_id'  => ['nullable', 'exists:hostels,id'],
            'is_active'  => ['nullable', 'boolean'],
            'is_approved'=> ['nullable', 'boolean'],
        ];
        if ($isCreate) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        } else {
            $rules['password'] = ['nullable', 'confirmed', Password::min(8)];
        }

        $data = $request->validate($rules);

        // Prevent self-demotion/deactivation
        if ($id && $id === Auth::id()) {
            if (!($data['is_active'] ?? true) || ($data['role'] !== User::ROLE_ADMIN)) {
                return back()->with('error', 'You cannot deactivate or demote your own account.');
            }
        }

        $payload = [
            'email'       => strtolower(trim($data['email'])),
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'phone'       => $data['phone'] ?? '',
            'role'        => $data['role'],
            'hostel_id'   => $data['hostel_id'] ?? null,
            'is_active'   => $data['is_active'] ?? true,
            'is_approved' => $data['is_approved'] ?? true,
        ];
        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        if ($isCreate) {
            User::create($payload);
        } else {
            $editUser->update($payload);
        }

        return redirect()->route('admin.users')->with('success', 'User saved.');
    }

    public function userAction(Request $request, int $id)
    {
        $request->validate(['action' => ['required', 'in:approve,activate,deactivate']]);
        $target = User::findOrFail($id);
        $action = $request->input('action');

        if ($target->id === Auth::id() && $action === 'deactivate') {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        match ($action) {
            'approve'    => $target->update(['is_approved' => true]),
            'activate'   => $target->update(['is_active' => true]),
            'deactivate' => $target->update(['is_active' => false]),
        };

        return redirect()->route('admin.users')
            ->with('success', "{$target->full_name} {$action}d.");
    }

    // ================================================================ Hostels
    public function hostels()
    {
        $hostels = Hostel::withCount([
            'members as students_count' => fn ($q) => $q->where('role', User::ROLE_STUDENT),
            'members as porters_count'  => fn ($q) => $q->where('role', User::ROLE_PORTER),
            'complaints as open_complaints_count' => fn ($q) => $q->whereIn('status', [
                Complaint::STATUS_PENDING, Complaint::STATUS_IN_PROGRESS,
            ]),
            'complaints as total_complaints_count',
        ])->get();

        return view('admin.hostels.index', compact('hostels'));
    }

    public function showHostelForm(int $id = null)
    {
        $hostel = $id ? Hostel::findOrFail($id) : null;
        return view('admin.hostels.form', compact('hostel'));
    }

    public function saveHostel(Request $request, int $id = null)
    {
        $hostel = $id ? Hostel::findOrFail($id) : null;
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:120', "unique:hostels,name" . ($id ? ",{$id}" : '')],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($hostel) {
            $hostel->update($data);
        } else {
            Hostel::create($data);
        }

        return redirect()->route('admin.hostels')->with('success', 'Hostel saved.');
    }

    public function toggleHostel(int $id)
    {
        $hostel = Hostel::findOrFail($id);
        $hostel->update(['is_active' => !$hostel->is_active]);
        $state = $hostel->is_active ? 'active' : 'inactive';
        return back()->with('success', "{$hostel->name} is now {$state}.");
    }

    // ================================================================ Categories
    public function categories()
    {
        $categories = ComplaintCategory::withCount('complaints')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function showCategoryForm(int $id = null)
    {
        $category = $id ? ComplaintCategory::findOrFail($id) : null;
        return view('admin.categories.form', compact('category'));
    }

    public function saveCategory(Request $request, int $id = null)
    {
        $category = $id ? ComplaintCategory::findOrFail($id) : null;
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:50', "unique:complaint_categories,name" . ($id ? ",{$id}" : '')],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        if ($category) {
            $category->update($data);
        } else {
            ComplaintCategory::create($data);
        }

        return redirect()->route('admin.categories')->with('success', 'Category saved.');
    }

    public function toggleCategory(int $id)
    {
        $category = ComplaintCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        $state = $category->is_active ? 'active' : 'inactive';
        return back()->with('success', "{$category->name} is now {$state}.");
    }
}
