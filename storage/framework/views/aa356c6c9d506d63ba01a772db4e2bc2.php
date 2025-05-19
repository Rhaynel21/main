<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['comment','level'=>0,'postSlug']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['comment','level'=>0,'postSlug']); ?>
<?php foreach (array_filter((['comment','level'=>0,'postSlug']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<?php
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
?>


<div class="media thread-comment" data-comment-id="<?php echo e($comment->id); ?>" id="comment-<?php echo e($comment->id); ?>" data-comment-slug="<?php echo e($comment->slug); ?>">
<?php
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
?>

<img src="<?php echo e($avatarUrl); ?>"
     class="mr-2 rounded-circle profile-card-trigger"
     alt="<?php echo e($displayName); ?>"
     data-username="<?php echo e($isDeleted ? '' : $comment->user->name); ?>"
     style="width:30px; height:30px;">

        <div class="media-body">
          <!-- Comment Header -->
          <div class="comment-header" style="cursor: pointer;">
            <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0" style="font-size: 0.85rem;">
            <?php if($isDeleted): ?>
    [Deleted User]
<?php else: ?>
    <a href="<?php echo e(route('users.show', $comment->user->name)); ?>" 
       class="hover:underline d-inline-flex align-items-center profile-card-trigger"
       data-username="<?php echo e($comment->user->name); ?>">
        <?php echo e($displayName); ?>

        <?php echo App\Helpers\BadgeHelper::renderInlineBadges($comment->user, 32); ?>
    </a>
<?php endif; ?>
          <small class="text-muted">
              • <span class="date-tooltip" data-iso="<?php echo e($comment->created_at->toIso8601String()); ?>">
                  <?php echo e($comment->created_at->diffForHumans()); ?>

              </span>
              <?php if($comment->updated_at != $comment->created_at): ?>
                  • <span class="date-tooltip" data-iso="<?php echo e($comment->updated_at->toIso8601String()); ?>">
                      Edited <?php echo e($comment->updated_at->diffForHumans()); ?>

                  </span>
              <?php endif; ?>
          </small>
        </h6>
      </div>
    </div>
    <!-- Comment Details -->
    <div class="comment-details" style="display: <?php echo e($initialDisplay); ?>;">
  <div class="comment-body">
    <?php if($isDeleted): ?>
      
      <?php echo e($comment->body === '[Deleted Content]' 
          ? '[deleted by user]' 
          : '[removed by moderator]'); ?>

    <?php else: ?>
      <?php echo $comment->body; ?>

    <?php endif; ?>
  </div>

  <div class="comment-actions d-flex align-items-center mt-1" style="font-size:0.75rem;">
  <?php $me = Auth::user(); ?>

  <?php if($isDeleted): ?>
  
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

  <?php if (! ($isDeleted)): ?>
    
    <button class="btn btn-sm rounded-pill px-3 btn-outline-secondary mr-2 reply-btn"
            data-toggle="collapse"
            data-target="#replyForm<?php echo e($comment->id); ?>">
      <i class="fas fa-reply"></i> Reply
    </button>
  <?php endif; ?>

  
  <button
  type="button"
  class="btn btn-sm btn-outline-secondary rounded-pill px-3 share-comment-btn"
  data-comment-id="<?php echo e($comment->id); ?>"
  data-comment-slug="<?php echo e($comment->slug); ?>">
  <i class="fas fa-share"></i> Share
</button>


  <?php if(!$isDeleted && ($me->id === $comment->user_id || $me->is_mod)): ?>
    
    <div class="dropdown">
      <button class="btn btn-sm btn-light p-1 rounded-circle" data-toggle="dropdown">
        <i class="fas fa-ellipsis-h"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-right">
      <?php if($me->id === $comment->user_id): ?>
      <form class="dropdown-item p-0 m-0">
        <?php echo csrf_field(); ?>
        <button type="button" class="btn btn-link w-100 text-left p-2 edit-btn text-primary" data-toggle="collapse" data-target="#editForm<?php echo e($comment->id); ?>">
          Edit
        </button>
      </form>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('comments.delete', ['post'=>$postSlug,'comment'=>$comment->slug])); ?>" class="dropdown-item p-0 m-0 delete-form" data-type="comment">
      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
      <button
        type="button"
        class="btn btn-link w-100 text-left p-2 text-danger delete-btn"
      >
        Delete
      </button>
    </form>

      </div>
    </div>
  <?php endif; ?>


</div>

     <!-- Reply Form (AJAX submission) -->
<div class="collapse mt-1" id="replyForm<?php echo e($comment->id); ?>">
  <form action="<?php echo e(route('comments.reply.store', ['post' => $postSlug, 'comment' => $comment->slug])); ?>" 
        method="POST" 
        class="reply-form" 
        data-comment-id="<?php echo e($comment->id); ?>">
    <?php echo csrf_field(); ?>
    <div class="form-group mb-1">
      <div id="editor-reply-<?php echo e($comment->id); ?>" class="quill-editor" style="height: 100px;"></div>
      <input type="hidden" name="body" id="input-reply-<?php echo e($comment->id); ?>">
    </div>

    <div class="d-flex justify-content-between">
      <button type="button" class="btn btn-light btn-sm cancel-reply-btn" data-toggle="collapse" data-target="#replyForm<?php echo e($comment->id); ?>">
        Cancel
      </button>
      <button type="submit" class="btn btn-secondary btn-sm submit-reply-btn">Submit Reply</button>
    </div>
  </form>
</div>

      <!-- Edit Form -->
      <div class="collapse mt-1" id="editForm<?php echo e($comment->id); ?>">
      <form class="edit-form"  action="<?php echo e(route('comments.update', $comment->slug)); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>
          <div class="form-group mb-1">
            
            <div id="editor-edit-<?php echo e($comment->id); ?>"
                class="quill-editor"
                style="height: 100px;">
              <?php echo $comment->body; ?>

            </div>
            <input type="hidden" name="body" id="input-edit-<?php echo e($comment->id); ?>">
          </div>
          <div class="text-right">
          <button type="button" class="btn btn-light btn-sm cancel-edit-btn" data-toggle="collapse" data-target="#editForm<?php echo e($comment->id); ?>">
        Cancel
      </button>
            <button type="submit" class="btn btn-secondary btn-sm">Update Comment</button>
          </div>
        </form>
      </div>
      
      <!-- Replies Section with Chunking -->
      <?php
          $allReplies = $comment->replies;
          $totalReplies = $allReplies->count();
      ?>
      <?php if($totalReplies > 0): ?>
        <div class="thread-line ml-3 pl-2">
          <?php if($totalReplies <= $maxVisible): ?>
            <?php $__currentLoopData = $allReplies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php echo $__env->make('forum._comment', ['comment' => $reply, 'level' => $level + 1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
            <?php $__currentLoopData = $allReplies->take($maxVisible); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php echo $__env->make('forum._comment', ['comment' => $reply, 'level' => $level + 1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php
                $remainingReplies = $allReplies->slice($maxVisible);
            ?>
            <?php $__currentLoopData = $remainingReplies->chunk(20); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunkIndex => $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="d-block text-primary toggle-hidden-replies" data-target="#moreReplies<?php echo e($comment->id); ?>_chunk<?php echo e($chunkIndex); ?>" style="font-size: 0.75rem; cursor: pointer;">show more replies</a>
                <div class="collapse" id="moreReplies<?php echo e($comment->id); ?>_chunk<?php echo e($chunkIndex); ?>">
                  <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('forum._comment', ['comment' => $reply, 'level' => $level + 1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php /**PATH C:\xampp\htdocs\CLSUHub\resources\views/forum/_comment.blade.php ENDPATH**/ ?>