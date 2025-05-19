@php
  $canChange = $isOwner && isset($form) && ($form->optional_group_a && $form->optional_group_b);
  
@endphp
@section('title', 'Profile')

@extends('layouts.main')

@section('content')
<div class="container my-5">
  <h1 class="my-profile">{{ $isOwner ? 'My Profile' : $user->name . '\'s Profile' }}</h1>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <!-- Nav tabs - Different tabs for owner vs viewer -->
  <ul class="nav nav-tabs" id="profileTabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#account" role="tab">Account Info</a>
    </li>
    @if($isOwner)
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#form-responses" role="tab">Form Responses</a>
      </li>
    @endif
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#my-posts" role="tab">{{ $isOwner ? 'My' : 'Their' }} Posts</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#my-comments" role="tab">{{ $isOwner ? 'My' : 'Their' }} Comments</a>
    </li>
    @if($isOwner)
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#hidden-posts" role="tab">
          Hidden Posts
        </a>
      </li>
    @endif
  </ul>

  <div class="tab-content mt-4">
    {{-- ACCOUNT INFO --}}
    <div class="tab-pane fade show active" id="account" role="tabpanel">
      @php
        $avatarUrl = $user->profile_photo
          ? asset('storage/' . $user->profile_photo)
          : "https://ui-avatars.com/api/?name=" 
              . urlencode($user->name)
              . "&background=0D8ABC&color=fff&rounded=true&size=128";
      @endphp

      <div class="row">
