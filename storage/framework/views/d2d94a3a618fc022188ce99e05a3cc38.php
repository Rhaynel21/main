<?php
  $canChange = $isOwner && isset($form) && ($form->optional_group_a && $form->optional_group_b);
  
?>
<?php $__env->startSection('title', 'Profile'); ?>



<?php $__env->startSection('content'); ?>
<div class="container my-5">
  <h1><?php echo e($isOwner ? 'My Profile' : $user->name . '\'s Profile'); ?></h1>

  <?php if(session('status')): ?>
    <div class="alert alert-success"><?php echo e(session('status')); ?></div>
  <?php endif; ?>

  <?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
  <?php endif; ?>

  <!-- Nav tabs - Different tabs for owner vs viewer -->
  <ul class="nav nav-tabs" id="profileTabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#account" role="tab">Account Info</a>
    </li>
    <?php if($isOwner): ?>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#form-responses" role="tab">Form Responses</a>
      </li>
    <?php endif; ?>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#my-posts" role="tab"><?php echo e($isOwner ? 'My' : 'Their'); ?> Posts</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#my-comments" role="tab"><?php echo e($isOwner ? 'My' : 'Their'); ?> Comments</a>
    </li>
    <?php if($isOwner): ?>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#hidden-posts" role="tab">
          Hidden Posts
        </a>
      </li>
    <?php endif; ?>
  </ul>

  <div class="tab-content mt-4">
    
    <div class="tab-pane fade show active" id="account" role="tabpanel">
      <?php
        $avatarUrl = $user->profile_photo
          ? asset('storage/' . $user->profile_photo)
          : "https://ui-avatars.com/api/?name=" 
              . urlencode($user->name)
              . "&background=0D8ABC&color=fff&rounded=true&size=128";
      ?>

      <div class="row">
        <div class="col-md-4 text-center">
          <div class="mb-4">
            <img id="mainAvatar"
                src="<?php echo e($avatarUrl); ?>"
                class="rounded-circle mb-2"
                width="120" height="120">

            <?php if($isOwner): ?>
              <input type="file"
                    id="photoInput"
                    accept="image/*"
                    style="display:none"
                    <?php echo e($canChange ? '' : 'disabled'); ?>

                     <?php echo e($canChange ? '' : 'data-toggle="tooltip" data-placement="top" data-tooltip-message="form-edit-tooltip"'); ?>

                    >

              <div>
                <button id="pickImageBtn"
                        class="btn btn-sm btn-secondary mt-2"
                        <?php echo e($canChange ? '' : 'disabled'); ?>

                         <?php echo e($canChange ? '' : 'data-toggle="tooltip" data-placement="top" data-tooltip-message="form-edit-tooltip"'); ?>

                        >
                  Change Photo
                </button>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-8">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column">
            <h4 class="mb-0 d-flex align-items-center">
              <span id="displayUsername"><?php echo e($user->name); ?></span>
              <?php echo App\Helpers\BadgeHelper::renderInlineBadges($user, 64); ?>
              
              <?php if($isOwner): ?>
                <button id="editUsernameBtn" class="btn btn-sm btn-outline-secondary ml-2" 
                    <?php echo e($canChange ? '' : 'disabled'); ?>

                    <?php echo e($canChange ? '' : 'data-toggle="tooltip" data-placement="top" data-tooltip-message="form-edit-tooltip"'); ?>>
                  <i class="fas fa-pencil-alt"></i>
                </button>
              <?php endif; ?>
            </h4>
              
              <?php if($user->form): ?>
                <p class="mb-1"><?php echo e($user->form->first_name); ?> <?php echo e($user->form->middle_name); ?> <?php echo e($user->form->last_name); ?> <?php if($user->form->suffix): ?> (<?php echo e($user->form->suffix); ?>) <?php endif; ?></p>
              <?php endif; ?>
              <?php if($isOwner): ?>
              <p class="mb-1"><?php echo e($user->email); ?></p>
              <?php endif; ?>
              <p><strong>Member since:</strong> <?php echo e($user->created_at->format('F Y')); ?></p>
            </div>
            <?php if($isOwner): ?>
              <div id="usernameFormContainer" style="display: none;">
                <form id="usernameForm" class="d-flex">
                  <?php echo csrf_field(); ?>
                  <input type="text" name="name" id="nameInput" value="<?php echo e($user->name); ?>" class="form-control mr-2" required>
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button type="button" id="cancelUsernameEdit" class="btn btn-secondary ml-2">Cancel</button>
                </form>
              </div>
            <?php endif; ?>
          </div>
          
          <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">About Me</h5>
              <?php if($isOwner): ?>
                <button id="editAboutBtn" class="btn btn-sm btn-outline-secondary" 
                <?php echo e($canChange ? '' : 'disabled'); ?>

                 <?php echo e($canChange ? '' : 'data-toggle="tooltip" data-placement="top" data-tooltip-message="form-edit-tooltip"'); ?>

                >
                  <i class="fas fa-pencil-alt"></i> Edit
                </button>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <div id="aboutDisplay">
                <p id="aboutText"><?php echo e($user->about_me ?? 'No information provided.'); ?></p>
              </div>
              <?php if($isOwner): ?>
                <div id="aboutFormContainer" style="display: none;">
                  <form id="aboutForm">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                      <textarea id="aboutInput" name="about_me" class="form-control" maxlength="500" rows="4"><?php echo e($user->about_me ?? ''); ?></textarea>
                      <small class="text-muted"><span id="charCount">0</span>/500 characters</small>
                    </div>
                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">Save</button>
                      <button type="button" id="cancelAboutEdit" class="btn btn-secondary ml-2">Cancel</button>
                    </div>
                  </form>
                </div>
              <?php endif; ?>
            </div>
            <?php echo App\Helpers\BadgeHelper::renderBadgesSection($user, 180); ?>
          </div>
        </div>
      </div>
    </div> 

    
    <?php if($isOwner): ?>
      <div class="tab-pane fade" id="form-responses" role="tabpanel">
        <h3>My Form Responses</h3>
        <?php if(!$form): ?>
          <div class="alert alert-info">
            You haven't answered the form yet.
            <a href="<?php echo e(route('form.show')); ?>" class="alert-link">Answer it now</a>.
          </div>
        <?php else: ?>
          <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Full Name:</strong>
              <?php echo e($form->last_name); ?>, <?php echo e($form->first_name); ?> <?php echo e($form->middle_name); ?>

              <?php if($form->suffix): ?> (<?php echo e($form->suffix); ?>) <?php endif; ?>
            </li>
            <li class="list-group-item"><strong>Address:</strong> <?php echo e($form->current_address); ?></li>
            <li class="list-group-item"><strong>Courses:</strong> <?php echo e($form->graduated_course); ?></li>
            <li class="list-group-item"><strong>Specialization:</strong> <?php echo e($form->field_of_specialization); ?></li>
            <li class="list-group-item"><strong>Graduation Year:</strong> <?php echo e($form->graduation_year); ?></li>
            <li class="list-group-item"><strong>Graduate Studies Within 12m?:</strong> <?php echo e($form->graduate_study_status); ?></li>
            <li class="list-group-item"><strong>Present Employment:</strong> <?php echo e($form->present_employment_status); ?></li>
            <?php if(in_array($form->present_employment_status, ['Unemployed','Self-employed'])): ?>
              <li class="list-group-item"><strong>Had Job Before?:</strong> <?php echo e($form->job_experience_status ?? 'N/A'); ?></li>
            <?php endif; ?>
            <li class="list-group-item"><strong>First Employment Date:</strong> <?php echo e($form->employment_date); ?></li>
            <li class="list-group-item"><strong>First Workplace:</strong> <?php echo e($form->first_workplace); ?></li>
            <li class="list-group-item"><strong>Position:</strong> <?php echo e($form->position); ?></li>
            <li class="list-group-item"><strong>Employer:</strong> <?php echo e($form->first_employer_name); ?></li>
            <li class="list-group-item"><strong>Office Address:</strong> <?php echo e($form->office_address); ?></li>
            <li class="list-group-item"><strong>Employer Contact:</strong> <?php echo e($form->employer_contact); ?></li>
            <li class="list-group-item"><strong>Time to First Job:</strong> <?php echo e($form->time_to_first_job); ?></li>
            <li class="list-group-item"><strong>Job Related to Degree?:</strong> <?php echo e($form->job_related_to_degree); ?></li>
          </ul>
          
          <?php if($form->optional_group_a || $form->optional_group_b): ?>
            <ul class="list-group mb-3">
              <?php if($form->optional_group_a): ?>
                <li class="list-group-item"><strong>Optional Group A: </strong> <?php echo e($form->optional_group_a); ?><br>
                <p class="small text-muted">
                1. The timeliness/relevance of instructional delivery and supervision is good at CLSU.<br>
                2. The training and academic preparation I obtained from CLSU prepared me for my employment.</p></li>
              <?php else: ?>
                <form method="POST" action="<?php echo e(route('profile.optional.update')); ?>">
                  <?php echo csrf_field(); ?>
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
              <?php endif; ?>

              <?php if($form->optional_group_b): ?>
                <li class="list-group-item"><strong> Optional Group B: </strong> <?php echo e($form->optional_group_b); ?> <br>
                <p class="small text-muted">  
                3. The career service support in CLSU is sufficient to enable me to find my first job.<br>
                4. The learning technologies and facilities of the University helped me become a competitive graduate.</p></li>
              <?php else: ?>
                <form method="POST" action="<?php echo e(route('profile.optional.update')); ?>">
                  <?php echo csrf_field(); ?>
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
              <?php endif; ?>
            </ul>
          <?php else: ?>
            
            <div class="alert alert-warning">
              You haven't answered the optional questions.
            </div>
            <form method="POST" action="<?php echo e(route('profile.optional.update')); ?>">
              <?php echo csrf_field(); ?>
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
          <?php endif; ?>

        <?php endif; ?>
      </div> 
    <?php endif; ?>

    
