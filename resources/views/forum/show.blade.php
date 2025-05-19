@php use Illuminate\Support\Facades\Storage; @endphp

@extends('layouts.main')
@section('title','View Post')

@section('content')
<div class="container mt-3 view-post-page">
  @if($post)
    @php $me = Auth::user(); @endphp

    <div class="post-card mb-4">

      {{-- HEADER ROW --}}
      <div class="post-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <a href="{{ url()->previous() }}" class="btn btn-circle mr-2">
            <i class="fas fa-arrow-left"></i>
          </a>
          <img src="{{ $post->user->profile_photo
                        ? asset('storage/'.$post->user->profile_photo)
                        : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&rounded=true' }}"
               class="post-avatar mr-2">

               <div class="post-meta">
    @if($post->body === '[Deleted Content]')
        <strong>[Deleted User]</strong>
    @else
        <strong>
            <a href="{{ route('users.show', $post->user->name) }}" class="hover:underline d-inline-flex align-items-center">
                {{ $post->user->name }}
                @inlineBadges($post->user, 32)
            </a>
        </strong>
    @endif
    <div class="text-muted small">
        <span class="date-tooltip" data-iso="{{ $post->created_at->toIso8601String() }}">
            {{ $post->created_at->diffForHumans() }}
        </span>
        @if($post->updated_at->gt($post->created_at))
            • <span class="date-tooltip" data-iso="{{ $post->updated_at->toIso8601String() }}">
                Edited {{ $post->updated_at->diffForHumans() }}
            </span>
        @endif
    </div>
</div>
        </div>

        {{-- ELLIPSIS MENU --}}
        <div class="dropdown">
          <button class="btn btn-sm btn-light p-1 rounded-circle" data-toggle="dropdown">
            <i class="fas fa-ellipsis-h"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right">

            {{-- Edit --}}
            @if($post->body!=='[Deleted Content]' && $me->id === $post->user_id)
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
                class="dropdown-item p-0 m-0 delete-form"
                method="POST"
                action="{{ route('posts.delete', $post) }}"
                data-type="post"
              >
                @csrf @method('DELETE')
                <button
                  type="button"
                  class="btn btn-link w-100 text-left p-2 text-danger delete-btn"
                >Delete</button>
              </form>
            @endif

            {{-- Hide / Unhide / Report --}}
            @if($me->id !== $post->user_id && $post->body !== '[Deleted Content]')
              @if($hasHidden)
                <form class="dropdown-item p-0 m-0" method="POST" action="{{ route('forum.unhide', $post) }}">
                  @csrf
                  <button class="btn btn-link w-100 text-left p-2 text-secondary">Unhide Post</button>
                </form>
              @else
                <form class="dropdown-item p-0 m-0" method="POST" action="{{ route('forum.hide', $post) }}">
                  @csrf
                  <button class="btn btn-link w-100 text-left p-2 text-secondary">Hide Post</button>
                </form>
              @endif

              <form class="dropdown-item p-0 m-0" method="POST" action="{{ route('forum.report', $post) }}">
                @csrf
                <button class="btn btn-link w-100 text-left p-2 text-warning">Report Post</button>
              </form>
            @endif

          </div>
        </div>

      </div>

      {{-- TITLE ROW --}}
      <h4 class="post-title mt-2 mb-1">
        {{ $post->body === '[Deleted Content]' ? '[Deleted Content]' : $post->title }}
      </h4>

      {{-- BODY --}}
      <div class="post-body mb-3" style="font-size:1.1rem;">
        {!! $post->body !!}
      </div>

      {{-- MEDIA (identical carousel/video logic) --}}
      @if($post->video)
        <div class="post-media-container mt-2">
          <video controls class="post-media">
            <source src="{{ asset('storage/'.$post->video) }}" type="video/mp4">
          </video>
        </div>
      @elseif(count($post->images ?? []) > 0)
        <div class="post-media-container mt-2 position-relative" data-image-count="{{ count($post->images) }}">
          @foreach($post->images as $i => $img)
            <div class="post-media-slide {{ $i === 0 ? 'active' : '' }}">
              <div class="post-media-bg"
                   style="background-image:url('{{ asset('storage/'.$img) }}')"></div>
              <img src="{{ asset('storage/'.$img) }}"
                   loading="lazy"
                   class="post-media">
            </div>
          @endforeach

          @if(count($post->images) > 1)
            <div class="slide-counter badge badge-dark position-absolute"
                 style="top:5px; right:10px;">
              <span class="current-slide">1</span> / {{ count($post->images) }}
            </div>
            <button class="slide-arrow left">&lsaquo;</button>
            <button class="slide-arrow right">&rsaquo;</button>
            <div class="slide-dots">
              @foreach($post->images as $i => $_)
                <span class="dot {{ $i===0?'active':'' }}" data-index="{{ $i }}"></span>
              @endforeach
            </div>
          @endif
        </div>
      @endif

      {{-- ACTIONS ROW --}}

