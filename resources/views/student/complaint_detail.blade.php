@extends('layouts.app')
@section('title', 'Complaint #' . $complaint->id)

@section('content')
<div class="bar">
  <a href="{{ route('student.dashboard') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back to dashboard</a>
  <div class="tags">
    <span class="badge badge-{{ strtolower(str_replace('_', '-', $complaint->status)) }}"><span class="badge-dot"></span> {{ $complaint->status_label }}</span>
  </div>
</div>

<section class="hero">
  <div class="hero-top">
    <div>
      <div class="eyebrow">Complaint #{{ $complaint->id }} &bull; {{ $complaint->category?->name ?: 'General' }}</div>
      <h1>{{ $complaint->title }}</h1>
    </div>
    @if($complaint->status === 'RESOLVED' && !$complaint->student_rating)
      <a href="{{ route('student.rate', $complaint->id) }}" class="btn btn-gold"><i class="fa-solid fa-star"></i> Rate resolution</a>
    @endif
  </div>
  <div class="meta">
    <span><i class="fa-regular fa-clock"></i> Submitted {{ $complaint->created_at->format('F d, Y \a\t h:i A') }}</span>
    @if($complaint->hostel)
      <span><i class="fa-solid fa-building"></i> {{ $complaint->hostel->name }}@if($complaint->room_number) — Room {{ $complaint->room_number }}@endif</span>
    @endif
  </div>
</section>

<section class="track" id="track" data-status="{{ strtolower($complaint->status) }}">
  <h3>Where this complaint stands</h3>
  <div class="steps">
    <div class="fill" id="fill"></div>
    <div class="st" data-i="1"><div class="c"><i class="fa-solid fa-paper-plane"></i></div>Submitted</div>
    <div class="st" data-i="2"><div class="c"><i class="fa-solid fa-wrench"></i></div>In progress</div>
    <div class="st" data-i="3"><div class="c"><i class="fa-solid fa-check-double"></i></div>Resolved</div>
  </div>
</section>

<div class="grid">
  <div class="col">
    <section class="card">
      <div class="label"><i class="fa-solid fa-align-left"></i> Problem description</div>
      <p class="desc">{{ $complaint->description }}</p>
    </section>

    @if($complaint->student_rating)
    <section class="rating">
      <div class="top">
        <b>Your feedback</b>
        <span class="stars" aria-label="{{ $complaint->student_rating }} out of 5">
          @for($i = 1; $i <= 5; $i++)
            @if($i <= $complaint->student_rating)<i class="fa-solid fa-star"></i>@else<i class="fa-regular fa-star"></i>@endif
          @endfor
          <span class="n">{{ $complaint->student_rating }}/5</span>
        </span>
      </div>
      @if($complaint->student_feedback)<blockquote>&ldquo;{{ $complaint->student_feedback }}&rdquo;</blockquote>@endif
    </section>
    @endif

    <section class="card">
      <h3><i class="fa-solid fa-timeline"></i> Progress &amp; resolution history</h3>
      <p class="sub">Chronological updates from porters and administrators.</p>

      @if($complaint->updates->count() > 0)
        <ul class="tl">
          @foreach($complaint->updates as $idx => $update)
          <li style="animation-delay: .{{ $idx + 1 }}s">
            <span class="av">{{ strtoupper(substr($update->author?->first_name ?: ($update->author?->email ?: 'U'), 0, 1)) }}</span>
            <div class="bd">
              <div class="hd">
                <span class="who">
                  {{ $update->author?->full_name ?: ($update->author?->email ?: 'System') }}
                  <span class="role">{{ $update->author?->role_label }}</span>
                </span>
                <time>{{ $update->created_at->format('M d, Y, h:i A') }}</time>
              </div>
              <p>{{ $update->message }}</p>
              @if($update->status_changed_to)
                <div class="chg">
                  <span class="badge badge-{{ strtolower(str_replace('_', '-', $update->status_changed_to)) }}"><i class="fa-solid fa-arrow-right" style="font-size:.65rem"></i> Status: <span class="js-status">{{ str_replace('_', ' ', $update->status_changed_to) }}</span></span>
                </div>
              @endif
            </div>
          </li>
          @endforeach
        </ul>
      @else
        <div class="note"><i class="fa-regular fa-hourglass-half"></i><span>No updates yet. This complaint is waiting for the porter to review it.</span></div>
      @endif
    </section>
  </div>

  <aside class="aside stack">
    <div class="card" style="animation-delay:.25s">
      <h3 style="margin-bottom:1.1rem">Complaint summary</h3>
      <div class="facts">
        <div class="fact"><span class="ic" style="background:var(--sagebg);color:var(--deep)"><i class="fa-solid fa-hashtag"></i></span><div><small>Complaint</small><b>#{{ $complaint->id }}</b></div></div>
        <div class="fact"><span class="ic" style="background:var(--skybg);color:#3f7084"><i class="fa-solid fa-tag"></i></span><div><small>Category</small><b>{{ $complaint->category?->name ?: 'General' }}</b></div></div>
        @if($complaint->hostel)
        <div class="fact"><span class="ic" style="background:var(--goldbg);color:#82641a"><i class="fa-solid fa-building"></i></span><div><small>Hostel</small><b>{{ $complaint->hostel->name }}</b></div></div>
        @endif
        @if($complaint->room_number)
        <div class="fact"><span class="ic" style="background:var(--skybg);color:#3f7084"><i class="fa-solid fa-door-open"></i></span><div><small>Room</small><b>{{ $complaint->room_number }}</b></div></div>
        @endif
      </div>

      @if($complaint->status === 'RESOLVED' && !$complaint->student_rating)
        <div class="cta-side"><a href="{{ route('student.rate', $complaint->id) }}" class="btn btn-gold"><i class="fa-solid fa-star"></i> Rate this resolution</a></div>
      @endif
      <div class="cta-side"><a href="{{ route('student.dashboard') }}" class="btn btn-solid"><i class="fa-solid fa-table-cells-large"></i> All my complaints</a></div>

      <div class="help"><b><i class="fa-solid fa-circle-info"></i> Need to add something?</b>Talk to your hostel porter and mention complaint #{{ $complaint->id }}.</div>
    </div>
  </aside>
</div>
@endsection

@push('scripts')
<script>
(function(){
  var track=document.getElementById('track'),status=(track.dataset.status||'').toLowerCase();
  var stage=1;
  if(status==='in_progress'||status==='in-progress')stage=2;
  else if(status==='resolved')stage=3;
  document.querySelectorAll('.st').forEach(function(el){
    var i=Number(el.dataset.i);
    if(i<stage||stage===3)el.classList.add('done');
    else if(i===stage)el.classList.add('now');
  });
  var fill=document.getElementById('fill');
  requestAnimationFrame(function(){
    fill.style.width=((stage-1)/2*75)+'%';
  });
})();
</script>
@endpush
