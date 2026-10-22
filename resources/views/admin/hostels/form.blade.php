@extends('layouts.app')
@section('title', isset($hostel->id) ? 'Edit Hostel' : 'Add Hostel')

@section('content')
<a href="{{ route('admin.hostels') }}" class="back" style="margin-bottom:1.2rem;display:inline-flex"><i class="fa-solid fa-arrow-left"></i> Back to hostels</a>

<section class="card narrow">
  <h3 style="font-size:1.6rem"><i class="fa-solid fa-building"></i> {{ isset($hostel->id) ? 'Edit ' . $hostel->name : 'Add New Hostel' }}</h3>
  <p class="lead">{{ isset($hostel->id) ? 'Update residence hall name or active status.' : 'Register a new hostel hall for complaints.' }}</p>

  <form method="POST" action="{{ isset($hostel->id) ? route('admin.hostels.update', $hostel->id) : route('admin.hostels.store') }}">
    @csrf

    <div class="form-group @error('name') has-error @enderror">
      <label for="name">Hostel name <span class="req">*</span></label>
      <input type="text" id="name" name="name" value="{{ old('name', $hostel->name ?? '') }}" placeholder="e.g. Queen Amina Hall or Nelson Mandela Hostel" required>
      @error('name')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    <div style="margin:1.2rem 0">
      <label style="display:flex;align-items:center;font-weight:600;font-size:.9rem;cursor:pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $hostel->is_active ?? true) ? 'checked' : '' }}> Hostel active for students and porters
      </label>
    </div>

    <div style="display:flex;gap:.7rem;flex-wrap:wrap">
      <button type="submit" class="btn btn-solid"><i class="fa-solid fa-floppy-disk"></i> Save hostel</button>
      <a href="{{ route('admin.hostels') }}" class="btn btn-line">Cancel</a>
    </div>
  </form>
</section>
@endsection
