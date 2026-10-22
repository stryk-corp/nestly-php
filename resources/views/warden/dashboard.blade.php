@extends('layouts.app')
@section('title', 'Warden Dashboard')

@section('content')
<div class="container page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Hostel Dashboard</h1>
            <p class="page-sub">Complaints in {{ auth()->user()->hostel->name ?? 'your hostel' }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-pill"><span class="sp-num">{{ $stats['total'] }}</span><span class="sp-lbl">Total</span></div>
        <div class="stat-pill pending"><span class="sp-num">{{ $stats['pending'] }}</span><span class="sp-lbl">Pending</span></div>
        <div class="stat-pill progress"><span class="sp-num">{{ $stats['in_progress'] }}</span><span class="sp-lbl">In Progress</span></div>
        <div class="stat-pill resolved"><span class="sp-num">{{ $stats['resolved'] }}</span><span class="sp-lbl">Resolved</span></div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="filter-bar">
        <select name="status" class="filter-select">
            <option value="">All statuses</option>
            @foreach(['PENDING' => 'Pending', 'IN_PROGRESS' => 'In Progress', 'RESOLVED' => 'Resolved'] as $v => $l)
                <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <select name="category" class="filter-select">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="priority" class="filter-select">
            <option value="">All priorities</option>
            @foreach(['LOW' => 'Low', 'MEDIUM' => 'Medium', 'HIGH' => 'High', 'URGENT' => 'Urgent'] as $v => $l)
                <option value="{{ $v }}" {{ request('priority') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" class="filter-search">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('warden.dashboard') }}" class="btn btn-ghost btn-sm">Clear</a>
    </form>

    {{-- Table --}}
    @if($complaints->isEmpty())
        <div class="empty-state"><div class="empty-icon">✅</div><p>No complaints match your filters.</p></div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Student</th><th>Title</th><th>Category</th>
                        <th>Priority</th><th>Status</th><th>Date</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $c)
                    <tr>
                        <td class="text-muted">#{{ $c->id }}</td>
                        <td>{{ $c->student->full_name }}</td>
                        <td><strong>{{ $c->title }}</strong></td>
                        <td>{{ $c->category->name }}</td>
                        <td><span class="badge priority-{{ strtolower($c->priority) }}">{{ $c->priority_label }}</span></td>
                        <td><span class="badge status-{{ strtolower(str_replace('_','-',$c->status)) }}">{{ $c->status_label }}</span></td>
                        <td class="text-muted">{{ $c->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('warden.complaint', $c->id) }}" class="link-btn">Manage →</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $complaints->links() }}</div>
    @endif
</div>
@endsection
