@extends('layouts.home')
@section('title', 'Nestly — Hostel Complaint Tracker')
@section('meta_description', 'Nestly helps university hostel students submit and track complaints. Porters and administrators resolve issues quickly.')

@section('content')
{{-- HERO --}}
<section id="hero" id="top">
  <div class="wrap">
    <div class="hero-text rv">
      <span class="eyebrow">University Hostel Management</span>
      <h1>A calmer way to live<br>in your hostel.</h1>
      <p>Students submit complaints in seconds. Porters take action and update progress. Everyone knows exactly what's happening.</p>
      <div class="cta-row">
        @guest
          <a href="{{ route('register') }}" class="btn btn-white"><i class="fa-solid fa-user-plus"></i> Create your account</a>
          <a href="{{ route('login') }}" class="btn btn-ghost-w"><i class="fa-solid fa-right-to-bracket"></i> Sign in</a>
        @else
          <a href="{{ route('dashboard') }}" class="btn btn-white"><i class="fa-solid fa-gauge-high"></i> Open my dashboard</a>
        @endguest
      </div>
    </div>
    <div class="hero-stats">
      <div class="hstat rv d1">
        <b data-count="{{ $students_count }}">0</b>
        <span>Students enrolled</span>
      </div>
      <div class="hstat rv d2">
        <b data-count="{{ $hostels_count }}">0</b>
        <span>Active hostels</span>
      </div>
      <div class="hstat rv d1" style="grid-column:span 2">
        <b data-count="{{ $complaints_resolved }}">0</b>
        <span>Complaints resolved</span>
      </div>
    </div>
  </div>
</section>

{{-- HOW IT WORKS --}}
<section id="how">
  <div class="wrap">
    <div class="head rv">
      <span class="eyebrow">The process</span>
      <h2>From report to resolution</h2>
      <p>Three straightforward steps that keep everyone on the same page.</p>
    </div>
    <div class="steps-list" style="grid-template-columns:repeat(3, 1fr)">
      <div class="step rv">
        <div class="d"><i class="fa-solid fa-pen-to-square"></i></div>
        <h3>1. Submit</h3>
        <p>Select your hostel from the dropdown, describe the issue, and provide an optional room number.</p>
      </div>
      <div class="step rv d1">
        <div class="d"><i class="fa-solid fa-user-shield"></i></div>
        <h3>2. In progress</h3>
        <p>The hostel porter reviews the complaint, starts work, and posts real-time progress notes.</p>
      </div>
      <div class="step rv d2">
        <div class="d"><i class="fa-solid fa-check-double"></i></div>
        <h3>3. Resolved</h3>
        <p>Once fixed, the porter marks it resolved and you leave your rating and feedback.</p>
      </div>
    </div>
  </div>
</section>

{{-- FEATURES --}}
<section id="features" style="background:var(--bg2)">
  <div class="wrap">
    <div class="head rv">
      <span class="eyebrow">What you get</span>
      <h2>Designed for students, porters, and administrators</h2>
      <p>Built to eliminate communication breakdowns and make every step clear and accountable.</p>
    </div>
    <div class="bento">
      <div class="cell s4 tint-g rv">
        <div class="ic"><i class="fa-solid fa-chart-line"></i></div>
        <h3>Resolution analytics</h3>
        <p>Track complaint resolution trends across active hostels and complaint categories.</p>
      </div>
      <div class="cell s2 tint-d rv d1">
        <div class="ic"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <h3>Live timeline tracking</h3>
        <p>A time-stamped log of every update from submission to resolution.</p>
      </div>
      <div class="cell s2 rv">
        <div class="ic" style="background:var(--skybg);color:#3f7084"><i class="fa-solid fa-shield-halved"></i></div>
        <h3>Focused role portals</h3>
        <p>Dedicated spaces for Students, Porters, and Administrators.</p>
      </div>
      <div class="cell s2 rv d1">
        <div class="ic" style="background:var(--claybg);color:#a46a45"><i class="fa-solid fa-user-check"></i></div>
        <h3>Porter accountability</h3>
        <p>Porters manage complaints directly for their assigned hostel.</p>
      </div>
      <div class="cell s2 rv d2">
        <div class="ic" style="background:var(--goldbg);color:#82641a"><i class="fa-solid fa-star"></i></div>
        <h3>Student ratings</h3>
        <p>Students rate completed resolutions to ensure high living standards.</p>
      </div>
    </div>
  </div>
</section>

{{-- FAQ --}}
<section id="faq">
  <div class="wrap faq-grid">
    <div class="head rv">
      <span class="eyebrow">Questions</span>
      <h2>Frequently asked questions</h2>
      <p>Have questions? Reach out to your hostel porter or sign in to your dashboard.</p>
    </div>
    <div class="faq rv">
      <details open><summary>Who can use Nestly?</summary><p>Students, hostel porters, and administrators. Each actor has a dedicated portal.</p></details>
      <details><summary>How do I know my complaint was received?</summary><p>As soon as you submit, it appears on your dashboard and on your hostel porter's feed.</p></details>
      <details><summary>Is my room number required?</summary><p>No, room number is optional. You can specify a room number or describe common areas.</p></details>
      <details><summary>What if an issue is not properly fixed?</summary><p>You can rate the resolution and add feedback, or submit a follow-up ticket.</p></details>
      <details><summary>Does it work on mobile?</summary><p>Yes, the interface is fully responsive on phones, tablets, and desktops.</p></details>
    </div>
  </div>
</section>

{{-- CTA --}}
<section>
  <div class="wrap">
    <div class="cta rv">
      <h2>A calmer way to live in your hostel</h2>
      <p>Join the students and porters already resolving hostel issues faster.</p>
      @guest
        <div class="cta-row">
          <a href="{{ route('register') }}" class="btn btn-white"><i class="fa-solid fa-user-plus"></i> Create your account</a>
          <a href="{{ route('login') }}" class="btn btn-ghost-w"><i class="fa-solid fa-right-to-bracket"></i> Sign in</a>
        </div>
      @else
        <div class="cta-row"><a href="{{ route('dashboard') }}" class="btn btn-white"><i class="fa-solid fa-gauge-high"></i> Open my dashboard</a></div>
      @endguest
    </div>
  </div>
</section>
@endsection