<div class="post-actions d-flex align-items-center mt-3">
@if($post->body === '[Deleted Content]' || $post->body === '[Removed by moderator]')
            {{-- Show disabled buttons with no vote count for deleted content --}}
            <div class="vote-controls d-flex align-items-center mr-3">
              <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                <i class="fas fa-arrow-up"></i>
              </button>
              <div class="mx-2">-</div>
              <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                <i class="fas fa-arrow-down"></i>
              </button>
            </div>
          @else
            {{-- Normal voting for non-deleted posts --}}
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
          @endif


        <a href="{{ route('forum.show',$post) }}"
           class="btn btn-sm btn-outline-info rounded-pill px-3 mr-2">
          <i class="fas fa-comment"></i> {{ $post->totalCommentCount() }}
        </a>

        <button
          id="sharePostBtn"
          class="btn btn-sm btn-outline-secondary rounded-pill px-3 share-post-btn"
          data-post-slug="{{ $post->slug }}">
          <i class="fas fa-share"></i>
        </button>
      </div>
    </div><!-- /.post-card -->


    <div class="card mb-2">
      <div class="card-body" style="padding: 15px;">
        <h4 style="font-size: 1rem;">Add a Comment</h4>
        <!-- Placeholder div shown initially -->
        <div id="comment-placeholder" style="border: 1px solid #ccc; padding: 10px; border-radius: 4px; cursor: text; color: #999;">
          Join the Conversation
        </div>
        <!-- Full comment form, hidden by default -->
        <form id="commentForm" action="{{ route('posts.comments.store', ['post' => $post->slug]) }}" method="POST" style="display: none;">
          @csrf
          <div class="form-group mb-1">
            <div id="editor-commentForm" class="quill-editor" style="height: 120px;"></div>
            <input type="hidden" name="body" id="input-commentForm">
          </div>
          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-light btn-sm" id="cancelComment">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary btn-sm submit-comment-btn">Post Comment</button>
          </div>
        </form>
      </div>
    </div>


    @php
      $sort = request('sort_by', 'top');
      $directComments = $post->comments;
      if($sort == 'most_liked') {
        $directComments = $directComments->sortByDesc(function($comment) {
          return $comment->likesCount();
        });
      } elseif($sort == 'most_disliked') {
        $directComments = $directComments->sortByDesc(function($comment) {
          return $comment->dislikesCount();
        });
      } elseif($sort == 'top') {
        $directComments = $directComments->sortByDesc(function($comment) {
          return $comment->likesCount() - $comment->dislikesCount();
        });
      } elseif($sort == 'newest') {
        $directComments = $directComments->sortByDesc('created_at');
      } elseif($sort == 'oldest') {
        $directComments = $directComments->sortBy('created_at');
      }
      $totalDirect = $directComments->count();
      $visibleDirect = $directComments->take(15);
      $remainingDirect = $directComments->slice(15);
    @endphp

    <div class="mb-3">
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Sort by: {{ ucfirst($sort) }}
        </button>
        <div class="dropdown-menu" aria-labelledby="sortDropdown">
          <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'most_liked']) }}">Most Liked</a>
          <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'most_disliked']) }}">Most Disliked</a>
          <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'top']) }}">Top</a>
          <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'newest']) }}">Newest</a>
          <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'oldest']) }}">Oldest</a>
        </div>
      </div>
    </div>



        <h4 style="font-size: 1rem;">Comments</h4>
        <div id="comments-container">
          @foreach($visibleDirect as $comment)
          @include('forum._comment', ['comment' => $comment,'postSlug' => $post->slug])

          @endforeach
          </div>
          @if($totalDirect > 15)
            <a class="d-block text-primary toggle-hidden-comments" data-target="#moreDirectComments" style="font-size: 0.9rem; cursor: pointer; text-align: center;">Show more comments</a>
            <div class="collapse" id="moreDirectComments">
              @foreach($remainingDirect as $comment)
              @include('forum._comment', ['comment' => $comment,'postSlug' => $post->slug])

              @endforeach
            </div>
          @endif
  @else
    <p>No post found.</p>
  @endif
