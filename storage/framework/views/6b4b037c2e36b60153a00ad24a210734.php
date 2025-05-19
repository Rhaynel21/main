<?php use Illuminate\Support\Facades\Storage; ?>


<?php $__env->startSection('title','View Post'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-3 view-post-page">
  <?php if($post): ?>
    <?php $me = Auth::user(); ?>

    <div class="post-card mb-4">

      
      <div class="post-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <a href="<?php echo e(url()->previous()); ?>" class="btn btn-circle mr-2">
            <i class="fas fa-arrow-left"></i>
          </a>
          <img src="<?php echo e($post->user->profile_photo
                        ? asset('storage/'.$post->user->profile_photo)
                        : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&rounded=true'); ?>"
               class="post-avatar mr-2">

               <div class="post-meta">
    <?php if($post->body === '[Deleted Content]'): ?>
        <strong>[Deleted User]</strong>
    <?php else: ?>
        <strong>
            <a href="<?php echo e(route('users.show', $post->user->name)); ?>" class="hover:underline d-inline-flex align-items-center">
                <?php echo e($post->user->name); ?>

                <?php echo App\Helpers\BadgeHelper::renderInlineBadges($post->user, 32); ?>
            </a>
        </strong>
    <?php endif; ?>
    <div class="text-muted small">
        <span class="date-tooltip" data-iso="<?php echo e($post->created_at->toIso8601String()); ?>">
            <?php echo e($post->created_at->diffForHumans()); ?>

        </span>
        <?php if($post->updated_at->gt($post->created_at)): ?>
            • <span class="date-tooltip" data-iso="<?php echo e($post->updated_at->toIso8601String()); ?>">
                Edited <?php echo e($post->updated_at->diffForHumans()); ?>

            </span>
        <?php endif; ?>
    </div>
</div>
        </div>

        
        <div class="dropdown">
          <button class="btn btn-sm btn-light p-1 rounded-circle" data-toggle="dropdown">
            <i class="fas fa-ellipsis-h"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right">

            
            <?php if($post->body!=='[Deleted Content]' && $me->id === $post->user_id): ?>
              <form class="dropdown-item p-0 m-0">
                <?php echo csrf_field(); ?>
                <button
                  type="button"
                  class="btn btn-link w-100 text-left p-2 text-primary"
                  onclick="location.href='<?php echo e(route('forum.edit', $post)); ?>'"
                >Edit</button>
              </form>
            <?php endif; ?>

            
            <?php if($me->id === $post->user_id || $me->is_mod): ?>
              <form
                class="dropdown-item p-0 m-0 delete-form"
                method="POST"
                action="<?php echo e(route('posts.delete', $post)); ?>"
                data-type="post"
              >
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button
                  type="button"
                  class="btn btn-link w-100 text-left p-2 text-danger delete-btn"
                >Delete</button>
              </form>
            <?php endif; ?>

            
            <?php if($me->id !== $post->user_id && $post->body !== '[Deleted Content]'): ?>
              <?php if($hasHidden): ?>
                <form class="dropdown-item p-0 m-0" method="POST" action="<?php echo e(route('forum.unhide', $post)); ?>">
                  <?php echo csrf_field(); ?>
                  <button class="btn btn-link w-100 text-left p-2 text-secondary">Unhide Post</button>
                </form>
              <?php else: ?>
                <form class="dropdown-item p-0 m-0" method="POST" action="<?php echo e(route('forum.hide', $post)); ?>">
                  <?php echo csrf_field(); ?>
                  <button class="btn btn-link w-100 text-left p-2 text-secondary">Hide Post</button>
                </form>
              <?php endif; ?>

              <form class="dropdown-item p-0 m-0" method="POST" action="<?php echo e(route('forum.report', $post)); ?>">
                <?php echo csrf_field(); ?>
                <button class="btn btn-link w-100 text-left p-2 text-warning">Report Post</button>
              </form>
            <?php endif; ?>

          </div>
        </div>

      </div>

      
      <h4 class="post-title mt-2 mb-1">
        <?php echo e($post->body === '[Deleted Content]' ? '[Deleted Content]' : $post->title); ?>

      </h4>

      
      <div class="post-body mb-3" style="font-size:1.1rem;">
        <?php echo $post->body; ?>

      </div>

      
      <?php if($post->video): ?>
        <div class="post-media-container mt-2">
          <video controls class="post-media">
            <source src="<?php echo e(asset('storage/'.$post->video)); ?>" type="video/mp4">
          </video>
        </div>
      <?php elseif(count($post->images ?? []) > 0): ?>
        <div class="post-media-container mt-2 position-relative" data-image-count="<?php echo e(count($post->images)); ?>">
          <?php $__currentLoopData = $post->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="post-media-slide <?php echo e($i === 0 ? 'active' : ''); ?>">
              <div class="post-media-bg"
                   style="background-image:url('<?php echo e(asset('storage/'.$img)); ?>')"></div>
              <img src="<?php echo e(asset('storage/'.$img)); ?>"
                   loading="lazy"
                   class="post-media">
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          <?php if(count($post->images) > 1): ?>
            <div class="slide-counter badge badge-dark position-absolute"
                 style="top:5px; right:10px;">
              <span class="current-slide">1</span> / <?php echo e(count($post->images)); ?>

            </div>
            <button class="slide-arrow left">&lsaquo;</button>
            <button class="slide-arrow right">&rsaquo;</button>
            <div class="slide-dots">
              <?php $__currentLoopData = $post->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $_): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="dot <?php echo e($i===0?'active':''); ?>" data-index="<?php echo e($i); ?>"></span>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      

