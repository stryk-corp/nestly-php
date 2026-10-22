<form method="get" class="filters">
  <div class="f grow"><label for="fq">Search</label><input type="search" id="fq" name="q" value="{{ request('q') }}" placeholder="Subject, student or ticket number"></div>
  @if(isset($hostels) && $hostels->isNotEmpty())
  <div class="f"><label for="fh">Hostel</label>
    <select id="fh" name="hostel"><option value="">All hostels</option>
      @foreach($hostels as $h)<option value="{{ $h->id }}" {{ request('hostel') == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>@endforeach
    </select></div>
  @endif
  @if(isset($categories) && $categories->isNotEmpty())
  <div class="f"><label for="fc">Category</label>
    <select id="fc" name="category"><option value="">All categories</option>
      @foreach($categories as $c)<option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
    </select></div>
  @endif
  <div class="f"><label for="fs">Status</label>
    <select id="fs" name="status"><option value="">Any status</option>
      <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
      <option value="IN_PROGRESS" {{ request('status') === 'IN_PROGRESS' ? 'selected' : '' }}>In progress</option>
      <option value="RESOLVED" {{ request('status') === 'RESOLVED' ? 'selected' : '' }}>Resolved</option>
    </select></div>
  <div class="toolbar"><button type="submit" class="btn btn-solid btn-sm"><i class="fa-solid fa-filter"></i> Apply</button><a href="{{ url()->current() }}" class="btn btn-line btn-sm">Clear</a></div>
</form>