</div>
@endsection
@section('scripts')
<!-- jQuery, Bootstrap JS, TinyMCE -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1) Configure your toolbar
  const toolbarOptions = [
    ['bold','italic','underline'],
    ['link'],
    [{ list: 'ordered' }, { list: 'bullet' }]
  ];

  // 2) Find every editor container on the page…
  document.querySelectorAll('.quill-editor').forEach(el => {
    // each should have data‑input attribute or follow a naming convention
    // here we derive inputId by swapping "editor-" → "input-"
    const inputId = el.id.replace('editor-', 'input-');

    // init Quill
    const quill = new Quill('#'+el.id, {
      theme: 'snow',
      modules: { toolbar: toolbarOptions }
    });

    // when its form is submitted, copy HTML into the hidden input
    el.closest('form').addEventListener('submit', () => {
      document.getElementById(inputId).value = quill.root.innerHTML;
    });
    document.getElementById('comment-placeholder').addEventListener('click', function () {
      this.style.display = 'none';
      document.getElementById('commentForm').style.display = 'block';
    });


    // OPTIONAL: custom image‑handler hooking your new route
    quill.getModule('toolbar').addHandler('image', () => {
      const input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      input.click();
      input.onchange = () => {
        const file = input.files[0];
        const form = new FormData();
        form.append('image', file);

        fetch("{{ route('quill.upload') }}", {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: form
        })
        .then(res => res.json())
        .then(json => {
          const range = quill.getSelection(true);
          quill.insertEmbed(range.index, 'image', json.url);
        })
        .catch(console.error);
      };
    });
  });
});

document.addEventListener('DOMContentLoaded', function() {
  // Your existing Quill initialization code...

  // Handle the comment placeholder
  document.getElementById('comment-placeholder').addEventListener('click', function () {
    this.style.display = 'none';
    document.getElementById('commentForm').style.display = 'block';
  });

  // Handle the cancel button for main comment
  document.getElementById('cancelComment').addEventListener('click', function() {
    document.getElementById('commentForm').style.display = 'none';
    document.getElementById('comment-placeholder').style.display = 'block';
  });

  // Prevent multiple submissions for main comment form
  document.querySelector('#commentForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.submit-comment-btn');
    if (submitBtn.disabled) {
      e.preventDefault();
      return false;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
    
    // Enable the button after 5 seconds in case of network issues
    setTimeout(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = 'Post Comment';
    }, 5000);
  });

  // Prevent multiple submissions for reply forms
  document.querySelectorAll('.reply-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      const submitBtn = this.querySelector('.submit-reply-btn');
      if (submitBtn.disabled) {
        e.preventDefault();
        return false;
      }
      
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
      
      // Enable the button after 5 seconds in case of network issues
      setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Submit Reply';
      }, 5000);
    });
  });

  // For AJAX submissions, you need to modify your existing AJAX code
  // Here's an example of how you might prevent multiple submissions in AJAX:
  $(document).on('submit', '.reply-form', function(e) {
    e.preventDefault();
    const form = $(this);
    const submitBtn = form.find('.submit-reply-btn');
    
    if (submitBtn.prop('disabled')) {
      return false;
    }
    
    submitBtn.prop('disabled', true);
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');
    
    // Your existing AJAX submission code here...
    $.ajax({
      url: form.attr('action'),
      method: 'POST',
      data: form.serialize(),
      success: function(response) {
        // Your existing success handler
        // ...
        
        // Reset the button
        submitBtn.prop('disabled', false);
        submitBtn.html('Submit Reply');
      },
      error: function(error) {
        // Your existing error handler
        // ...
        
        // Reset the button
        submitBtn.prop('disabled', false);
        submitBtn.html('Submit Reply');
      }
    });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const sentinel = document.querySelector('#comment-load-more');
  let loading = false;

  const loadMoreComments = () => {
    const url = sentinel.dataset.nextPageUrl;
    if (!url || loading) return;
    loading = true;
    fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' } })
      .then(r => r.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        // append new comments
        doc.querySelectorAll('.thread-comment').forEach(c =>
          document.querySelector('#comments-container').appendChild(c)
        );
        // bump to next comments_page
        sentinel.dataset.nextPageUrl = doc
          .querySelector('#comment-load-more')
          .dataset.nextPageUrl || '';
      })
      .finally(() => loading = false);
  };

  new IntersectionObserver(entries => {
    if (entries[0].isIntersecting) loadMoreComments();
  }, { rootMargin: '200px' }).observe(sentinel);
});

$(document).ready(function() {
    // Check if we have a highlighted comment ID
    const highlightId = {{ $highlightId ?? 'null' }};
    
    if (highlightId) {
        // Find the element
        const commentElement = document.getElementById(`comment-${highlightId}`);
        
        if (commentElement) {
            // Scroll to the element
            setTimeout(() => {
                commentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Add the highlight animation class
                commentElement.classList.add('comment-highlight');
                
                // The animation will handle the fade-out naturally
                // No need to manually remove the class as the animation ends with transparency
            }, 500); // Small delay to ensure everything is loaded
        }
    }
});
</script>

@endsection