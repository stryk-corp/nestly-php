@extends('layouts.app')
@section('title', 'Hostels')

@section('content')
<div class="page-head">
  <div><h1>Hostels</h1><p>{{ $hostels->count() }} hostel{{ $hostels->count() !== 1 ? 's' : '' }} on record. Hostels are made inactive rather than deleted, so complaint history stays intact.</p></div>
  <a href="{{ route('admin.hostels.add') }}" class="btn btn-solid"><i class="fa-solid fa-plus"></i> Add hostel</a>
</div>

<section class="panel">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>Hostel</th><th>Students</th><th>Porters</th><th>Open complaints</th><th>Status</th><th class="t-right">Actions</th></tr></thead>
      <tbody>
        @forelse($hostels as $h)
        <tr>
          <td data-label="Hostel"><span class="t-title">{{ $h->name }}</span></td>
          <td data-label="Students">{{ $h->students_count ?? 0 }}</td>
          <td data-label="Porters">{{ $h->porters_count ?? 0 }}</td>
          <td data-label="Open">{{ $h->open_complaints_count ?? 0 }} <span style="color:var(--mute);font-size:.8rem">of {{ $h->complaints_count ?? 0 }}</span></td>
          <td data-label="Status">@if($h->is_active)<span class="badge badge-ok">Active</span>@else<span class="badge badge-off">Inactive</span>@endif</td>
          <td data-label="Actions" class="t-right">
            <div class="act">
              <a href="{{ route('admin.hostels.edit', $h->id) }}" class="btn btn-line btn-sm"><i class="fa-solid fa-pen"></i> Edit</a>
              <form method="POST" action="{{ route('admin.hostels.toggle', $h->id) }}" class="inline">
                @csrf
                <button class="btn btn-line btn-sm" type="submit">{{ $h->is_active ? 'Deactivate' : 'Activate' }}</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--mute)">No hostels registered yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
