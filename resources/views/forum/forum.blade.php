@extends('layouts.main')

@section('title', 'Home')

@section('content')
<div class="container mt-3">

  <!-- Create Post Button -->
  <div class="mb-4 text-right">
    <a href="{{ route('forum.create') }}" class="btn btn-primary">
      Create Post
    </a>
  </div>

  <!-- Forum Listing -->
  <div id="posts-container">
  @if($posts->count() > 0)
    @foreach($posts as $post)

      {{-- 1) Skip posts fully deleted --}}
      @if($post->body === '[Deleted Content]' && $post->totalCommentCount() === 0)
        @continue
      @endif

      <div class="post-card mb-4" data-href="{{ route('forum.show', ['post' => $post->slug]) }}" >
        {{-- HEADER ROW: avatar, user/name/times, ellipsis dropdown --}}
        <div class="post-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              {{-- Avatar --}}
              <img src="{{ $post->user->profile_photo
                            ? asset('storage/'.$post->user->profile_photo)
                            : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&rounded=true' }}"
                  class="post-avatar mr-2">

              {{-- Name + timestamp --}}
              <div class="post-meta">
                <strong>
                    <a href="{{ route('users.show', $post->user->name) }}" class="hover:underline d-inline-flex align-items-center">
                        {{ $post->user->name }}
                        @inlineBadges($post->user,32)
                    </a>
                </strong>
                <div class="text-muted small">
                    <span class="date-tooltip"
                          data-iso="{{ $post->created_at->toIso8601String() }}">
                      {{ $post->created_at->diffForHumans() }}
                    </span>
                    @if($post->updated_at->gt($post->created_at))
                      • <span class="date-tooltip"
                              data-iso="{{ $post->created_at->toIso8601String() }}">
                          edited {{ $post->updated_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
            </div>
          </div>

          {{-- Ellipsis dropdown --}}
          @php $me = Auth::user(); @endphp
          <div class="dropdown">
            <button class="btn btn-sm btn-light p-1 rounded-circle" data-toggle="dropdown">
              <i class="fas fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">

              {{-- Edit --}}
              @if($me->id === $post->user_id && $post->body!=='[Deleted Content]')
                <form class="dropdown-item p-0 m-0">
                  @csrf
                  <button
                    type="button"
                    class="btn btn-link w-100 text-left p-2 text-primary"
                    onclick="location.href='{{ route('forum.edit', $post) }}'"
                  >Edit</button>
                </form>
              @endif

              {{-- Delete --}}
              @if($me->id === $post->user_id || $me->is_mod)
                <form
                  method="POST"
                  action="{{ route('posts.delete', $post) }}"
                  class="dropdown-item p-0 m-0 delete-form"
                  data-type="post"
                >
                  @csrf @method('DELETE')
                  <button
                    type="button"
                    class="btn btn-link w-100 text-left p-2 text-danger delete-btn"
                  >Delete</button>
                </form>
              @endif

              {{-- Hide / Report --}}
              @if($me->id !== $post->user_id && $post->body!=='[Deleted Content]')
                <form class="dropdown-item p-0 m-0" method="POST" action="{{ route('forum.hide', $post) }}">
                  @csrf
                  <button class="btn btn-link w-100 text-left p-2 text-secondary">Hide</button>
                </form>
                <form class="dropdown-item p-0 m-0" method="POST" action="{{ route('forum.report', $post) }}">
                  @csrf
                  <button class="btn btn-link w-100 text-left p-2 text-warning">Report</button>
                </form>
              @endif

            </div>
          </div>

        </div>

        {{-- TITLE ROW --}}
        <h4 class="post-title mt-2 mb-1">{{ $post->title }}</h4>

        {{-- BODY ROW (clamped to ~6 lines) --}}
        @if($post->body!=='[Deleted Content]')
          <p class="post-body clamp-6-lines">
            {!! $post->body !!}
          </p>
        @else
          <p class="text-muted">[Deleted Content]</p>
        @endif

        {{-- MEDIA ROW --}}
          @if($post->video)
            <div class="post-media-container mt-2">
              <video controls class="post-media">
                <source src="{{ asset('storage/'.$post->video) }}" type="video/mp4">
              </video>
            </div>
          @elseif(count($post->images ?? []) > 0)
            <div class="post-media-container mt-2 position-relative" data-image-count="{{ count($post->images) }}">
              @foreach($post->images as $index => $img)
                <div class="post-media-slide {{ $index === 0 ? 'active' : '' }}">
                  <div class="post-media-bg" style="background-image: url('{{ asset('storage/'.$img) }}');"></div>
                  <img src="{{ asset('storage/'.$img) }}" loading="lazy" class="post-media">
                </div>
              @endforeach

              {{-- Slide Indicator Top Right --}}
              @if(count($post->images) > 1)
                <div class="slide-counter badge badge-dark position-absolute" style="top: 5px; right: 10px;">
                  <span class="current-slide">1</span> / {{ count($post->images) }}
                </div>

                {{-- Navigation Arrows --}}
                <button class="slide-arrow left">&lsaquo;</button>
                <button class="slide-arrow right">&rsaquo;</button>

                {{-- Dots --}}
                <div class="slide-dots">
                  @foreach($post->images as $i => $_)
                    <span class="dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></span>
                  @endforeach
                </div>
              @endif
            </div>
          @endif


        {{-- ACTIONS ROW --}}
        <div class="post-actions d-flex align-items-center mt-3">
                  {{-- Like / Dislike --}}
                  <div class="vote-controls d-flex align-items-center mr-3">
                    <form class="vote-form d-inline"
                          data-vote-url="{{ route('posts.vote',$post) }}"
                          method="POST">
                      @csrf
                      <input type="hidden" name="vote" value="1">
                      <button type="button"
                              class="btn btn-sm {{ optional($post->votes()->where('user_id',Auth::id())->first())->vote == 1 ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-arrow-up"></i>
                      </button>
                    </form>

                    <div class="vote-count mx-2 {{ ($post->likesCount() - $post->dislikesCount()) < 0 ? 'text-danger' : '' }}">
                      {{ $post->likesCount() - $post->dislikesCount() }}
                    </div>

                    <form class="vote-form d-inline"
                          data-vote-url="{{ route('posts.vote',$post) }}"
                          method="POST">
                      @csrf
                      <input type="hidden" name="vote" value="-1">
                      <button type="button"
                              class="btn btn-sm {{ optional($post->votes()->where('user_id',Auth::id())->first())->vote == -1 ? 'btn-danger' : 'btn-outline-danger' }}">
                        <i class="fas fa-arrow-down"></i>
                      </button>
                    </form>
                  </div>


          {{-- Comments --}}
          <a href="{{ route('forum.show', $post) }}"
            class="btn btn-sm btn-outline-info rounded-pill px-3 mr-2">
            <i class="fas fa-comment"></i> {{ $post->totalCommentCount() }}
          </a>

          {{-- Share --}}
          <button
            class="btn btn-sm btn-outline-secondary rounded-pill px-3 share-post-btn"
            data-post-slug="{{ $post->slug }}">
            <i class="fas fa-share"></i>
          </button>
        </div>
      </div>

    @endforeach
  @else
    <p>No posts available.</p>
  @endif
  </div>

  <!-- Loading Spinner -->
  <div id="loading-spinner" class="text-center my-4" style="display: none;">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    <p class="mt-2">Loading more posts...</p>
  </div>

  <!-- End message when no more posts -->
  <div id="end-of-posts-message" class="text-center my-4" style="display: none;">
    <p class="text-muted">You've reached the end of posts</p>
  </div>

  <!-- Load More Sentinel Element -->
  <div
    id="post-load-more"
    data-next-page-url="{{ $posts->nextPageUrl() }}"
    style="height:1px">
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const sentinel = document.querySelector('#post-load-more');
    const spinner = document.querySelector('#loading-spinner');
    const endMessage = document.querySelector('#end-of-posts-message');
    let loading = false;
    let allPostsLoaded = false;

    const loadMore = () => {
      const url = sentinel.dataset.nextPageUrl;
      
      // If no more URLs or already loading, don't proceed
      if (!url || loading) return;
      
      // If all posts are loaded, show end message
      if (allPostsLoaded) {
        endMessage.style.display = 'block';
        return;
      }
      
      // Show loading spinner
      spinner.style.display = 'block';
      loading = true;
      
      fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
          // Hide spinner
          spinner.style.display = 'none';
          
          // Parse returned HTML
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          
          // Grab new post-cards
          const newPosts = doc.querySelectorAll('.post-card');
          
          // If no new posts, mark as all loaded
          if (newPosts.length === 0) {
            allPostsLoaded = true;
            endMessage.style.display = 'block';
            return;
          }
          
          // Append new posts to container
          newPosts.forEach(card => {
            document.querySelector('#posts-container').appendChild(card);
          });
          
          // Update next-page URL
          const nextLoadMore = doc.querySelector('#post-load-more');
          const nextUrl = nextLoadMore ? nextLoadMore.dataset.nextPageUrl : '';
          sentinel.dataset.nextPageUrl = nextUrl;
          
          // Check if this was the last page
          if (!nextUrl) {
            allPostsLoaded = true;
            endMessage.style.display = 'block';
          }
        })
        .catch(error => {
          console.error('Error loading more posts:', error);
          spinner.style.display = 'none';
        })
        .finally(() => {
          loading = false;
        });
    };

    // Initialize Intersection Observer
    const observer = new IntersectionObserver(entries => {
      if (entries[0].isIntersecting && !allPostsLoaded) {
        loadMore();
      }
    }, { rootMargin: '200px' });
    
    // Start observing the sentinel element
    observer.observe(sentinel);
    
    // Initialize any post cards that are already loaded
    document.querySelectorAll('.post-card').forEach(card => {
      card.addEventListener('click', function(e) {
        // Only navigate if the click wasn't on a button or other interactive element
        if (!e.target.closest('button') && !e.target.closest('a') && !e.target.closest('form')) {
          window.location.href = this.dataset.href;
        }
      });
    });
    
    // Handle dynamically added cards (delegation)
    document.querySelector('#posts-container').addEventListener('click', function(e) {
      const card = e.target.closest('.post-card');
      if (card && !e.target.closest('button') && !e.target.closest('a') && !e.target.closest('form')) {
        window.location.href = card.dataset.href;
      }
    });
  });
</script>
@endsection