<div class="box">
        <div class="avatar col-md-3 text-center">
          <div class="mb-4">
            <img id="mainAvatar"
                src="{{ $avatarUrl }}"
                class="rounded-circle mb-2"
                width="120" height="120">

            @if($isOwner)
              <input type="file"
                    id="photoInput"
                    accept="image/*"
                    style="display:none"
                    {{ $canChange ? '' : 'disabled' }}
                     {{ $canChange ? '' : 'data-toggle="tooltip" data-placement="top" data-tooltip-message="form-edit-tooltip"' }}
                    >

              <div>
                <button id="pickImageBtn"
                        class="btn btn-sm btn-secondary mt-2"
                        {{ $canChange ? '' : 'disabled' }}
                         {{ $canChange ? '' : 'data-toggle="tooltip" data-placement="top" data-tooltip-message="form-edit-tooltip"' }}
                        >
                  Change Photo
                </button>
              </div>
            @endif
          </div>
        </div>
          <div class="profile-name d-flex justify-content-between align-items-center mb-3">
            <div class="profile-name d-flex flex-column">
            <h4 class="mb-0 d-flex align-items-center">
              <span id="displayUsername">{{ $user->name }}</span>
              @inlineBadges($user, 64)
              
              @if($isOwner)
                <button id="editUsernameBtn" style="margin-left: 100px !important; "class="btn btn-sm btn-outline-secondary ml-2" 
                    {{ $canChange ? '' : 'disabled' }}
                    {{ $canChange ? '' : 'data-toggle="tooltip" data-placement="top" data-tooltip-message="form-edit-tooltip"' }}>
                  <i class="fas fa-pencil-alt" ></i><span class="edit">Edit Profile</span>
                </button>
              @endif
            </h4>
              
              @if($user->form)
                <p class="mb-1">{{ $user->form->first_name }} {{ $user->form->middle_name }} {{ $user->form->last_name }} @if($user->form->suffix) ({{ $user->form->suffix }}) @endif</p>
              @endif
              @if($isOwner)
              <p class="mb-1">{{ $user->email }}</p>
              @endif
              <p><strong>Member since:</strong> {{ $user->created_at->format('F Y') }}</p>
            </div>
            @if($isOwner)
              <div id="usernameFormContainer" style="display: none;">
                <form id="usernameForm" class="d-flex">
                  @csrf
                  <input type="text" name="name" id="nameInput" value="{{ $user->name }}" class="form-control mr-2" required>
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button type="button" id="cancelUsernameEdit" class="btn btn-secondary ml-2">Cancel</button>
                </form>
              </div>
            @endif
          </div>

          
          <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">About Me</h5>
              @if($isOwner)
                <button id="editAboutBtn" class="btn btn-sm btn-outline-secondary" 
                {{ $canChange ? '' : 'disabled' }}
                 {{ $canChange ? '' : 'data-toggle="tooltip" data-placement="top" data-tooltip-message="form-edit-tooltip"' }}
                >
                  <i class="fas fa-pencil-alt"></i> Edit
                </button>
              @endif
            </div>
            <div class="card-body">
              <div id="aboutDisplay">
                <p id="aboutText">{{ $user->about_me ?? 'No information provided.' }}</p>
              </div>
              @if($isOwner)
                <div id="aboutFormContainer" style="display: none; justify-content: center;">
                  <form id="aboutForm">
                    @csrf
                    <div class="form-group">
                      <textarea id="aboutInput" name="about_me" class="form-control" maxlength="500" rows="4">{{ $user->about_me ?? '' }}</textarea>
                      <small class="text-muted"><span id="charCount">0</span>/500 characters</small>
                    </div>
                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">Save</button>
                      <button type="button" id="cancelAboutEdit" class="btn btn-secondary ml-2">Cancel</button>
                    </div>
                  </form>
                </div>
              @endif
            </div>
            @badgesSection($user, 180) 
          </div>
        </div>
      </div>
    </div> {{-- /#account --}}
    
    {{-- FORM RESPONSES (Only visible to owner) --}}
    @if($isOwner)
      <div class="tab-pane fade" id="form-responses" role="tabpanel">
        <h3>My Form Responses</h3>
        @if(!$form)
          <div class="alert alert-info">
            You haven't answered the form yet.
            <a href="{{ route('form.show') }}" class="alert-link">Answer it now</a>.
          </div>
        @else
          <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Full Name:</strong>
              {{ $form->last_name }}, {{ $form->first_name }} {{ $form->middle_name }}
              @if($form->suffix) ({{ $form->suffix }}) @endif
            </li>
            <li class="list-group-item"><strong>Address:</strong> {{ $form->current_address }}</li>
            <li class="list-group-item"><strong>Courses:</strong> {{ $form->graduated_course }}</li>
            <li class="list-group-item"><strong>Specialization:</strong> {{ $form->field_of_specialization }}</li>
            <li class="list-group-item"><strong>Graduation Year:</strong> {{ $form->graduation_year }}</li>
            <li class="list-group-item"><strong>Graduate Studies Within 12m?:</strong> {{ $form->graduate_study_status }}</li>
            <li class="list-group-item"><strong>Present Employment:</strong> {{ $form->present_employment_status }}</li>
            @if(in_array($form->present_employment_status, ['Unemployed','Self-employed']))
              <li class="list-group-item"><strong>Had Job Before?:</strong> {{ $form->job_experience_status ?? 'N/A' }}</li>
            @endif
            <li class="list-group-item"><strong>First Employment Date:</strong> {{ $form->employment_date }}</li>
            <li class="list-group-item"><strong>First Workplace:</strong> {{ $form->first_workplace }}</li>
            <li class="list-group-item"><strong>Position:</strong> {{ $form->position }}</li>
            <li class="list-group-item"><strong>Employer:</strong> {{ $form->first_employer_name }}</li>
            <li class="list-group-item"><strong>Office Address:</strong> {{ $form->office_address }}</li>
            <li class="list-group-item"><strong>Employer Contact:</strong> {{ $form->employer_contact }}</li>
            <li class="list-group-item"><strong>Time to First Job:</strong> {{ $form->time_to_first_job }}</li>
            <li class="list-group-item"><strong>Job Related to Degree?:</strong> {{ $form->job_related_to_degree }}</li>
          </ul>
          {{-- Optional Qs --}}
          @if($form->optional_group_a || $form->optional_group_b)
            <ul class="list-group mb-3">
              @if($form->optional_group_a)
                <li class="list-group-item"><strong>Optional Group A: </strong> {{ $form->optional_group_a }}<br>
                <p class="small text-muted">
                1. The timeliness/relevance of instructional delivery and supervision is good at CLSU.<br>
                2. The training and academic preparation I obtained from CLSU prepared me for my employment.</p></li>
              @else
                <form method="POST" action="{{ route('profile.optional.update') }}">
                  @csrf
                  <div class="form-group">
                    <label>Group A – please read and select:</label>
                    <p class="small text-muted">
                      1. The timeliness/relevance of instructional delivery and supervision is good at CLSU.<br>
                      2. The training and academic preparation I obtained from CLSU prepared me for my employment.
                    </p>
                    <select name="optional_group_a" class="form-control">
                      <option value="">— choose —</option>
                      <option value="Agree with both">I agree with both statements</option>
                      <option value="Agree with Statement 1 only">I agree with statement 1 only</option>
                      <option value="Agree with Statement 2 only">I agree with statement 2 only</option>
                      <option value="Disagree with both">I disagree with both</option>
                    </select>
                    <button class="btn btn-sm btn-success mt-2">Save Group A Answer</button>
                  </div>
                </form>
              @endif

              @if($form->optional_group_b)
                <li class="list-group-item"><strong> Optional Group B: </strong> {{ $form->optional_group_b }} <br>
                <p class="small text-muted">  
                3. The career service support in CLSU is sufficient to enable me to find my first job.<br>
                4. The learning technologies and facilities of the University helped me become a competitive graduate.</p></li>
              @else
                <form method="POST" action="{{ route('profile.optional.update') }}">
                  @csrf
                  <div class="form-group">
                    <label>Group B – please read and select:</label>
                    <p class="small text-muted">
                      3. The career service support in CLSU is sufficient to enable me to find my first job.<br>
                      4. The learning technologies and facilities of the University helped me become a competitive graduate.
                    </p>
                    <select name="optional_group_b" class="form-control">
                      <option value="">— choose —</option>
                      <option value="Agree with both">I agree with both statements</option>
                      <option value="Agree with Statement 3 only">I agree with statement 3 only</option>
                      <option value="Agree with Statement 4 only">I agree with statement 4 only</option>
                      <option value="Disagree with both">I disagree with both</option>
                    </select>
                    <button class="btn btn-sm btn-success mt-2">Save Group B Answer</button>
                  </div>
                </form>
              @endif
            </ul>
          @else
            {{-- Both unanswered: show combined form --}}
            <div class="alert alert-warning">
              You haven't answered the optional questions.
            </div>
            <form method="POST" action="{{ route('profile.optional.update') }}">
              @csrf
              <div class="form-group">
                <label>Group A – please read and select:</label>
                <p class="small text-muted">
                  1. The timeliness/relevance of instructional delivery and supervision is good at CLSU.<br>
                  2. The training and academic preparation I obtained from CLSU prepared me for my employment.
                </p>
                <select name="optional_group_a" class="form-control">
                  <option value="">— choose —</option>
                  <option value="Agree with both">I agree with both statements</option>
                  <option value="Agree with Statement 1 only">I agree with statement 1 only</option>
                  <option value="Agree with Statement 2 only">I agree with statement 2 only</option>
                  <option value="Disagree with both">I disagree with both</option>
                </select>
              </div>

              <div class="form-group">
                <label>Group B – please read and select:</label>
                <p class="small text-muted">
                  3. The career service support in CLSU is sufficient to enable me to find my first job.<br>
                  4. The learning technologies and facilities of the University helped me become a competitive graduate.
                </p>
                <select name="optional_group_b" class="form-control">
                  <option value="">— choose —</option>
                  <option value="Agree with both">I agree with both statements</option>
                  <option value="Agree with Statement 3 only">I agree with statement 3 only</option>
                  <option value="Agree with Statement 4 only">I agree with statement 4 only</option>
                  <option value="Disagree with both">I disagree with both</option>
                </select>
              </div>

              <button class="btn btn-success">Save Optional Answers</button>
            </form>
          @endif

        @endif
      </div> {{-- /#form-responses --}}
    @endif

    {{-- POSTS --}}
<div class="tab-pane fade" id="my-posts" role="tabpanel">
  <h3>{{ $isOwner ? 'My' : $user->name . '\'s' }} Posts</h3>
  @if($posts->isEmpty())
    <div class="alert alert-info">No posts yet.</div>
  @else
    <div class="posts-container">
      @foreach($posts as $post)
        @if($post->body === '[Deleted Content]' && $post->totalCommentCount() === 0 && !$isOwner)
          @continue
        @endif

        <div class="post-card mb-3 compact" data-href="{{ route('forum.show', ['post' => $post->slug]) }}">
          {{-- HEADER ROW: avatar, user/name/times --}}
          <div class="post-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              {{-- Avatar --}}
              <img src="{{ $post->user->profile_photo
                          ? asset('storage/'.$post->user->profile_photo)
                          : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&rounded=true' }}"
                class="post-avatar mr-2" style="width:30px; height:30px;">

              {{-- Name + timestamp --}}
              <div class="post-meta">
                <div class="text-muted small">
                  <span class="date-tooltip" title="{{ $post->created_at->toDayDateTimeString() }}">
                    Posted {{ $post->created_at->diffForHumans() }}
                  </span>
                  @if($post->updated_at->gt($post->created_at))
                    • <span class="date-tooltip" title="{{ $post->updated_at->toDayDateTimeString() }}">
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
                {{-- author can edit --}}
                @if($me->id === $post->user_id && $post->body!=='[Deleted Content]')
                  <a class="dropdown-item" href="{{ route('forum.edit', $post) }}">Edit</a>
                @endif
                {{-- owner or mod delete --}}
                @if($me->id === $post->user_id || $me->is_mod)
                  <form method="POST"
                        action="{{ route('posts.delete', $post) }}"
                        class="dropdown-item p-0 delete-form"
                        data-type="post">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-link w-100 text-left p-2 delete-btn">
                      Delete
                    </button>
                  </form>
                @endif
                {{-- others hide/report --}}
                @if($me->id !== $post->user_id && $post->body!=='[Deleted Content]')
                  <form method="POST" action="{{ route('forum.hide', $post) }}" class="dropdown-item p-0">
                    @csrf
                    <button class="btn btn-link w-100 text-left p-2">Hide</button>
                  </form>
                  <form method="POST" action="{{ route('forum.report', $post) }}" class="dropdown-item p-0">
                    @csrf
                    <button class="btn btn-link w-100 text-left p-2">Report</button>
                  </form>
                @endif
              </div>
            </div>
          </div>

          {{-- TITLE ROW --}}
          <h5 class="post-title mt-2 mb-1">{{ $post->title }}</h5>

          {{-- BODY ROW (clamped to ~3 lines) --}}
          @if($post->body!=='[Deleted Content]')
            <p class="post-body clamp-3-lines">
              {!! $post->body !!}
            </p>
          @else
            @if($isOwner)
              <p class="text-muted">[Deleted Content]</p>
            @endif
          @endif

          {{-- MEDIA INDICATOR --}}
          @if($post->video || (isset($post->images) && count($post->images) > 0))
            <div class="media-indicator small text-muted mt-1">
              @if($post->video)
                <i class="fas fa-video"></i> Has video
              @else
                <i class="fas fa-image"></i> {{ count($post->images) }} image{{ count($post->images) > 1 ? 's' : '' }}
              @endif
            </div>
          @endif

          {{-- ACTIONS ROW --}}
          <div class="post-actions d-flex align-items-center mt-2">
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

            {{-- Comments --}}
            <a href="{{ route('forum.show', $post) }}"
              class="btn btn-sm btn-outline-info rounded-pill px-3 ml-2">
              <i class="fas fa-comment"></i> {{ $post->totalCommentCount() }}
            </a>

            {{-- Share --}}
            <button
              class="btn btn-sm btn-outline-secondary rounded-pill px-3 ml-2 share-post-btn"
              data-post-slug="{{ $post->slug }}">
              <i class="fas fa-share"></i>
            </button>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div> {{-- /#my-posts --}}

{{-- COMMENTS --}}
<div class="tab-pane fade" id="my-comments" role="tabpanel">
  <h3>{{ $isOwner ? 'My' : $user->name . '\'s' }} Comments</h3>
  @if($comments->isEmpty())
    <div class="alert alert-info">No comments yet.</div>
  @else
    <div class="comments-container">
      @foreach($comments as $comment)
        @php
          $isDeleted = $comment->body === '[Deleted Content]' || $comment->body === '[Removed by moderator]';
          
          // Skip deleted comments for non-owners
          if ($isDeleted && !$isOwner) {
            continue;
          }
          
          // Get parent comment info if it's a reply
          $isReply = $comment->parent_id !== null;
          $parentUser = $isReply ? App\Models\Comment::find($comment->parent_id)->user : null;
        @endphp
        
        <div class="comment-card mb-3 p-3 border rounded" data-href="{{ route('forum.show', $comment->post) . '#comment-' . $comment->id }}">
          {{-- Post Title --}}
          <div class="comment-post-title mb-2">
            <a href="{{ route('forum.show', $comment->post) }}" class="font-weight-bold">
              {{ $comment->post->title }}
            </a>
          </div>
          
          {{-- Comment info --}}
          <div class="comment-info d-flex align-items-start mb-1">
            <img src="{{ $user->profile_photo 
                      ? asset('storage/'.$user->profile_photo) 
                      : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&rounded=true' }}"
                class="rounded-circle mr-2" style="width:24px; height:24px;">
                
            <div>
              <div class="small">
                @if($isReply && $parentUser)
                  <span>replied to <a href="{{ route('users.show', $parentUser->name) }}">{{ $parentUser->name }}</a></span>
                @else
                  <span>commented</span>
                @endif
                <span class="text-muted date-tooltip" title="{{ $comment->created_at->toDayDateTimeString() }}">
                  {{ $comment->created_at->diffForHumans() }}
                </span>
              </div>
            </div>
          </div>
          
          {{-- Comment content --}}
          <div class="comment-body">
            @if($isDeleted)
              {{-- Show deleted content to owner only --}}
              @if($isOwner)
                <p class="text-muted">
                  {{ $comment->body === '[Deleted Content]' 
                      ? '[deleted by you]' 
                      : '[removed by moderator]' }}
                </p>
              @endif
            @else
              <p class="clamp-3-lines">
                {!! $comment->body !!}
              </p>
            @endif
          </div>
          
          {{-- Comment actions --}}
          <div class="comment-actions d-flex align-items-center mt-2">
            @php $me = Auth::user(); @endphp
            @php 
  $commentDeleted = $comment->body === '[Deleted Content]' || $comment->body === '[Removed by moderator]';
@endphp

@if($commentDeleted)
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
            
            {{-- View/Reply link --}}
            <a href="{{ route('forum.show', $comment->post) . '#comment-' . $comment->id }}"
               class="btn btn-sm rounded-pill px-2 btn-outline-secondary mr-2">
              <i class="fas fa-reply"></i> Reply
            </a>
            
            {{-- Share --}}
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary rounded-pill px-2 share-comment-btn"
              data-comment-id="{{ $comment->id }}">
              <i class="fas fa-share"></i>
            </button>
            
            {{-- Dropdown (if not deleted and is owner or mod) --}}
            @if(!$isDeleted && ($me->id === $comment->user_id || $me->is_mod))
              <div class="dropdown ml-auto">
                <button class="btn btn-sm btn-light p-1 rounded-circle" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                  @if($me->id === $comment->user_id)
                    <a href="{{ route('forum.show', $comment->post) . '#comment-' . $comment->id }}" class="dropdown-item p-2">
                      Edit
                    </a>
                  @endif
                  <form method="POST"
                        action="{{ route('comments.delete', ['post'=>$comment->post->slug,'comment'=>$comment->slug]) }}"
                        class="dropdown-item p-0 delete-form"
                        data-type="comment">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-link w-100 text-left p-2 delete-btn">
                      Delete
                    </button>
                  </form>
                </div>
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div> {{-- /#my-comments --}}

    {{-- HIDDEN POSTS (Only visible to owner) --}}
    @if($isOwner)
      <div class="tab-pane fade" id="hidden-posts" role="tabpanel">
        <h4>Hidden Posts</h4>
        @if($hiddenPosts->isEmpty())
          <div class="alert alert-info">You haven't hidden any posts.</div>
        @else
          @foreach($hiddenPosts as $hidden)
            @php $p = $hidden->post; @endphp
            <div class="d-flex justify-content-between align-items-center mb-2">
              <a href="{{ route('forum.show', $p) }}">
                {{ $p->title }}
              </a>
              <form action="{{ route('forum.unhide', $p) }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-secondary">Unhide</button>
              </form>
            </div>
          @endforeach
        @endif
      </div>
    @endif
  </div> {{-- /.tab-content --}}

  @if($isOwner)
    <!-- ▶️ CROPPER MODAL (Only for profile owner) -->
    <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Crop &amp; Adjust</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body text-center">
            <!-- 1:1 preview area -->
            <div style="position:relative; width:300px; height:300px; margin:auto; overflow:hidden; border-radius:50%; background:#f0f0f0;">
              <img id="cropImage" style="max-width:none; display:block; /* will be repositioned by Cropper.js */">
            </div>
            <!-- controls -->
            <div class="d-flex justify-content-center align-items-center mt-3">
              <button id="btnRotateLeft"  class="btn btn-sm btn-secondary me-2">⟲</button>
              <button id="btnRotateReset" class="btn btn-sm btn-secondary me-2">Reset</button>
              <button id="btnRotateRight" class="btn btn-sm btn-secondary">⟳</button>
            </div>
            <div class="d-flex align-items-center mt-3" style="gap: 0.5rem;">
              <img src="https://img.icons8.com/ios/24/000000/zoom-out.png" alt="Zoom Out">
              <input type="range" id="zoomSlider" class="flex-grow-1 zoom-range" step="0.01">
              <img src="https://img.icons8.com/ios/32/000000/zoom-in.png" alt="Zoom In">
            </div>
          </div>
          <div class="modal-footer">
            <button id="cropSave" class="btn btn-primary">Save</button>
            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  @endif

@endsection

@section('scripts')
<script>
$(function(){

  $('[data-toggle="tooltip"][data-tooltip-message="form-edit-tooltip"]').tooltip({
    title: "Please answer the two optional questions on the form tab to edit!"
  });

  const pickBtn    = document.getElementById('pickImageBtn');
  const fileInput  = document.getElementById('photoInput');
  const mainAvatar = document.getElementById('mainAvatar');
  const modalEl    = $('#cropModal');
  const imgEl      = document.getElementById('cropImage');
  const zoomSlider = document.getElementById('zoomSlider');
  const btnLeft    = document.getElementById('btnRotateLeft');
  const btnRight   = document.getElementById('btnRotateRight');
  const btnReset   = document.getElementById('btnRotateReset');
  const saveBtn    = document.getElementById('cropSave');
  let cropper, minZoom, naturalZoom;

  // 1) Open file picker
  pickBtn.addEventListener('click', ()=> fileInput.click());

  // 2) When file selected, load into img & show modal
  fileInput.addEventListener('change', e=>{
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => {
      imgEl.src = reader.result;
      modalEl.modal('show');
    };
    reader.readAsDataURL(file);
  });

  // 3) Initialize Cropper once modal is shown
  modalEl.on('shown.bs.modal', () => {
    // destroy old instance if any
    if (cropper) { cropper.destroy(); cropper = null; }

    cropper = new Cropper(imgEl, {
      viewMode: 3, // make sure entire image stays visible in canvas
      dragMode: 'move', // drag image only
      aspectRatio: 1, // square crop box (visually appears circular due to container mask)
      autoCropArea: 1,
      movable: true,
      zoomable: true,         // ✅ allow programmatic zoom
      zoomOnWheel: false,     // ❌ disable mouse wheel zoom
      rotatable: true,
      scalable: false,
      cropBoxResizable: false, // fixed size
      cropBoxMovable: false,   // locked position
      background: false,
      responsive: true,
      zoomOnWheel: false, // 🚫 disable scroll zoom
      ready() {
        // Fit image inside circle
        const imageData = cropper.getImageData();
        minZoom     = imageData.width  / imageData.naturalWidth;
        naturalZoom = imageData.height / imageData.naturalHeight;
        const initialZoom = Math.max(minZoom, naturalZoom);
        
        zoomSlider.min   = initialZoom;
        zoomSlider.max   = initialZoom * 3;
        zoomSlider.step  = 0.01;
        zoomSlider.value = initialZoom;

        cropper.zoomTo(initialZoom);
      }
    });
  });

  // 4) Slider → change zoom
  zoomSlider.addEventListener('input', () => {
    if (cropper) {
      cropper.zoomTo(parseFloat(zoomSlider.value));
    }
  });

  // 5) Rotate
  btnLeft .addEventListener('click', ()=> cropper.rotate(-90));
  btnRight.addEventListener('click', ()=> cropper.rotate( 90));
  
  // 6) Reset all transforms
  btnReset.addEventListener('click', ()=> {
    cropper.reset();
    // reset slider to initialZoom again
    zoomSlider.value = zoomSlider.min;
  });

  // 7) Save: get blob & POST
  saveBtn.addEventListener('click', ()=> {
    cropper.getCroppedCanvas({ width:300, height:300 }).toBlob(blob=>{
      const fd = new FormData();
      fd.append('_token','{{ csrf_token() }}');
      fd.append('photo', blob, 'avatar.png');

      fetch("{{ route('profile.photo.update') }}", {
        method:'POST', body: fd
      })
      .then(r=> {
        if (!r.ok) throw new Error('Upload failed');
        return r.json();
      })
      .then(json=> {
        mainAvatar.src = json.url;
        Swal.fire({
          toast:true, position:'top-end', icon:'success',
          title:'Profile photo updated', showConfirmButton:false,
          timer:1500
        });
        modalEl.modal('hide');
      })
      .catch(()=> {
        Swal.fire('Upload failed','Please try again','error');
      });
    }, 'image/png');
  });
  
  // Username Edit Functionality
  const editUsernameBtn = document.getElementById('editUsernameBtn');
  const usernameFormContainer = document.getElementById('usernameFormContainer');
  const displayUsername = document.getElementById('displayUsername');
  const usernameForm = document.getElementById('usernameForm');
  const nameInput = document.getElementById('nameInput');
  const cancelUsernameEdit = document.getElementById('cancelUsernameEdit');
  
  // Show username edit form
  editUsernameBtn.addEventListener('click', function() {
    editUsernameBtn.style.display = 'none';
    usernameFormContainer.style.display = 'block';
    nameInput.focus();
  });
  
  // Cancel username edit
  cancelUsernameEdit.addEventListener('click', function() {
    usernameFormContainer.style.display = 'none';
    editUsernameBtn.style.display = 'inline-block';
    nameInput.value = displayUsername.textContent;
  });
  
  // Submit username change
  usernameForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const newUsername = nameInput.value.trim();
    
    // Check if username has spaces
    if (newUsername.includes(' ')) {
      Swal.fire('Invalid Username', 'Username cannot contain spaces', 'error');
      return;
    }
    
    
    // Send AJAX request to update username
    fetch("{{ route('profile.username.update') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ name: newUsername })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayUsername.textContent = newUsername;
        usernameFormContainer.style.display = 'none';
        editUsernameBtn.style.display = 'inline-block';
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Username updated successfully',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire('Error', data.message || 'Username could not be updated', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire('Error', 'Something went wrong', 'error');
    });
  });
  
  // About Me Functionality
  const editAboutBtn = document.getElementById('editAboutBtn');
  const aboutDisplay = document.getElementById('aboutDisplay');
  const aboutFormContainer = document.getElementById('aboutFormContainer');
  const aboutForm = document.getElementById('aboutForm');
  const aboutInput = document.getElementById('aboutInput');
  const aboutText = document.getElementById('aboutText');
  const charCount = document.getElementById('charCount');
  const cancelAboutEdit = document.getElementById('cancelAboutEdit');
  
  // Update character count
  aboutInput.addEventListener('input', function() {
    charCount.textContent = this.value.length;
  });
  
  // Initialize character count
  charCount.textContent = aboutInput.value.length;
  
  // Show about edit form
  editAboutBtn.addEventListener('click', function() {
    aboutDisplay.style.display = 'none';
    aboutFormContainer.style.display = 'block';
    aboutInput.focus();
  });
  
  // Cancel about edit
  cancelAboutEdit.addEventListener('click', function() {
    aboutFormContainer.style.display = 'none';
    aboutDisplay.style.display = 'block';
  });
  
  // Submit about change
  aboutForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const newAbout = aboutInput.value.trim();
    
    // Send AJAX request to update about me
    fetch("{{ route('profile.about.update') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ about_me: newAbout })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        aboutText.textContent = newAbout || 'Tell us about yourself...';
        aboutFormContainer.style.display = 'none';
        aboutDisplay.style.display = 'block';
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'About me updated successfully',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire('Error', data.message || 'About me could not be updated', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire('Error', 'Something went wrong', 'error');
    });
  });
});
</script>
@endsection