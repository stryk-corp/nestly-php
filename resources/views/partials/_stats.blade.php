<div class="stats">
  <a class="stat {{ request('status') ? '' : 'on' }}" href="{{ url()->current() }}"><div class="ic ic-g"><i class="fa-solid fa-folder-open"></i></div><div><b data-count="{{ $stats['total'] ?? 0 }}">0</b><span>All complaints</span></div></a>
  <a class="stat {{ request('status') === 'PENDING' ? 'on' : '' }}" href="{{ url()->current() }}?status=PENDING"><div class="ic ic-y"><i class="fa-solid fa-clock"></i></div><div><b data-count="{{ $stats['pending'] ?? 0 }}">0</b><span>Pending</span></div></a>
  <a class="stat {{ request('status') === 'IN_PROGRESS' ? 'on' : '' }}" href="{{ url()->current() }}?status=IN_PROGRESS"><div class="ic ic-b"><i class="fa-solid fa-person-digging"></i></div><div><b data-count="{{ $stats['in_progress'] ?? 0 }}">0</b><span>In progress</span></div></a>
  <a class="stat {{ request('status') === 'RESOLVED' ? 'on' : '' }}" href="{{ url()->current() }}?status=RESOLVED"><div class="ic ic-c"><i class="fa-solid fa-circle-check"></i></div><div><b data-count="{{ $stats['resolved'] ?? 0 }}">0</b><span>Resolved</span></div></a>
</div>
