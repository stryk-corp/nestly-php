@extends('layouts.app')
@section('title', "Complaint #{{ $complaint->id }}")

@section('content')
<div class="container page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Complaint #{{ $complaint->id }}</h1>
            <p class="page-sub">{{ $complaint->hostel->name }} &mdash; {{ $complaint->category->name }}</p>
        </div>
        <a href="{{ route('staff.dashboard') }}" class="btn btn-outline">← Back</a>
    </div>

    <div class="detail-grid">
        <div class="detail-main">
            <div class="detail-card">
                <h2 class="detail-title">{{ $complaint->title }}</h2>
                <p class="detail-body">{{ $complaint->description }}</p>
                @if($complaint->image)
                    <div class="detail-image">
                        <img src="{{ asset('storage/' . $complaint->image) }}" alt="Complaint photo">
                    </div>
                @endif
            </div>

            <div class="action-card">
                <h3>Post progress update</h3>
                <form method="POST" action="{{ route('staff.complaint', $complaint->id) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            @foreach(\App\Models\Complaint::STATUS_LABELS as $val => $lbl)
                                @if(\App\Models\Complaint::STATUS_ORDER[$val] >= \App\Models\Complaint::STATUS_ORDER[$complaint->status])
                                    <option value="{{ $val }}" {{ $val === $complaint->status ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Note</label>
                        <textarea name="message" rows="4" class="form-control" placeholder="Describe what you've done or what's next…" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post update</button>
                </form>
            </div>

            <div class="timeline-card">
                <h3 class="timeline-heading">Activity Timeline</h3>
                <div class="timeline">
                    @foreach($updates as $update)
                    <div class="timeline-item">
                        <div class="tl-dot status-{{ strtolower(str_replace('_', '-', $update->status_changed_to)) }}"></div>
                        <div class="tl-body">
                            <div class="tl-meta">
                                <strong>{{ $update->author->full_name }}</strong>
                                &mdash; {{ $update->created_at->format('d M Y, H:i') }}
                                <span class="badge status-{{ strtolower(str_replace('_', '-', $update->status_changed_to)) }} ml-2">
                                    {{ \App\Models\Complaint::STATUS_LABELS[$update->status_changed_to] }}
                                </span>
                            </div>
                            <p class="tl-msg">{{ $update->message }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="detail-sidebar">
            <div class="sidebar-card">
                <div class="sb-row"><span class="sb-label">Status</span>
                    <span class="badge status-{{ strtolower(str_replace('_','-',$complaint->status)) }}">{{ $complaint->status_label }}</span></div>
                <div class="sb-row"><span class="sb-label">Priority</span>
                    <span class="badge priority-{{ strtolower($complaint->priority) }}">{{ $complaint->priority_label }}</span></div>
                <div class="sb-row"><span class="sb-label">Hostel</span><span>{{ $complaint->hostel->name }}</span></div>
                <div class="sb-row"><span class="sb-label">Room</span><span>{{ $complaint->room?->room_number ?? '—' }}</span></div>
                <div class="sb-row"><span class="sb-label">Student</span><span>{{ $complaint->student->full_name }}</span></div>
            </div>
        </aside>
    </div>
</div>
@endsection
