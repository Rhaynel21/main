@extends('layouts.main')

@section('title','Edit Post')

@section('content')
<div class="container mt-3">
  <div class="card">
    <div class="card-header">Edit Post</div>
    <div class="card-body">
    @error('images')
  <div class="text-danger">{{ $message }}</div>
@enderror
@error('video')
  <div class="text-danger">{{ $message }}</div>
@enderror

      <form action="{{ route('forum.update', $post) }}"
            method="POST"
            enctype="multipart/form-data"
            id="editPostForm">
        @csrf @method('PUT')

        {{-- Title (read-only) --}}
        <div class="form-group mb-3">
          <label>Title</label>
          <input type="text"
                 name="title"
                 class="form-control"
                 value="{{ $post->title }}"
                 readonly>
        </div>

        {{-- Body --}}
        <div class="form-group mb-3">
          <label>Body</label>
          <div id="editor-postBody"
               class="quill-editor"
               style="height: 300px;">
            {!! old('body', $post->body) !!}
          </div>
          <input type="hidden" name="body" id="input-postBody">
        </div>

        {{-- Media Type (hidden) --}}
        <input type="hidden" name="media_type" id="media_type" value="">

        {{-- Media Section --}}
        <div class="form-group mb-3">
          <label>Media</label>
          
          {{-- Existing Media Display --}}
          <div id="existing-media" class="d-flex flex-wrap gap-2 mb-3">
            @if($post->video)
              <div class="media-thumb position-relative border p-1">
                <video src="{{ asset('storage/'.$post->video) }}" 
                       style="width:120px;height:90px;object-fit:cover;"
                       controls></video>
                <button type="button"
                        class="remove-media btn btn-sm btn-danger position-absolute"
                        style="top:0;right:0"
                        data-media-type="video"
                        data-filename="{{ $post->video }}">×</button>
              </div>
            @elseif(is_array($post->images) && count($post->images) > 0)
              @foreach($post->images as $index => $img)
                <div class="media-thumb position-relative border p-1">
                  {{-- Add a debug comment to see what path we're using --}}
                  <!-- Image path: {{ $img }} -->
                  <img src="{{ asset('storage/'.$img) }}"
                      style="width:120px;height:90px;object-fit:cover;">
                  <button type="button"
                          class="remove-media btn btn-sm btn-danger position-absolute"
                          style="top:0;right:0"
                          data-media-type="image"
                          data-filename="{{ $img }}">×</button>
                </div>
              @endforeach
            @endif
          </div>

          {{-- New Media Preview --}}
          <div id="new-media-preview" class="d-flex flex-wrap gap-2 mb-3">
            <!-- New uploads will be previewed here -->
          </div>

          {{-- Add New Media --}}
          <div id="media-controls" class="mb-3">
            <div class="d-flex flex-wrap gap-2">
              <div class="me-3">
                <label for="addImages" class="form-label">Add Images</label>
                <input type="file" 
                       class="form-control" 
                       name="new_images[]" 
                       multiple 
                       accept="image/*" 
                       id="addImages" 
                       {{ $post->video ? 'disabled' : '' }}>
              </div>
              <div>
                <label for="addVideo" class="form-label">Add Video</label>
                <input type="file" 
                       class="form-control" 
                       name="new_video" 
                       accept="video/mp4,video/*" 
                       id="addVideo" 
                       {{ is_array($post->images) && count($post->images) > 0 ? 'disabled' : '' }}>
              </div>
            </div>
            <small class="text-muted">Note: You can add either images OR video, not both</small>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Post</button>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
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

    // Removed media storage array
    const removedMedia = [];

    // Remove existing media thumbnails
    document.querySelectorAll('.remove-media').forEach(btn => {
      btn.addEventListener('click', () => {
        const filename = btn.dataset.filename;
        const mediaType = btn.dataset.mediaType;
        
        // Add to removed media array
        removedMedia.push(filename);
        
        // Create hidden field for removed media
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_media[]';
        input.value = filename;
        document.getElementById('editPostForm').appendChild(input);
        
        // Remove thumbnail from DOM
        btn.closest('.media-thumb').remove();

        // Enable appropriate inputs based on what's left
        updateMediaControls();
      });
    });

    // Function to update media control availability
    function updateMediaControls() {
      const hasExistingVideo = document.querySelector('#existing-media video') !== null;
      const hasExistingImages = document.querySelectorAll('#existing-media img').length > 0;
      const hasNewVideo = document.querySelector('#new-media-preview video') !== null;
      const hasNewImages = document.querySelectorAll('#new-media-preview img').length > 0;
      
      const hasVideo = hasExistingVideo || hasNewVideo;
      const hasImages = hasExistingImages || hasNewImages;
      
      document.getElementById('addImages').disabled = hasVideo;
      document.getElementById('addVideo').disabled = hasImages;
    }

    // On form submit → copy HTML and set media type
    document.getElementById('editPostForm')
  .addEventListener('submit', e => {
    // 1) Determine media_type from actual file inputs:
    const imgsSelected  = document.getElementById('addImages').files.length  > 0;
    const videoSelected = document.getElementById('addVideo').files.length   > 0;

    let mediaType;
    if (videoSelected)      mediaType = 'video';
    else if (imgsSelected)  mediaType = 'images';

    document.getElementById('media_type').value = mediaType;

    // 2) Copy Quill HTML into hidden input:
    document.getElementById('input-postBody').value = quill.root.innerHTML;
  });


    // Preview images when selected
    document.getElementById('addImages').addEventListener('change', e => {
      if (e.target.files.length > 0) {
        // Clear the new media preview first
        document.getElementById('new-media-preview').innerHTML = '';
        
        // Create preview for each selected image
        Array.from(e.target.files).forEach(file => {
          const reader = new FileReader();
          reader.onload = function(event) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'media-thumb position-relative border p-1';
            previewDiv.innerHTML = `
              <img src="${event.target.result}" style="width:120px;height:90px;object-fit:cover;">
              <button type="button" class="remove-new-media btn btn-sm btn-danger position-absolute" 
                      style="top:0;right:0">×</button>
            `;
            document.getElementById('new-media-preview').appendChild(previewDiv);
            
            // Add event listener to remove button
            previewDiv.querySelector('.remove-new-media').addEventListener('click', () => {
              previewDiv.remove();
              // Reset the file input if all previews are removed
              if (document.querySelectorAll('#new-media-preview img').length === 0) {
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
        // Clear the new media preview first
        document.getElementById('new-media-preview').innerHTML = '';
        
        const file = e.target.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
          const previewDiv = document.createElement('div');
          previewDiv.className = 'media-thumb position-relative border p-1';
          previewDiv.innerHTML = `
            <video src="${event.target.result}" style="width:120px;height:90px;object-fit:cover;" controls></video>
            <button type="button" class="remove-new-media btn btn-sm btn-danger position-absolute"
                    style="top:0;right:0">×</button>
          `;
          document.getElementById('new-media-preview').appendChild(previewDiv);
          
          // Add event listener to remove button
          previewDiv.querySelector('.remove-new-media').addEventListener('click', () => {
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
@endsection