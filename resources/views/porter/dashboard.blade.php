@extends('layouts.app')
@section('title', 'Porter Dashboard')

@section('content')
<section class="banner">
  <div class="b-left">
    <div class="b-icon"><i class="fa-solid fa-user-shield"></i></div>
    <div>
      <div class="date" id="today"></div>
      <h1>{{ auth()->user()->hostel?->name ?: 'No hostel assigned' }}</h1>
      <p>Welcome back, <strong>{{ auth()->user()->first_name ?: auth()->user()->email }}</strong>. @if(($stats['pending'] ?? 0) > 0)<strong>{{ $stats['pending'] }}</strong> complaint(s) waiting for review.@else Nothing is waiting for review.@endif</p>
    </div>
  </div>
</section>

@if(!auth()->user()->hostel)
<div class="nudge" style="display:flex"><i class="fa-solid fa-triangle-exclamation"></i><span>No hostel is linked to your account yet, so there are no complaints to show. Ask the administrator to assign one.</span></div>
@endif

@include('partials._stats')

<section class="panel">
  <div class="p-head"><div><h2>Complaints in your hostel</h2><p>Open a complaint to post an update or change its status.</p></div></div>
  @include('partials._filters')
  @include('partials._complaint_table', ['routePrefix' => 'porter'])
</section>
@endsection
