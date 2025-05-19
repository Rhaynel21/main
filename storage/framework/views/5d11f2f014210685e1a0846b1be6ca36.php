<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-3">

  <!-- Create Post Button -->
  <div class="mb-4 text-right">
    <a href="<?php echo e(route('forum.create')); ?>" class="btn btn-primary">
      Create Post
    </a>
  </div>

  <!-- Forum Listing -->
  <div id="posts-container">
  <?php if($posts->count() > 0): ?>
    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

      
      <?php if($post->body === '[Deleted Content]' && $post->totalCommentCount() === 0): ?>
        <?php continue; ?>
      <?php endif; ?>

      <div class="post-card mb-4" data-href="<?php echo e(route('forum.show', ['post' => $post->slug])); ?>" >
        
        <div class="post-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              
              <img src="<?php echo e($post->user->profile_photo
                            ? asset('storage/'.$post->user->profile_photo)
                            : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&rounded=true'); ?>"
                  class="post-avatar mr-2">

              
              <div class="post-meta">
                <strong>
                    <a href="<?php echo e(route('users.show', $post->user->name)); ?>" class="hover:underline d-inline-flex align-items-center">
                        <?php echo e($post->user->name); ?>

                        <?php echo App\Helpers\BadgeHelper::renderInlineBadges($post->user,32); ?>
                    </a>
                </strong>
                <div class="text-muted small">
                    <span class="date-tooltip"
                          data-iso="<?php echo e($post->created_at->toIso8601String()); ?>">
                      <?php echo e($post->created_at->diffForHumans()); ?>

                    </span>
                    <?php if($post->updated_at->gt($post->created_at)): ?>
                      • <span class="date-tooltip"
                              data-iso="<?php echo e($post->created_at->toIso8601String()); ?>">
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
                  method="POST"
                  action="<?php echo e(route('posts.delete', $post)); ?>"
                  class="dropdown-item p-0 m-0 delete-form"
                  data-type="post"
                >
                  <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                  <button
                    type="button"
                    class="btn btn-link w-100 text-left p-2 text-danger delete-btn"
                  >Delete</button>
                </form>
              <?php endif; ?>

              
              <?php if($me->id !== $post->user_id && $post->body!=='[Deleted Content]'): ?>
                <form class="dropdown-item p-0 m-0" method="POST" action="<?php echo e(route('forum.hide', $post)); ?>">
                  <?php echo csrf_field(); ?>
                  <button class="btn btn-link w-100 text-left p-2 text-secondary">Hide</button>
                </form>
                <form class="dropdown-item p-0 m-0" method="POST" action="<?php echo e(route('forum.report', $post)); ?>">
                  <?php echo csrf_field(); ?>
                  <button class="btn btn-link w-100 text-left p-2 text-warning">Report</button>
                </form>
              <?php endif; ?>

            </div>
          </div>

        </div>

        
        <h4 class="post-title mt-2 mb-1"><?php echo e($post->title); ?></h4>

        
        <?php if($post->body!=='[Deleted Content]'): ?>
          <p class="post-body clamp-6-lines">
            <?php echo $post->body; ?>

          </p>
        <?php else: ?>
          <p class="text-muted">[Deleted Content]</p>
        <?php endif; ?>

        
          <?php if($post->video): ?>
            <div class="post-media-container mt-2">
              <video controls class="post-media">
                <source src="<?php echo e(asset('storage/'.$post->video)); ?>" type="video/mp4">
              </video>
            </div>
          <?php elseif(count($post->images ?? []) > 0): ?>
            <div class="post-media-container mt-2 position-relative" data-image-count="<?php echo e(count($post->images)); ?>">
              <?php $__currentLoopData = $post->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="post-media-slide <?php echo e($index === 0 ? 'active' : ''); ?>">
                  <div class="post-media-bg" style="background-image: url('<?php echo e(asset('storage/'.$img)); ?>');"></div>
                  <img src="<?php echo e(asset('storage/'.$img)); ?>" loading="lazy" class="post-media">
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

              
              <?php if(count($post->images) > 1): ?>
                <div class="slide-counter badge badge-dark position-absolute" style="top: 5px; right: 10px;">
                  <span class="current-slide">1</span> / <?php echo e(count($post->images)); ?>

                </div>

                
                <button class="slide-arrow left">&lsaquo;</button>
                <button class="slide-arrow right">&rsaquo;</button>

                
                <div class="slide-dots">
                  <?php $__currentLoopData = $post->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $_): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="dot <?php echo e($i === 0 ? 'active' : ''); ?>" data-index="<?php echo e($i); ?>"></span>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>


        
        <div class="post-actions d-flex align-items-center mt-3">
                  
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


          
          <a href="<?php echo e(route('forum.show', $post)); ?>"
            class="btn btn-sm btn-outline-info rounded-pill px-3 mr-2">
            <i class="fas fa-comment"></i> <?php echo e($post->totalCommentCount()); ?>

          </a>

          
          <button
            class="btn btn-sm btn-outline-secondary rounded-pill px-3 share-post-btn"
            data-post-slug="<?php echo e($post->slug); ?>">
            <i class="fas fa-share"></i>
          </button>
        </div>
      </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php else: ?>
    <p>No posts available.</p>
  <?php endif; ?>
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
    data-next-page-url="<?php echo e($posts->nextPageUrl()); ?>"
    style="height:1px">
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CLSUHub(05_17_25)\CLSUHub-main\resources\views/forum/forum.blade.php ENDPATH**/ ?>