@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<section class="banner">
  <div class="b-left">
    <div class="b-icon"><i class="fa-solid fa-graduation-cap"></i></div>
    <div>
      <div class="date" id="today"></div>
      <h1>Welcome back, {{ auth()->user()->first_name ?: auth()->user()->email }}!</h1>
      <p>
        @if(auth()->user()->hostel)
          Resident in <strong>{{ auth()->user()->hostel->name }}</strong>
        @else
          Hostel complaint &amp; resolution portal
        @endif
      </p>
    </div>
  </div>
  <a href="{{ route('student.submit') }}" class="btn btn-light"><i class="fa-solid fa-circle-plus"></i> Submit new complaint</a>
</section>

<div class="nudge" id="nudge">
  <i class="fa-solid fa-star"></i>
  <span id="nudgeText"></span>
  <button class="btn btn-gold btn-sm" id="nudgeBtn" type="button">Show them</button>
</div>

<div class="stats">
  <button class="stat" data-filter="all" type="button">
    <div class="ic ic-g"><i class="fa-solid fa-folder-open"></i></div>
    <div><b data-count="{{ $stats['total'] ?? 0 }}">0</b><span>Total complaints logged</span></div>
  </button>
  <button class="stat" data-filter="pending" type="button">
    <div class="ic ic-y"><i class="fa-solid fa-clock"></i></div>
    <div><b data-count="{{ $stats['pending'] ?? 0 }}">0</b><span>Pending review</span></div>
  </button>
  <button class="stat" data-filter="progress" type="button">
    <div class="ic ic-b"><i class="fa-solid fa-person-digging"></i></div>
    <div><b data-count="{{ $stats['in_progress'] ?? 0 }}">0</b><span>In progress</span></div>
  </button>
  <button class="stat" data-filter="resolved" type="button">
    <div class="ic ic-c"><i class="fa-solid fa-circle-check"></i></div>
    <div><b data-count="{{ $stats['resolved'] ?? 0 }}">0</b><span>Successfully resolved</span></div>
  </button>
</div>

<section class="panel">
  <div class="p-head">
    <div>
      <h2>My complaint tickets</h2>
      <p>Track real-time progress and resolution notes from your porter.</p>
    </div>
  </div>

  @if($complaints->count() > 0)
    <div class="tools">
      <div class="search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" id="q" placeholder="Search this page by title or ticket" aria-label="Search complaints"></div>
      <div class="chips" id="chips">
        <button class="chip on" data-filter="all" type="button">All</button>
        <button class="chip" data-filter="pending" type="button">Pending</button>
        <button class="chip" data-filter="progress" type="button">In progress</button>
        <button class="chip" data-filter="resolved" type="button">Resolved</button>
      </div>
    </div>

    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>Ticket</th><th>Subject &amp; issue</th><th>Category</th><th>Status</th><th>Submitted</th><th class="t-right">Action</th>
          </tr>
        </thead>
        <tbody id="rows">
          @foreach($complaints as $complaint)
          <tr data-status="{{ strtolower($complaint->status) }}" data-text="{{ $complaint->id }} {{ strtolower($complaint->title) }}" data-rate="{{ ($complaint->status === 'RESOLVED' && !$complaint->student_rating) ? '1' : '' }}">
            <td data-label="Ticket"><span class="tid">#{{ $complaint->id }}</span></td>
            <td data-label="Issue">
              <div>
                <div class="t-title">{{ $complaint->title }}</div>
                @if($complaint->hostel || $complaint->room_number)
                  <div class="t-sub"><i class="fa-solid fa-location-dot"></i> {{ $complaint->hostel?->name }} @if($complaint->room_number)&bull; Room {{ $complaint->room_number }}@endif</div>
                @endif
              </div>
            </td>
            <td data-label="Category"><span class="badge cat">{{ $complaint->category?->name ?: 'General' }}</span></td>
            <td data-label="Status"><span class="badge badge-{{ strtolower(str_replace('_', '-', $complaint->status)) }}"><span class="badge-dot"></span> {{ $complaint->status_label }}</span></td>
            <td data-label="Submitted">{{ $complaint->created_at->format('M d, Y') }}</td>
            <td data-label="Action" class="t-right">
              <div class="act">
                <a href="{{ route('student.complaint', $complaint->id) }}" class="btn btn-line btn-sm"><i class="fa-solid fa-eye"></i> Details</a>
                @if($complaint->status === 'RESOLVED' && !$complaint->student_rating)
                  <a href="{{ route('student.rate', $complaint->id) }}" class="btn btn-gold btn-sm"><i class="fa-solid fa-star"></i> Rate</a>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="no-match" id="noMatch"><i class="fa-regular fa-face-meh"></i>No tickets match your search or filter on this page.</div>
    </div>

    @if($complaints->hasPages())
      <div class="pager">
        @if(!$complaints->onFirstPage())
          <a href="{{ $complaints->previousPageUrl() }}" class="btn btn-line btn-sm"><i class="fa-solid fa-arrow-left"></i> Previous</a>
        @endif
        <span>Page {{ $complaints->currentPage() }} of {{ $complaints->lastPage() }}</span>
        @if($complaints->hasMorePages())
          <a href="{{ $complaints->nextPageUrl() }}" class="btn btn-line btn-sm">Next <i class="fa-solid fa-arrow-right"></i></a>
        @endif
      </div>
    @endif
  @else
    <div class="empty">
      <div class="art"><i class="fa-solid fa-clipboard-check"></i></div>
      <h3>No complaints logged</h3>
      <p>You currently have no active or past repair requests. Everything in your room is in order!</p>
      <a href="{{ route('student.submit') }}" class="btn btn-solid"><i class="fa-solid fa-circle-plus"></i> Submit your first ticket</a>
    </div>
  @endif
