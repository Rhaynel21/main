@props(['comment','level'=>0,'postSlug'])
@php
    // Use an anonymous function for recursive checking.
    $shouldRender = function($c) use (&$shouldRender) {
    // always show user-deleted ([Deleted Content]) if they have replies
    if ($c->body === '[Deleted Content]') {
        // but only skip if zero replies
        return $c->replies->count() > 0;
    }
    // skip pure moderator-removed only if no replies
    if ($c->body === '[Removed by moderator]') {
        return $c->replies->count() > 0;
    }
    // otherwise show all other comments
    return true;
};


    // If this comment and its entire chain are deleted, skip rendering.
    if (!$shouldRender($comment)) {
        return;
    }
    
    // Determine maximum visible replies based on nesting level.
    if ($level == 0) {
        $maxVisible = 4;
    } elseif ($level == 1) {
        $maxVisible = 2;
    } else {
        $maxVisible = 0;
    }
    
    $isDeleted = in_array($comment->body, [
      '[Deleted Content]',
      '[Removed by moderator]',
    ]);
    $initialDisplay = $isDeleted ? 'none' : 'block';
    $disabled = $isDeleted ? 'disabled' : '';
    $displayName = ($comment->user) ? $comment->user->name : 'Deleted';
@endphp


<div class="media thread-comment" data-comment-id="{{ $comment->id }}" id="comment-{{ $comment->id }}" data-comment-slug="{{ $comment->slug }}">
@php
  // Determine display name or fallback
  $displayName = $comment->user
               ? $comment->user->name
               : 'Deleted User';

    if ($comment->body === '[Deleted Content]' || $comment->body === '[Removed by moderator]') {
      // deleted comment: always black DU avatar
      $avatarUrl = 'https://ui-avatars.com/api/'
                . '?name=Deleted+User'
                . '&background=000000'
                . '&color=ffffff'
                . '&rounded=true'
                . '&size=64';
    }
    elseif ($comment->user && $comment->user->profile_photo) {
      $avatarUrl = asset('storage/'.$comment->user->profile_photo);
    } else {
      $avatarUrl = 'https://ui-avatars.com/api/'
                . '?name=' . urlencode($displayName)
                . '&background=0D8ABC'
                . '&color=fff'
                . '&rounded=true'
                . '&size=64';
    }
@endphp

<img src="{{ $avatarUrl }}"
     class="mr-2 rounded-circle profile-card-trigger"
     alt="{{ $displayName }}"
     data-username="{{ $isDeleted ? '' : $comment->user->name }}"
     style="width:30px; height:30px;">

        <div class="media-body">
          <!-- Comment Header -->
          <div class="comment-header" style="cursor: pointer;">
            <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0" style="font-size: 0.85rem;">
            @if($isDeleted)
    [Deleted User]
@else
    <a href="{{ route('users.show', $comment->user->name) }}" 
       class="hover:underline d-inline-flex align-items-center profile-card-trigger"
       data-username="{{ $comment->user->name }}">
        {{ $displayName }}
        @inlineBadges($comment->user, 32)
    </a>
@endif
          <small class="text-muted">
              • <span class="date-tooltip" data-iso="{{ $comment->created_at->toIso8601String() }}">
                  {{ $comment->created_at->diffForHumans() }}
              </span>
              @if($comment->updated_at != $comment->created_at)
                  • <span class="date-tooltip" data-iso="{{ $comment->updated_at->toIso8601String() }}">
                      Edited {{ $comment->updated_at->diffForHumans() }}
                  </span>
              @endif
          </small>
        </h6>
      </div>
    </div>
    <!-- Comment Details -->
    <div class="comment-details" style="display: {{ $initialDisplay }};">
  <div class="comment-body">
    @if($isDeleted)
      {{-- You could show different text for moderator vs user here if you like --}}
      {{ $comment->body === '[Deleted Content]' 
          ? '[deleted by user]' 
          : '[removed by moderator]' }}
    @else
      {!! $comment->body !!}
    @endif
  </div>

  <div class="comment-actions d-flex align-items-center mt-1" style="font-size:0.75rem;">
  @php $me = Auth::user(); @endphp

  @if($isDeleted)
  {{-- Show disabled buttons with no vote count for deleted content --}}
  <div class="vote-controls d-flex align-items-center mr-2">
    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
      <i class="fas fa-arrow-up"></i>
    </button>
    <div class="mx-2">-</div>
    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
      <i class="fas fa-arrow-down"></i>
    </button>
  </div>
@else
  {{-- Normal voting for non-deleted comments --}}
  <div class="vote-controls d-flex align-items-center mr-2" data-vote-type="comment">
    <form method="POST"
          action="{{ route('comments.vote', ['post'=>$comment->post->slug,'comment'=>$comment->slug]) }}"
          class="vote-form d-inline"
          data-vote-type="comment">
      @csrf
      <input type="hidden" name="vote" value="1">
      <button type="button"
              class="btn btn-sm {{ optional($comment->votes()->where('user_id',Auth::id())->first())->vote == 1
                                ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-arrow-up"></i>
      </button>
    </form>

    <div class="vote-count mx-2 {{ ($comment->likesCount() - $comment->dislikesCount()) < 0 ? 'text-danger' : '' }}">
      {{ $comment->likesCount() - $comment->dislikesCount() }}
    </div>

    <form method="POST"
          action="{{ route('comments.vote', ['post'=>$comment->post->slug,'comment'=>$comment->slug]) }}"
          class="vote-form d-inline"
          data-vote-type="comment">
      @csrf
      <input type="hidden" name="vote" value="-1">
      <button type="button"
              class="btn btn-sm {{ optional($comment->votes()->where('user_id',Auth::id())->first())->vote == -1
                                ? 'btn-danger' : 'btn-outline-danger' }}">
        <i class="fas fa-arrow-down"></i>
      </button>
    </form>
  </div>
