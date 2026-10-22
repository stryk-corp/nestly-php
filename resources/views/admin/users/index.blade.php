@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="page-head">
  <div><h1>Users</h1><p>Students, porters and administrators.</p></div>
  <a href="{{ route('admin.users.add') }}" class="btn btn-solid"><i class="fa-solid fa-user-plus"></i> Add user</a>
</div>

@if(isset($awaiting_approval) && $awaiting_approval > 0)
<div class="nudge" style="display:flex"><i class="fa-solid fa-user-clock"></i><span><strong>{{ $awaiting_approval }}</strong> account{{ $awaiting_approval > 1 ? 's' : '' }} waiting for approval.</span><a href="?state=pending" class="btn btn-gold btn-sm">Show them</a></div>
<div style="height:1rem"></div>
@endif

<section class="panel">
  <form method="get" class="filters">
    <div class="f grow"><label for="uq">Search</label><input type="search" id="uq" name="q" value="{{ request('q') }}" placeholder="Name or email"></div>
    <div class="f"><label for="ur">Role</label>
      <select id="ur" name="role"><option value="">All roles</option>
        @foreach(\App\Models\User::ROLE_LABELS as $val => $lbl)
          <option value="{{ $val }}" {{ request('role') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
        @endforeach
      </select>
    </div>
    <div class="f"><label for="us">Show</label>
      <select id="us" name="state"><option value="">All accounts</option><option value="pending" {{ request('state') === 'pending' ? 'selected' : '' }}>Waiting for approval</option><option value="inactive" {{ request('state') === 'inactive' ? 'selected' : '' }}>Deactivated</option></select></div>
    <div class="toolbar"><button type="submit" class="btn btn-solid btn-sm"><i class="fa-solid fa-filter"></i> Apply</button><a href="{{ route('admin.users') }}" class="btn btn-line btn-sm">Clear</a></div>
  </form>

  @if($users->count() > 0)
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>Name</th><th>Role</th><th>Hostel</th><th>Status</th><th>Joined</th><th class="t-right">Actions</th></tr></thead>
      <tbody>
        @foreach($users as $u)
        <tr>
          <td data-label="Name"><div><div class="t-title">{{ $u->full_name }}</div><div class="t-sub">{{ $u->email }}</div></div></td>
          <td data-label="Role"><span class="badge cat">{{ $u->role_label }}</span></td>
          <td data-label="Hostel">@if($u->hostel){{ $u->hostel->name }}@else<span style="color:var(--mute)">None</span>@endif</td>
          <td data-label="Status">
            @if(!$u->is_approved)<span class="badge badge-warn">Awaiting approval</span>
            @elseif(!$u->is_active)<span class="badge badge-off">Deactivated</span>
            @else<span class="badge badge-ok">Active</span>@endif
          </td>
          <td data-label="Joined">{{ $u->created_at->format('M d, Y') }}</td>
          <td data-label="Actions" class="t-right">
            <div class="act">
              @if(!$u->is_approved)
              <form method="POST" action="{{ route('admin.users.action', $u->id) }}" class="inline">
                @csrf
                <input type="hidden" name="action" value="approve">
                <input type="hidden" name="next" value="{{ request()->fullUrl() }}">
                <button class="btn btn-gold btn-sm" type="submit"><i class="fa-solid fa-check"></i> Approve</button>
              </form>
              @endif
              <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-line btn-sm"><i class="fa-solid fa-pen"></i> Edit</a>
              @if($u->id !== auth()->id())
              <form method="POST" action="{{ route('admin.users.action', $u->id) }}" class="inline">
                @csrf
                <input type="hidden" name="action" value="{{ $u->is_active ? 'deactivate' : 'activate' }}">
                <input type="hidden" name="next" value="{{ request()->fullUrl() }}">
                <button class="btn btn-line btn-sm" type="submit">{{ $u->is_active ? 'Deactivate' : 'Activate' }}</button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
  <div class="pager">
    @if(!$users->onFirstPage())<a href="{{ $users->previousPageUrl() }}" class="btn btn-line btn-sm"><i class="fa-solid fa-arrow-left"></i> Previous</a>@endif
    <span>Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
    @if($users->hasMorePages())<a href="{{ $users->nextPageUrl() }}" class="btn btn-line btn-sm">Next <i class="fa-solid fa-arrow-right"></i></a>@endif
  </div>
  @endif
  @else
  <div class="empty" style="display:block"><div class="art"><i class="fa-solid fa-users"></i></div><h3>No users found</h3><p>Try a different search or filter.</p></div>
  @endif
</section>
@endsection