</section>
@endsection

@push('scripts')
<script>
(function(){
  // Filtering (current page)
  var rows=Array.prototype.slice.call(document.querySelectorAll('#rows tr'));
  if(!rows.length)return;
  var q=document.getElementById('q'),chips=document.querySelectorAll('.chip'),cards=document.querySelectorAll('.stat'),nm=document.getElementById('noMatch');
  var active='all';
  function group(s){
    if(s==='pending')return 'pending';
    if(s==='resolved'||s==='closed')return 'resolved';
    if(s==='assigned'||s==='in_progress'||s==='in-progress'||s==='inprogress')return 'progress';
    return 'other';
  }
  function apply(){
    var term=(q.value||'').trim().toLowerCase(),shown=0;
    rows.forEach(function(r){
      var okF=active==='all'||group(r.dataset.status)===active;
      var okQ=!term||r.dataset.text.indexOf(term.replace('#',''))>-1;
      var show=okF&&okQ;r.style.display=show?'':'none';if(show)shown++;
    });
    if(nm)nm.classList.toggle('show',shown===0);
    chips.forEach(function(c){c.classList.toggle('on',c.dataset.filter===active)});
    cards.forEach(function(c){c.classList.toggle('on',c.dataset.filter===active&&active!=='all')});
  }
  function setFilter(f,scroll){active=f;apply();if(scroll)document.querySelector('.panel').scrollIntoView({behavior:'smooth',block:'start'})}
  chips.forEach(function(c){c.addEventListener('click',function(){setFilter(c.dataset.filter)})});
  cards.forEach(function(c){c.addEventListener('click',function(){setFilter(c.dataset.filter===active?'all':c.dataset.filter,true)})});
  if(q)q.addEventListener('input',apply);

  // Stagger rows
  rows.forEach(function(r,i){r.style.animationDelay=(0.45+i*0.05)+'s'});

  // Rating nudge
  var need=rows.filter(function(r){return r.dataset.rate==='1'});
  if(need.length){
    var n=document.getElementById('nudge');
    document.getElementById('nudgeText').textContent=need.length+(need.length===1?' resolved repair is':' resolved repairs are')+' waiting for your rating.';
    n.classList.add('show');
    document.getElementById('nudgeBtn').addEventListener('click',function(){
      need[0].scrollIntoView({behavior:'smooth',block:'center'});
      need.forEach(function(r){r.style.outline='2px solid var(--gold)';r.style.outlineOffset='2px';r.style.borderRadius='16px'});
    });
  }
})();
</script>
@endpush