@endif

  @unless($isDeleted)
    {{-- Reply --}}
    <button class="btn btn-sm rounded-pill px-3 btn-outline-secondary mr-2 reply-btn"
            data-toggle="collapse"
            data-target="#replyForm{{ $comment->id }}">
      <i class="fas fa-reply"></i> Reply
    </button>
  @endunless

  {{-- Share (always) --}}
  <button
  type="button"
  class="btn btn-sm btn-outline-secondary rounded-pill px-3 share-comment-btn"
  data-comment-id="{{ $comment->id }}"
  data-comment-slug="{{ $comment->slug }}">
  <i class="fas fa-share"></i> Share
</button>


  @if(!$isDeleted && ($me->id === $comment->user_id || $me->is_mod))
    {{-- Dropdown --}}
    <div class="dropdown">
      <button class="btn btn-sm btn-light p-1 rounded-circle" data-toggle="dropdown">
        <i class="fas fa-ellipsis-h"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-right">
      @if($me->id === $comment->user_id)
      <form class="dropdown-item p-0 m-0">
        @csrf
        <button type="button" class="btn btn-link w-100 text-left p-2 edit-btn text-primary" data-toggle="collapse" data-target="#editForm{{ $comment->id }}">
          Edit
        </button>
      </form>
    @endif
    <form method="POST" action="{{ route('comments.delete', ['post'=>$postSlug,'comment'=>$comment->slug]) }}" class="dropdown-item p-0 m-0 delete-form" data-type="comment">
      @csrf @method('DELETE')
      <button
        type="button"
        class="btn btn-link w-100 text-left p-2 text-danger delete-btn"
      >
        Delete
      </button>
    </form>

      </div>
    </div>
  @endif


</div>

     <!-- Reply Form (AJAX submission) -->
<div class="collapse mt-1" id="replyForm{{ $comment->id }}">
  <form action="{{ route('comments.reply.store', ['post' => $postSlug, 'comment' => $comment->slug]) }}" 
        method="POST" 
        class="reply-form" 
        data-comment-id="{{ $comment->id }}">
    @csrf
    <div class="form-group mb-1">
      <div id="editor-reply-{{ $comment->id }}" class="quill-editor" style="height: 100px;"></div>
      <input type="hidden" name="body" id="input-reply-{{ $comment->id }}">
    </div>

    <div class="d-flex justify-content-between">
      <button type="button" class="btn btn-light btn-sm cancel-reply-btn" data-toggle="collapse" data-target="#replyForm{{ $comment->id }}">
        Cancel
      </button>
      <button type="submit" class="btn btn-secondary btn-sm submit-reply-btn">Submit Reply</button>
    </div>
  </form>
</div>

      <!-- Edit Form -->
      <div class="collapse mt-1" id="editForm{{ $comment->id }}">
      <form class="edit-form"  action="{{ route('comments.update', $comment->slug) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="form-group mb-1">
            {{-- Preload the existing HTML into the div --}}
            <div id="editor-edit-{{ $comment->id }}"
                class="quill-editor"
                style="height: 100px;">
              {!! $comment->body !!}
            </div>
            <input type="hidden" name="body" id="input-edit-{{ $comment->id }}">
          </div>
          <div class="text-right">
          <button type="button" class="btn btn-light btn-sm cancel-edit-btn" data-toggle="collapse" data-target="#editForm{{ $comment->id }}">
        Cancel
      </button>
            <button type="submit" class="btn btn-secondary btn-sm">Update Comment</button>
          </div>
        </form>
      </div>
      
      <!-- Replies Section with Chunking -->
      @php
          $allReplies = $comment->replies;
          $totalReplies = $allReplies->count();
      @endphp
      @if($totalReplies > 0)
        <div class="thread-line ml-3 pl-2">
          @if($totalReplies <= $maxVisible)
            @foreach($allReplies as $reply)
              @include('forum._comment', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
          @else
            @foreach($allReplies->take($maxVisible) as $reply)
              @include('forum._comment', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
            @php
                $remainingReplies = $allReplies->slice($maxVisible);
            @endphp
            @foreach($remainingReplies->chunk(20) as $chunkIndex => $chunk)
                <a class="d-block text-primary toggle-hidden-replies" data-target="#moreReplies{{ $comment->id }}_chunk{{ $chunkIndex }}" style="font-size: 0.75rem; cursor: pointer;">show more replies</a>
                <div class="collapse" id="moreReplies{{ $comment->id }}_chunk{{ $chunkIndex }}">
                  @foreach($chunk as $reply)
                    @include('forum._comment', ['comment' => $reply, 'level' => $level + 1])
                  @endforeach
                </div>
            @endforeach
          @endif
        </div>
      @endif
    </div>
  </div>
</div>