<div class="post-actions d-flex align-items-center mt-3">
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


        <a href="<?php echo e(route('forum.show',$post)); ?>"
           class="btn btn-sm btn-outline-info rounded-pill px-3 mr-2">
          <i class="fas fa-comment"></i> <?php echo e($post->totalCommentCount()); ?>

        </a>

        <button
          id="sharePostBtn"
          class="btn btn-sm btn-outline-secondary rounded-pill px-3 share-post-btn"
          data-post-slug="<?php echo e($post->slug); ?>">
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
        <form id="commentForm" action="<?php echo e(route('posts.comments.store', ['post' => $post->slug])); ?>" method="POST" style="display: none;">
          <?php echo csrf_field(); ?>
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


    <?php
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
    ?>

    <div class="mb-3">
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Sort by: <?php echo e(ucfirst($sort)); ?>

        </button>
        <div class="dropdown-menu" aria-labelledby="sortDropdown">
          <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'most_liked'])); ?>">Most Liked</a>
          <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'most_disliked'])); ?>">Most Disliked</a>
          <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'top'])); ?>">Top</a>
          <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'newest'])); ?>">Newest</a>
          <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'oldest'])); ?>">Oldest</a>
        </div>
      </div>
    </div>



        <h4 style="font-size: 1rem;">Comments</h4>
        <div id="comments-container">
          <?php $__currentLoopData = $visibleDirect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php echo $__env->make('forum._comment', ['comment' => $comment,'postSlug' => $post->slug], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php if($totalDirect > 15): ?>
            <a class="d-block text-primary toggle-hidden-comments" data-target="#moreDirectComments" style="font-size: 0.9rem; cursor: pointer; text-align: center;">Show more comments</a>
            <div class="collapse" id="moreDirectComments">
              <?php $__currentLoopData = $remainingDirect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php echo $__env->make('forum._comment', ['comment' => $comment,'postSlug' => $post->slug], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php endif; ?>
  <?php else: ?>
    <p>No post found.</p>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
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

        fetch("<?php echo e(route('quill.upload')); ?>", {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
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
    const highlightId = <?php echo e($highlightId ?? 'null'); ?>;
    
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CLSUHub(05_17_25)\CLSUHub-main\resources\views/forum/show.blade.php ENDPATH**/ ?>