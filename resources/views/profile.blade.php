@extends('layouts.app')
@section('title', 'My profile')

@section('content')
<div class="page-head"><div><h1>My profile</h1><p>Your details and password.</p></div></div>
<div class="grid">
  <div class="col">
    <section class="card">
      <h3><i class="fa-regular fa-id-card"></i> Your details</h3>
      <p class="sub">Name and phone number can be changed here. Email, role and hostel are managed by an administrator.</p>
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <div class="two">
          <div class="form-group @error('first_name') has-error @enderror">
            <label for="first_name">First name <span class="req">*</span></label>
            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" required>
            @error('first_name')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
          </div>
          <div class="form-group @error('last_name') has-error @enderror">
            <label for="last_name">Last name <span class="req">*</span></label>
            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required>
            @error('last_name')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
          </div>
        </div>
        <div class="form-group @error('phone') has-error @enderror">
          <label for="phone">Phone number</label>
          <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="e.g. 0803 000 0000">
          @error('phone')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-solid"><i class="fa-solid fa-floppy-disk"></i> Save details</button>
      </form>
    </section>

    <section class="card">
      <h3><i class="fa-solid fa-key"></i> Change password</h3>
      <p class="sub">Use at least 8 characters that are not easy to guess.</p>
      <form method="POST" action="{{ route('profile.password') }}">
        @csrf
        <div class="form-group @error('current_password') has-error @enderror">
          <label for="current_password">Current password <span class="req">*</span></label>
          <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
          @error('current_password')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
        </div>
        <div class="form-group @error('password') has-error @enderror">
          <label for="password">New password <span class="req">*</span></label>
          <input type="password" id="password" name="password" required autocomplete="new-password">
          @error('password')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label for="password_confirmation">Confirm new password <span class="req">*</span></label>
          <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-line"><i class="fa-solid fa-lock"></i> Change password</button>
      </form>
    </section>
  </div>

  <aside class="aside stack">
    <div class="card">
      <h3 style="margin-bottom:1.1rem">Account</h3>
      <div class="facts">
        <div class="fact"><span class="ic" style="background:var(--sagebg);color:var(--deep)"><i class="fa-regular fa-envelope"></i></span><div><small>Email</small><b>{{ auth()->user()->email }}</b></div></div>
        <div class="fact"><span class="ic" style="background:var(--skybg);color:#3f7084"><i class="fa-solid fa-user-tag"></i></span><div><small>Role</small><b>{{ auth()->user()->role_label }}</b></div></div>
        @if(auth()->user()->hostel)
          <div class="fact"><span class="ic" style="background:var(--goldbg);color:#82641a"><i class="fa-solid fa-building"></i></span><div><small>Hostel</small><b>{{ auth()->user()->hostel->name }}</b></div></div>
        @endif
        @if(auth()->user()->room)
          <div class="fact"><span class="ic" style="background:var(--claybg);color:#a46a45"><i class="fa-solid fa-door-open"></i></span><div><small>Room</small><b>{{ auth()->user()->room->room_number }}</b></div></div>
        @endif
      </div>
    </div>
  </aside>
</div>
@endsection