<div class="tab-pane fade" id="my-posts" role="tabpanel">
  <h3><?php echo e($isOwner ? 'My' : $user->name . '\'s'); ?> Posts</h3>
  <?php if($posts->isEmpty()): ?>
    <div class="alert alert-info">No posts yet.</div>
  <?php else: ?>
    <div class="posts-container">
      <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($post->body === '[Deleted Content]' && $post->totalCommentCount() === 0 && !$isOwner): ?>
          <?php continue; ?>
        <?php endif; ?>

        <div class="post-card mb-3 compact" data-href="<?php echo e(route('forum.show', ['post' => $post->slug])); ?>">
          
          <div class="post-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              
              <img src="<?php echo e($post->user->profile_photo
                          ? asset('storage/'.$post->user->profile_photo)
                          : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&rounded=true'); ?>"
                class="post-avatar mr-2" style="width:30px; height:30px;">

              
              <div class="post-meta">
                <div class="text-muted small">
                  <span class="date-tooltip" title="<?php echo e($post->created_at->toDayDateTimeString()); ?>">
                    Posted <?php echo e($post->created_at->diffForHumans()); ?>

                  </span>
                  <?php if($post->updated_at->gt($post->created_at)): ?>
                    • <span class="date-tooltip" title="<?php echo e($post->updated_at->toDayDateTimeString()); ?>">
                      edited <?php echo e($post->updated_at->diffForHumans()); ?>

                    </span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            
            <?php $me = Auth::user(); ?>
            <div class="dropdown">
              <button class="btn btn-sm btn-light p-1 rounded-circle" data-toggle="dropdown">
                <i class="fas fa-ellipsis-h"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                
                <?php if($me->id === $post->user_id && $post->body!=='[Deleted Content]'): ?>
                  <a class="dropdown-item" href="<?php echo e(route('forum.edit', $post)); ?>">Edit</a>
                <?php endif; ?>
                
                <?php if($me->id === $post->user_id || $me->is_mod): ?>
                  <form method="POST"
                        action="<?php echo e(route('posts.delete', $post)); ?>"
                        class="dropdown-item p-0 delete-form"
                        data-type="post">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="button" class="btn btn-link w-100 text-left p-2 delete-btn">
                      Delete
                    </button>
                  </form>
                <?php endif; ?>
                
                <?php if($me->id !== $post->user_id && $post->body!=='[Deleted Content]'): ?>
                  <form method="POST" action="<?php echo e(route('forum.hide', $post)); ?>" class="dropdown-item p-0">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-link w-100 text-left p-2">Hide</button>
                  </form>
                  <form method="POST" action="<?php echo e(route('forum.report', $post)); ?>" class="dropdown-item p-0">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-link w-100 text-left p-2">Report</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>

          
          <h5 class="post-title mt-2 mb-1"><?php echo e($post->title); ?></h5>

          
          <?php if($post->body!=='[Deleted Content]'): ?>
            <p class="post-body clamp-3-lines">
              <?php echo $post->body; ?>

            </p>
          <?php else: ?>
            <?php if($isOwner): ?>
              <p class="text-muted">[Deleted Content]</p>
            <?php endif; ?>
          <?php endif; ?>

          
          <?php if($post->video || (isset($post->images) && count($post->images) > 0)): ?>
            <div class="media-indicator small text-muted mt-1">
              <?php if($post->video): ?>
                <i class="fas fa-video"></i> Has video
              <?php else: ?>
                <i class="fas fa-image"></i> <?php echo e(count($post->images)); ?> image<?php echo e(count($post->images) > 1 ? 's' : ''); ?>

              <?php endif; ?>
            </div>
          <?php endif; ?>

          
          <div class="post-actions d-flex align-items-center mt-2">
          <?php if($post->body === '[Deleted Content]' || $post->body === '[Removed by moderator]'): ?>
            
            <div class="vote-controls d-flex align-items-center mr-3">
              <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                <i class="fas fa-arrow-up"></i>
              </button>
              <div class="mx-2">-</div>
              <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                <i class="fas fa-arrow-down"></i>
              </button>
            </div>
          <?php else: ?>
            
            <div class="vote-controls d-flex align-items-center mr-3">
              <form class="vote-form d-inline"
                    data-vote-url="<?php echo e(route('posts.vote',$post)); ?>"
                    method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="vote" value="1">
                <button type="button"
                        class="btn btn-sm <?php echo e(optional($post->votes()->where('user_id',Auth::id())->first())->vote == 1 ? 'btn-primary' : 'btn-outline-primary'); ?>">
                  <i class="fas fa-arrow-up"></i>
                </button>
              </form>

              <div class="vote-count mx-2 <?php echo e(($post->likesCount() - $post->dislikesCount()) < 0 ? 'text-danger' : ''); ?>">
                <?php echo e($post->likesCount() - $post->dislikesCount()); ?>

              </div>

              <form class="vote-form d-inline"
                    data-vote-url="<?php echo e(route('posts.vote',$post)); ?>"
                    method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="vote" value="-1">
                <button type="button"
                        class="btn btn-sm <?php echo e(optional($post->votes()->where('user_id',Auth::id())->first())->vote == -1 ? 'btn-danger' : 'btn-outline-danger'); ?>">
                  <i class="fas fa-arrow-down"></i>
                </button>
              </form>
            </div>
          <?php endif; ?>

            
            <a href="<?php echo e(route('forum.show', $post)); ?>"
              class="btn btn-sm btn-outline-info rounded-pill px-3 ml-2">
              <i class="fas fa-comment"></i> <?php echo e($post->totalCommentCount()); ?>

            </a>

            
            <button
              class="btn btn-sm btn-outline-secondary rounded-pill px-3 ml-2 share-post-btn"
              data-post-slug="<?php echo e($post->slug); ?>">
              <i class="fas fa-share"></i>
            </button>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  <?php endif; ?>
