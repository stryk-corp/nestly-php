@php
  $detailRouteName = $detail_route ?? (
    auth()->user()->isAdmin() ? 'admin.complaint' : (
      auth()->user()->isPorter() ? 'porter.complaint' : 'student.complaint'
    )
  );
@endphp

@if(isset($complaints) && $complaints->count() > 0)
<div class="tbl-wrap">
  <table>
    <thead><tr><th>Ticket</th><th>Issue</th><th>Category</th><th>Status</th><th>Submitted</th><th class="t-right">Action</th></tr></thead>
    <tbody>
      @foreach($complaints as $complaint)
      <tr>
        <td data-label="Ticket"><span class="tid">#{{ $complaint->id }}</span></td>
        <td data-label="Issue">
          <div>
            <div class="t-title">{{ $complaint->title }}</div>
            <div class="t-sub"><i class="fa-regular fa-user"></i> {{ $complaint->student?->full_name ?: ($complaint->student?->email ?: 'Unknown') }}</div>
            <div class="t-sub"><i class="fa-solid fa-location-dot"></i> {{ $complaint->hostel?->name ?: 'N/A' }}@if($complaint->room_number) &bull; Room {{ $complaint->room_number }}@endif</div>
          </div>
        </td>
        <td data-label="Category"><span class="badge cat">{{ $complaint->category?->name ?: 'General' }}</span></td>
        <td data-label="Status"><span class="badge badge-{{ strtolower(str_replace('_', '-', $complaint->status)) }}"><span class="badge-dot"></span> {{ $complaint->status_label }}</span></td>
        <td data-label="Submitted">{{ $complaint->created_at->format('M d, Y') }}</td>
        <td data-label="Action" class="t-right"><div class="act"><a href="{{ route($detailRouteName, $complaint->id) }}" class="btn btn-line btn-sm"><i class="fa-solid fa-eye"></i> Open</a></div></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@if($complaints->hasPages())
<div class="pager">
  @if($complaints->onFirstPage())
  @else
    <a href="{{ $complaints->previousPageUrl() }}" class="btn btn-line btn-sm"><i class="fa-solid fa-arrow-left"></i> Previous</a>
  @endif
  <span>Page {{ $complaints->currentPage() }} of {{ $complaints->lastPage() }}</span>
  @if($complaints->hasMorePages())
    <a href="{{ $complaints->nextPageUrl() }}" class="btn btn-line btn-sm">Next <i class="fa-solid fa-arrow-right"></i></a>
  @endif
</div>
@endif
@else
<div class="empty" style="display:block">
  <div class="art"><i class="fa-solid fa-clipboard-check"></i></div>
  <h3>No complaints found</h3>
  <p>{{ request()->hasAny(['q', 'status', 'hostel', 'category']) ? 'Nothing matches these filters. Try clearing them.' : 'There are no complaints here yet.' }}</p>
  @if(request()->hasAny(['q', 'status', 'hostel', 'category']))
    <a href="{{ url()->current() }}" class="btn btn-line">Clear filters</a>
  @endif
</div>
@endif
