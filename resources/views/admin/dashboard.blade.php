@extends('layouts.app')
@section('title', 'All complaints')

@section('content')
<section class="banner">
  <div class="b-left">
    <div class="b-icon"><i class="fa-solid fa-landmark"></i></div>
    <div>
      <div class="date" id="today"></div>
      <h1>Complaints across all hostels</h1>
      <p>Welcome back, <strong>{{ auth()->user()->first_name ?: auth()->user()->email }}</strong>. Monitor progress in every hostel from one place.</p>
    </div>
  </div>
  <a href="{{ route('admin.users') }}" class="btn btn-light"><i class="fa-solid fa-users"></i> Manage users</a>
</section>

@if(isset($awaiting_approval) && $awaiting_approval > 0)
<div class="nudge" style="display:flex"><i class="fa-solid fa-user-clock"></i><span><strong>{{ $awaiting_approval }}</strong> account{{ $awaiting_approval > 1 ? 's' : '' }} waiting for your approval.</span><a href="{{ route('admin.users') }}?state=pending" class="btn btn-gold btn-sm">Review</a></div>
@endif

@include('partials._stats')

<div class="stack">
  <section class="panel">
    <div class="p-head"><div><h2>Complaints by hostel</h2><p>Select a hostel to see only its complaints.</p></div></div>
    <div class="tbl-wrap" style="overflow-x:auto">
      <table class="mini">
        <thead><tr><th>Hostel</th><th class="n">Total</th><th class="n">Pending</th><th class="n">In progress</th><th class="n">Resolved</th></tr></thead>
        <tbody>
          @foreach($per_hostel as $h)
          <tr>
            <td><a href="?hostel={{ $h->id }}" style="font-weight:600;color:var(--deep)">{{ $h->name }}</a>@if(isset($h->is_active) && !$h->is_active) <span class="badge badge-off">Inactive</span>@endif</td>
            <td class="n">{{ $h->complaints_count ?? ($h->total ?? 0) }}</td>
            <td class="n">{{ $h->pending_count ?? ($h->pending ?? 0) }}</td>
            <td class="n">{{ $h->in_progress_count ?? ($h->in_progress ?? 0) }}</td>
            <td class="n">{{ $h->resolved_count ?? ($h->resolved ?? 0) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>

  <section class="panel">
    <div class="p-head"><div><h2>Complaint list</h2><p>Filter by hostel, category and status, then open any complaint to see its history.</p></div></div>
    @include('partials._filters')
    @include('partials._complaint_table')
  </section>
</div>
@endsection