</div> 


<div class="tab-pane fade" id="my-comments" role="tabpanel">
  <h3><?php echo e($isOwner ? 'My' : $user->name . '\'s'); ?> Comments</h3>
  <?php if($comments->isEmpty()): ?>
    <div class="alert alert-info">No comments yet.</div>
  <?php else: ?>
    <div class="comments-container">
      <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          $isDeleted = $comment->body === '[Deleted Content]' || $comment->body === '[Removed by moderator]';
          
          // Skip deleted comments for non-owners
          if ($isDeleted && !$isOwner) {
            continue;
          }
          
          // Get parent comment info if it's a reply
          $isReply = $comment->parent_id !== null;
          $parentUser = $isReply ? App\Models\Comment::find($comment->parent_id)->user : null;
        ?>
        
        <div class="comment-card mb-3 p-3 border rounded" data-href="<?php echo e(route('forum.show', $comment->post) . '#comment-' . $comment->id); ?>">
          
          <div class="comment-post-title mb-2">
            <a href="<?php echo e(route('forum.show', $comment->post)); ?>" class="font-weight-bold">
              <?php echo e($comment->post->title); ?>

            </a>
          </div>
          
          
          <div class="comment-info d-flex align-items-start mb-1">
            <img src="<?php echo e($user->profile_photo 
                      ? asset('storage/'.$user->profile_photo) 
                      : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&rounded=true'); ?>"
                class="rounded-circle mr-2" style="width:24px; height:24px;">
                
            <div>
              <div class="small">
                <?php if($isReply && $parentUser): ?>
                  <span>replied to <a href="<?php echo e(route('users.show', $parentUser->name)); ?>"><?php echo e($parentUser->name); ?></a></span>
                <?php else: ?>
                  <span>commented</span>
                <?php endif; ?>
                <span class="text-muted date-tooltip" title="<?php echo e($comment->created_at->toDayDateTimeString()); ?>">
                  <?php echo e($comment->created_at->diffForHumans()); ?>

                </span>
              </div>
            </div>
          </div>
          
          
          <div class="comment-body">
            <?php if($isDeleted): ?>
              
              <?php if($isOwner): ?>
                <p class="text-muted">
                  <?php echo e($comment->body === '[Deleted Content]' 
                      ? '[deleted by you]' 
                      : '[removed by moderator]'); ?>

                </p>
              <?php endif; ?>
            <?php else: ?>
              <p class="clamp-3-lines">
                <?php echo $comment->body; ?>

              </p>
            <?php endif; ?>
          </div>
          
          
          <div class="comment-actions d-flex align-items-center mt-2">
            <?php $me = Auth::user(); ?>
            <?php 
  $commentDeleted = $comment->body === '[Deleted Content]' || $comment->body === '[Removed by moderator]';
