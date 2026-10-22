@extends('layouts.app')
@section('title', isset($category->id) ? 'Edit Category' : 'Add Category')

@section('content')
<a href="{{ route('admin.categories') }}" class="back" style="margin-bottom:1.2rem;display:inline-flex"><i class="fa-solid fa-arrow-left"></i> Back to categories</a>

<section class="card narrow">
  <h3 style="font-size:1.6rem"><i class="fa-solid fa-tags"></i> {{ isset($category->id) ? 'Edit Category: ' . $category->name : 'Add New Category' }}</h3>
  <p class="lead">{{ isset($category->id) ? 'Update category title, description or active status.' : 'Define a category for student complaints.' }}</p>

  <form method="POST" action="{{ isset($category->id) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}">
    @csrf

    <div class="form-group @error('name') has-error @enderror">
      <label for="name">Category name <span class="req">*</span></label>
      <input type="text" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Electrical, Plumbing, Carpentry" required>
      @error('name')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    <div class="form-group @error('description') has-error @enderror">
      <label for="description">Description <small style="color:var(--mute);font-weight:400">(optional)</small></label>
      <textarea id="description" name="description" placeholder="Brief description of problems covered by this category." rows="3">{{ old('description', $category->description ?? '') }}</textarea>
      @error('description')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    <div style="display:grid;gap:.8rem;margin:1.2rem 0">
      <label style="display:flex;align-items:center;font-weight:600;font-size:.9rem;cursor:pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}> Category active for new complaints
      </label>
    </div>

    <div style="display:flex;gap:.7rem;flex-wrap:wrap">
      <button type="submit" class="btn btn-solid"><i class="fa-solid fa-floppy-disk"></i> Save category</button>
      <a href="{{ route('admin.categories') }}" class="btn btn-line">Cancel</a>
    </div>
  </form>
</section>
@endsection
