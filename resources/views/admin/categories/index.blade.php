@extends('layouts.app')
@section('title', 'Categories')

@section('content')
<div class="page-head">
  <div><h1>Complaint categories</h1><p>The system defines categories for complaint filing. Add new ones only when necessary.</p></div>
  <a href="{{ route('admin.categories.add') }}" class="btn btn-solid"><i class="fa-solid fa-plus"></i> Add category</a>
</div>

<section class="panel">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>Category</th><th>Description</th><th>Complaints</th><th>Status</th><th class="t-right">Actions</th></tr></thead>
      <tbody>
        @forelse($categories as $c)
        <tr>
          <td data-label="Category"><span class="t-title">{{ $c->name }}</span></td>
          <td data-label="Description" style="max-width:360px">{{ $c->description ?: '-' }}</td>
          <td data-label="Complaints">{{ $c->complaints_count ?? 0 }}</td>
          <td data-label="Status">@if($c->is_active)<span class="badge badge-ok">Active</span>@else<span class="badge badge-off">Inactive</span>@endif</td>
          <td data-label="Actions" class="t-right">
            <div class="act">
              <a href="{{ route('admin.categories.edit', $c->id) }}" class="btn btn-line btn-sm"><i class="fa-solid fa-pen"></i> Edit</a>
              <form method="POST" action="{{ route('admin.categories.toggle', $c->id) }}" class="inline">
                @csrf
                <button class="btn btn-line btn-sm" type="submit">{{ $c->is_active ? 'Deactivate' : 'Activate' }}</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--mute)">No categories found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