?>

<?php if($commentDeleted): ?>
  
  <div class="vote-controls d-flex align-items-center mr-2">
    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
      <i class="fas fa-arrow-up"></i>
    </button>
    <div class="mx-2">-</div>
    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
      <i class="fas fa-arrow-down"></i>
    </button>
  </div>
<?php else: ?>
  
  <div class="vote-controls d-flex align-items-center mr-2" data-vote-type="comment">
    <form method="POST"
          action="<?php echo e(route('comments.vote', ['post'=>$comment->post->slug,'comment'=>$comment->slug])); ?>"
          class="vote-form d-inline"
          data-vote-type="comment">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="vote" value="1">
      <button type="button"
              class="btn btn-sm <?php echo e(optional($comment->votes()->where('user_id',Auth::id())->first())->vote == 1
                                ? 'btn-primary' : 'btn-outline-primary'); ?>">
        <i class="fas fa-arrow-up"></i>
      </button>
    </form>

    <div class="vote-count mx-2 <?php echo e(($comment->likesCount() - $comment->dislikesCount()) < 0 ? 'text-danger' : ''); ?>">
      <?php echo e($comment->likesCount() - $comment->dislikesCount()); ?>

    </div>

    <form method="POST"
          action="<?php echo e(route('comments.vote', ['post'=>$comment->post->slug,'comment'=>$comment->slug])); ?>"
          class="vote-form d-inline"
          data-vote-type="comment">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="vote" value="-1">
      <button type="button"
              class="btn btn-sm <?php echo e(optional($comment->votes()->where('user_id',Auth::id())->first())->vote == -1
                                ? 'btn-danger' : 'btn-outline-danger'); ?>">
        <i class="fas fa-arrow-down"></i>
      </button>
    </form>
  </div>
