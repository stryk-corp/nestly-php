@extends('layouts.app')
@section('title', 'Submit Complaint')

@section('content')
<a href="{{ route('student.dashboard') }}" class="back" style="margin-bottom:1.2rem;display:inline-flex"><i class="fa-solid fa-arrow-left"></i> Back to dashboard</a>

<section class="hero">
  <div class="hero-top" style="align-items:center">
    <div style="display:flex;align-items:center;gap:1.2rem">
      <div style="width:58px;height:58px;border-radius:18px;background:rgba(255,255,255,.14);display:grid;place-items:center;font-size:1.4rem;flex-shrink:0"><i class="fa-solid fa-pen-to-square"></i></div>
      <div>
        <h1>Submit a complaint</h1>
        <p style="color:rgba(255,255,255,.8);font-size:.92rem;max-width:560px">Describe your hostel issue clearly so the porter can respond promptly.</p>
      </div>
    </div>
  </div>
</section>

<div class="grid">
  <section class="card">
    <h3 style="font-size:1.4rem"><i class="fa-solid fa-pen-nib"></i> Tell us what's wrong</h3>
    <p class="lead">Fields marked <span class="req">*</span> are required.</p>

    <form method="POST" action="{{ route('student.store') }}" id="complaintForm">
      @csrf

      <div class="form-group @error('title') has-error @enderror">
        <label for="title">Title / Short summary <span class="req">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Broken desk or Leaking bathroom tap" required maxlength="120">
        @error('title')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
      </div>

      <div class="two">
        <div class="form-group @error('hostel_id') has-error @enderror">
          <label for="hostel_id">Hostel <span class="req">*</span></label>
          <select id="hostel_id" name="hostel_id" required>
            <option value="">Select your hostel</option>
            @foreach($hostels as $hostel)
              <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>{{ $hostel->name }}</option>
            @endforeach
          </select>
          @error('hostel_id')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
        </div>

        <div class="form-group @error('room_number') has-error @enderror">
          <label for="room_number">Room number <small style="color:var(--mute);font-weight:400">(optional)</small></label>
          <input type="text" id="room_number" name="room_number" value="{{ old('room_number') }}" placeholder="e.g. A12 or Room 4" maxlength="20">
          @error('room_number')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
        </div>
      </div>

      <div class="form-group @error('category_id') has-error @enderror">
        <label for="category_id">Category <span class="req">*</span></label>
        <select id="category_id" name="category_id" required>
          <option value="">Select a category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
          @endforeach
        </select>
        @error('category_id')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
      </div>

      <div class="form-group @error('description') has-error @enderror">
        <label for="description">Detailed description <span class="req">*</span></label>
        <textarea id="description" name="description" placeholder="Describe where the issue is, when it began, and any helpful context for the porter." required maxlength="2000">{{ old('description') }}</textarea>
        @error('description')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
      </div>

      <div style="display:flex;gap:.85rem;margin-top:1.8rem;flex-wrap:wrap">
        <button type="submit" class="btn btn-solid" id="submitBtn"><i class="fa-solid fa-paper-plane ico"></i><span class="spin"></span><span>Submit complaint</span></button>
        <a href="{{ route('student.dashboard') }}" class="btn btn-line">Cancel</a>
      </div>
    </form>
  </section>

  <aside class="aside stack">
    <div class="card">
      <h3 style="margin-bottom:1rem"><i style="color:#82641a" class="fa-solid fa-lightbulb"></i> Tips for a faster resolution</h3>
      <ul style="display:grid;gap:.6rem;font-size:.9rem;color:var(--ink2)">
        <li style="display:flex;gap:.7rem"><i class="fa-solid fa-check" style="color:var(--sage);margin-top:.3rem"></i> Say exactly what is wrong and where in the hostel.</li>
        <li style="display:flex;gap:.7rem"><i class="fa-solid fa-check" style="color:var(--sage);margin-top:.3rem"></i> Mention when it started and how often it happens.</li>
        <li style="display:flex;gap:.7rem"><i class="fa-solid fa-check" style="color:var(--sage);margin-top:.3rem"></i> Include your room number so the porter can find you quickly.</li>
        <li style="display:flex;gap:.7rem"><i class="fa-solid fa-check" style="color:var(--sage);margin-top:.3rem"></i> Be specific — vague descriptions slow things down.</li>
      </ul>
    </div>

    <div class="card">
      <h3 style="margin-bottom:1rem"><i style="color:var(--deep)" class="fa-solid fa-route"></i> What happens next</h3>
      <div style="position:relative;display:grid;gap:.9rem">
        <div style="display:flex;gap:.9rem;align-items:flex-start;font-size:.88rem;color:var(--ink2)">
          <div style="width:32px;height:32px;border-radius:50%;background:var(--card);border:2px solid var(--sage);color:var(--deep);display:grid;place-items:center;font-size:.72rem;font-weight:700;flex-shrink:0">1</div>
          <div><b style="display:block;color:var(--ink);font-size:.9rem">Complaint logged</b>You get a complaint number straight away.</div>
        </div>
        <div style="display:flex;gap:.9rem;align-items:flex-start;font-size:.88rem;color:var(--ink2)">
          <div style="width:32px;height:32px;border-radius:50%;background:var(--card);border:2px solid var(--sage);color:var(--deep);display:grid;place-items:center;font-size:.72rem;font-weight:700;flex-shrink:0">2</div>
          <div><b style="display:block;color:var(--ink);font-size:.9rem">Porter reviews</b>The porter confirms details and begins investigation.</div>
        </div>
        <div style="display:flex;gap:.9rem;align-items:flex-start;font-size:.88rem;color:var(--ink2)">
          <div style="width:32px;height:32px;border-radius:50%;background:var(--card);border:2px solid var(--sage);color:var(--deep);display:grid;place-items:center;font-size:.72rem;font-weight:700;flex-shrink:0">3</div>
          <div><b style="display:block;color:var(--ink);font-size:.9rem">Resolved &amp; rated</b>Confirm the resolution and leave a rating.</div>
        </div>
      </div>
    </div>
  </aside>
</div>
@endsection

@push('scripts')
<script>
(function(){
  var form=document.getElementById('complaintForm');
  // Textarea counter
  var desc=document.getElementById('description');
  if(desc){
    var c=document.createElement('div');c.className='count';
    desc.parentNode.insertBefore(c,desc.nextSibling);
    function upd(){c.textContent=desc.value.length+' / 2000 characters'}
    desc.addEventListener('input',upd);upd();
  }
  // Submit button loading
  var btn=document.getElementById('submitBtn');
  form.addEventListener('submit',function(){
    if(form.checkValidity&&!form.checkValidity())return;
    btn.classList.add('loading');btn.disabled=true;
  });
  window.addEventListener('pageshow',function(){btn.classList.remove('loading');btn.disabled=false});
})();
</script>
@endpush
