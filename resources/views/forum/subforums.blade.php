@extends('layouts.sidenav')

@section('title', 'Subforums')

@section('content')
<div class="container mt-3">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Your Subforums</h2>
    <a href="{{ route('forum.index') }}" class="btn btn-secondary">Back to Main Forum</a>
  </div>

  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  @if($subforums->count() > 0)
    <div class="row">
      @foreach($subforums as $subforum)
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">{{ $subforum->name }}</h5>
              <p class="card-text text-muted">{{ Str::limit($subforum->description, 100) }}</p>
            </div>
            <div class="card-footer bg-white border-top-0">
              <a href="{{ route('forum.subforum.posts', $subforum) }}" class="btn btn-primary">View Posts</a>
              <a href="{{ route('forum.create', $subforum) }}" class="btn btn-outline-primary">Create Post</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="alert alert-info">
      <p>You don't have access to any subforums. This could be because you haven't specified any courses in your profile.</p>
    </div>
  @endif
</div>
@endsection