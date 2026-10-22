@extends('layouts.app')
@section('title', 'Rate repair')

@section('content')
<a href="{{ route('student.complaint', $complaint->id) }}" class="back" style="margin-bottom:1.2rem;display:inline-flex"><i class="fa-solid fa-arrow-left"></i> Back to ticket #{{ $complaint->id }}</a>

<section class="card narrow">
  <h3 style="font-size:1.6rem"><i class="fa-solid fa-star" style="color:var(--gold)"></i> How was the fix?</h3>
  <p class="lead">Ticket #{{ $complaint->id }}: {{ $complaint->title }}. Your rating helps keep standards high.</p>

  <form method="POST" action="{{ route('student.rate', $complaint->id) }}">
    @csrf

    <div class="form-group @error('rating') has-error @enderror">
      <label id="ratelabel">Your rating <span class="req">*</span></label>
      <div class="rate-stars" role="radiogroup" aria-labelledby="ratelabel">
        @for($n = 5; $n >= 1; $n--)
          <input type="radio" name="rating" id="star{{ $n }}" value="{{ $n }}" {{ old('rating', $complaint->student_rating) == $n ? 'checked' : '' }} required>
          <label for="star{{ $n }}" title="{{ $n }} star{{ $n > 1 ? 's' : '' }}">&#9733;</label>
        @endfor
      </div>
      @error('rating')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    <div class="form-group @error('feedback') has-error @enderror">
      <label for="feedback">Comment <span style="font-weight:500;color:var(--mute)">(optional)</span></label>
      <textarea id="feedback" name="feedback" maxlength="500" rows="4" placeholder="Tell us what went well or what could be better.">{{ old('feedback', $complaint->student_feedback) }}</textarea>
      @error('feedback')<div class="field-errors"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-solid"><i class="fa-solid fa-star"></i> Save rating</button>
  </form>
</section>
@endsection