<?php endif; ?>
            
            
            <a href="<?php echo e(route('forum.show', $comment->post) . '#comment-' . $comment->id); ?>"
               class="btn btn-sm rounded-pill px-2 btn-outline-secondary mr-2">
              <i class="fas fa-reply"></i> Reply
            </a>
            
            
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary rounded-pill px-2 share-comment-btn"
              data-comment-id="<?php echo e($comment->id); ?>">
              <i class="fas fa-share"></i>
            </button>
            
            
            <?php if(!$isDeleted && ($me->id === $comment->user_id || $me->is_mod)): ?>
              <div class="dropdown ml-auto">
                <button class="btn btn-sm btn-light p-1 rounded-circle" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                  <?php if($me->id === $comment->user_id): ?>
                    <a href="<?php echo e(route('forum.show', $comment->post) . '#comment-' . $comment->id); ?>" class="dropdown-item p-2">
                      Edit
                    </a>
                  <?php endif; ?>
                  <form method="POST"
                        action="<?php echo e(route('comments.delete', ['post'=>$comment->post->slug,'comment'=>$comment->slug])); ?>"
                        class="dropdown-item p-0 delete-form"
                        data-type="comment">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="button" class="btn btn-link w-100 text-left p-2 delete-btn">
                      Delete
                    </button>
                  </form>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  <?php endif; ?>
</div> 

    
    <?php if($isOwner): ?>
      <div class="tab-pane fade" id="hidden-posts" role="tabpanel">
        <h4>Hidden Posts</h4>
        <?php if($hiddenPosts->isEmpty()): ?>
          <div class="alert alert-info">You haven't hidden any posts.</div>
        <?php else: ?>
          <?php $__currentLoopData = $hiddenPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hidden): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $p = $hidden->post; ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <a href="<?php echo e(route('forum.show', $p)); ?>">
                <?php echo e($p->title); ?>

              </a>
              <form action="<?php echo e(route('forum.unhide', $p)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-outline-secondary">Unhide</button>
              </form>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div> 

  <?php if($isOwner): ?>
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
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
      fd.append('_token','<?php echo e(csrf_token()); ?>');
      fd.append('photo', blob, 'avatar.png');

      fetch("<?php echo e(route('profile.photo.update')); ?>", {
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
    fetch("<?php echo e(route('profile.username.update')); ?>", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
    fetch("<?php echo e(route('profile.about.update')); ?>", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CLSUHub\resources\views/profile/show.blade.php ENDPATH**/ ?>