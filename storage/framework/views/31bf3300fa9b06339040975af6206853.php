<?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo $__env->make('forum._comment', ['comment' => $comment, 'postSlug' => $post->slug], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($hasMorePages): ?>
<div 
    id="comment-load-more" 
    class="comment-loader" 
    data-next-page-url="<?php echo e(route('forum.comments.load', ['post' => $post->slug, 'page' => $nextPage, 'sort_by' => request('sort_by', 'top')])); ?>"
></div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\CLSUHub\resources\views/forum/_comments_chunk.blade.php ENDPATH**/ ?>