<?php $__env->startSection('title','Create New Post'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-3">
  <div class="card">
    <div class="card-header">Create New Post</div>
    <div class="card-body">
    <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
  <div class="text-danger"><?php echo e($message); ?></div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<?php $__errorArgs = ['video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
  <div class="text-danger"><?php echo e($message); ?></div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <form action="<?php echo e(route('forum.store')); ?>"
            method="POST"
            enctype="multipart/form-data"
            id="createPostForm">
        <?php echo csrf_field(); ?>

        
        <div class="form-group mb-3">
          <label>Title</label>
          <input type="text"
                 name="title"
                 class="form-control"
                 value="<?php echo e(old('title')); ?>"
                 required>
        </div>

        
        <div class="form-group mb-3">
          <label>Body</label>
          <div id="editor-postBody"
               class="quill-editor"
               style="height: 300px;">
            <?php echo old('body'); ?>

          </div>
          <input type="hidden" name="body" id="input-postBody">
        </div>

        
        <input type="hidden" name="media_type" id="media_type" value="">

        
        <div class="form-group mb-3">
          <label>Media</label>
          
          
          <div id="media-preview" class="d-flex flex-wrap gap-2 mb-3">
            <!-- Uploads will be previewed here -->
          </div>

          
          <div id="media-controls" class="mb-3">
            <div class="d-flex flex-wrap gap-2">
              <div class="me-3">
                <label for="addImages" class="form-label">Add Images</label>
                <input type="file" 
                       class="form-control" 
                       name="images[]" 
                       multiple 
                       accept="image/*" 
                       id="addImages">
              </div>
              <div>
                <label for="addVideo" class="form-label">Add Video</label>
                <input type="file" 
                       class="form-control" 
                       name="video" 
                       accept="video/mp4,video/*" 
                       id="addVideo">
              </div>
            </div>
            <small class="text-muted">Note: You can add either images OR video, not both</small>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Post</button>
      </form>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Initialize Quill
    const quill = new Quill('#editor-postBody', {
      theme: 'snow',
      modules: { toolbar: [
        ['bold','italic','underline'],
        ['link'],
        [{ list: 'ordered' }, { list: 'bullet' }]
      ]}
    });

    // On form submit → copy HTML and set media type
    // inside your "submit" listener
document.getElementById('createPostForm').addEventListener('submit', e => {
  // first, read the real file inputs:
  const imgsSelected  = document.getElementById('addImages').files.length  > 0;
  const videoSelected = document.getElementById('addVideo').files.length   > 0;

  if (videoSelected)      mediaType = 'video';
  else if (imgsSelected)  mediaType = 'images';
  else                    mediaType = 'none';

  document.getElementById('media_type').value = mediaType;

  // THEN copy your Quill HTML…
  document.getElementById('input-postBody').value = quill.root.innerHTML;
});


    // Function to update media control availability
    function updateMediaControls() {
      const hasVideo = document.querySelector('#media-preview video') !== null;
      const hasImages = document.querySelectorAll('#media-preview img').length > 0;
      
      document.getElementById('addImages').disabled = hasVideo;
      document.getElementById('addVideo').disabled = hasImages;
    }

    // Preview images when selected
    document.getElementById('addImages').addEventListener('change', e => {
      if (e.target.files.length > 0) {
        // Clear any existing video previews
        const videoElements = document.querySelectorAll('#media-preview .video-preview');
        videoElements.forEach(el => el.remove());
        
        // Create preview for each selected image
        Array.from(e.target.files).forEach(file => {
          const reader = new FileReader();
          reader.onload = function(event) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'media-thumb image-preview position-relative border p-1';
            previewDiv.innerHTML = `
              <img src="${event.target.result}" style="width:120px;height:90px;object-fit:cover;">
              <button type="button" class="remove-media btn btn-sm btn-danger position-absolute" 
                      style="top:0;right:0">×</button>
            `;
            document.getElementById('media-preview').appendChild(previewDiv);
            
            // Add event listener to remove button
            previewDiv.querySelector('.remove-media').addEventListener('click', () => {
              previewDiv.remove();
              
              // If all images are removed, reset the file input
              if (document.querySelectorAll('#media-preview img').length === 0) {
                document.getElementById('addImages').value = '';
              }
              
              updateMediaControls();
            });
          };
          reader.readAsDataURL(file);
        });
        
        updateMediaControls();
      }
    });

    // Preview video when selected
    document.getElementById('addVideo').addEventListener('change', e => {
      if (e.target.files.length > 0) {
        // Clear any existing image previews
        const imageElements = document.querySelectorAll('#media-preview .image-preview');
        imageElements.forEach(el => el.remove());
        
        // Clear any existing video previews
        const videoElements = document.querySelectorAll('#media-preview .video-preview');
        videoElements.forEach(el => el.remove());
        
        const file = e.target.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
          const previewDiv = document.createElement('div');
          previewDiv.className = 'media-thumb video-preview position-relative border p-1';
          previewDiv.innerHTML = `
            <video src="${event.target.result}" style="width:120px;height:90px;object-fit:cover;" controls></video>
            <button type="button" class="remove-media btn btn-sm btn-danger position-absolute"
                    style="top:0;right:0">×</button>
          `;
          document.getElementById('media-preview').appendChild(previewDiv);
          
          // Add event listener to remove button
          previewDiv.querySelector('.remove-media').addEventListener('click', () => {
            previewDiv.remove();
            document.getElementById('addVideo').value = '';
            updateMediaControls();
          });
        };
        reader.readAsDataURL(file);
        
        updateMediaControls();
      }
    });

    // Initial update of media controls
    updateMediaControls();
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CLSUHub(05_17_25)\CLSUHub-main\resources\views/forum/create.blade.php ENDPATH**/ ?>