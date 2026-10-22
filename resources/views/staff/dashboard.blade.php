@extends('layouts.app')
@section('title', 'My Assignments')

@section('content')
<div class="container page">
    <div class="page-header">
        <div>
            <h1 class="page-title">My Assignments</h1>
            <p class="page-sub">Complaints assigned to you for repair</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-pill"><span class="sp-num">{{ $stats['total'] }}</span><span class="sp-lbl">Total</span></div>
        <div class="stat-pill pending"><span class="sp-num">{{ $stats['pending'] }}</span><span class="sp-lbl">Pending</span></div>
        <div class="stat-pill progress"><span class="sp-num">{{ $stats['in_progress'] }}</span><span class="sp-lbl">In Progress</span></div>
        <div class="stat-pill resolved"><span class="sp-num">{{ $stats['resolved'] }}</span><span class="sp-lbl">Resolved</span></div>
    </div>

    <form method="GET" class="filter-bar">
        <select name="status" class="filter-select">
            <option value="">All statuses</option>
            @foreach(['PENDING' => 'Pending', 'IN_PROGRESS' => 'In Progress', 'RESOLVED' => 'Resolved'] as $v => $l)
                <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" class="filter-search">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('staff.dashboard') }}" class="btn btn-ghost btn-sm">Clear</a>
    </form>

    @if($complaints->isEmpty())
        <div class="empty-state"><div class="empty-icon">🔧</div><p>No assignments yet.</p></div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Title</th><th>Hostel</th><th>Priority</th><th>Status</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($complaints as $c)
                    <tr>
                        <td class="text-muted">#{{ $c->id }}</td>
                        <td><strong>{{ $c->title }}</strong></td>
                        <td>{{ $c->hostel->name }}</td>
                        <td><span class="badge priority-{{ strtolower($c->priority) }}">{{ $c->priority_label }}</span></td>
                        <td><span class="badge status-{{ strtolower(str_replace('_','-',$c->status)) }}">{{ $c->status_label }}</span></td>
                        <td class="text-muted">{{ $c->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('staff.complaint', $c->id) }}" class="link-btn">Open →</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $complaints->links() }}</div>
    @endif
</div>
@endsection
