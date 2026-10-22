@extends('layouts.app')
@section('title', isset($editUser->id) ? 'Edit User' : 'Add User')

@section('content')
<a href="{{ route('admin.users') }}" class="back" style="margin-bottom:1.2rem;display:inline-flex"><i class="fa-solid fa-arrow-left"></i> Back to users</a>

<section class="card narrow">
  <h3 style="font-size:1.6rem"><i class="fa-solid fa-user-pen"></i> {{ isset($editUser->id) ? 'Edit User #' . $editUser->id : 'Add New User' }}</h3>
  <p class="lead">{{ isset($editUser->id) ? 'Update profile, role, approval or hostel assignment.' : 'Create a student, porter or administrator account.' }}</p>

  <form method="POST" action="{{ isset($editUser->id) ? route('admin.users.update', $editUser->id) : route('admin.users.store') }}">
    @csrf

    <div class="two">
      <div class="form-group @error('first_name') has-error @enderror">
        <label for="first_name">First name <span class="req">*</span></label>
        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $editUser->first_name ?? '') }}" required>
        @error('first_name')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
      </div>
      <div class="form-group @error('last_name') has-error @enderror">
        <label for="last_name">Last name <span class="req">*</span></label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $editUser->last_name ?? '') }}" required>
        @error('last_name')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
      </div>
    </div>

    <div class="form-group @error('email') has-error @enderror">
      <label for="email">Email address <span class="req">*</span></label>
      <input type="email" id="email" name="email" value="{{ old('email', $editUser->email ?? '') }}" required>
      @error('email')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    <div class="form-group @error('phone') has-error @enderror">
      <label for="phone">Phone number</label>
      <input type="tel" id="phone" name="phone" value="{{ old('phone', $editUser->phone ?? '') }}" placeholder="e.g. 0803 000 0000">
      @error('phone')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    <div class="two">
      <div class="form-group @error('role') has-error @enderror">
        <label for="role">Role <span class="req">*</span></label>
        <select id="role" name="role" required>
          @foreach(\App\Models\User::ROLE_LABELS as $val => $lbl)
            <option value="{{ $val }}" {{ old('role', $editUser->role ?? 'STUDENT') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
          @endforeach
        </select>
        @error('role')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
      </div>

      <div class="form-group @error('hostel_id') has-error @enderror" id="hostelGrp">
        <label for="hostel_id">Hostel</label>
        <select id="hostel_id" name="hostel_id">
          <option value="">None / Not assigned</option>
          @foreach($hostels as $h)
            <option value="{{ $h->id }}" {{ old('hostel_id', $editUser->hostel_id ?? '') == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
          @endforeach
        </select>
        @error('hostel_id')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
      </div>
    </div>

    <div class="form-group @error('password') has-error @enderror">
      <label for="password">{{ isset($editUser->id) ? 'Password (leave blank to keep current)' : 'Password' }} @if(!isset($editUser->id))<span class="req">*</span>@endif</label>
      <input type="password" id="password" name="password" {{ !isset($editUser->id) ? 'required' : '' }} autocomplete="new-password">
      @error('password')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    <div style="display:flex;gap:1.5rem;flex-wrap:wrap;margin:1.2rem 0">
      <label style="display:flex;align-items:center;font-weight:600;font-size:.9rem;cursor:pointer">
        <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $editUser->is_approved ?? true) ? 'checked' : '' }}> Approved to sign in
      </label>
      <label style="display:flex;align-items:center;font-weight:600;font-size:.9rem;cursor:pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $editUser->is_active ?? true) ? 'checked' : '' }}> Account active
      </label>
    </div>

    <div style="display:flex;gap:.7rem;flex-wrap:wrap">
      <button type="submit" class="btn btn-solid"><i class="fa-solid fa-floppy-disk"></i> Save user</button>
      <a href="{{ route('admin.users') }}" class="btn btn-line">Cancel</a>
    </div>
  </form>
</section>
@endsection
