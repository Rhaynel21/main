@php
  use Illuminate\Support\Facades\Storage;

  $canChange = isset($form) && (
    !empty($form->optional_group_a) || 
    !empty($form->optional_group_b)
  );
@endphp

@extends('layouts.main')

@section('content')
<div class="container my-5">
  <h1>My Profile</h1>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <!-- 🔗 Nav Tabs -->
  <ul class="nav nav-tabs" id="profileTabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#account" role="tab">Account Info</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#form-responses" role="tab">Form Responses</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#my-posts" role="tab">My Posts</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#my-comments" role="tab">My Comments</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#hidden-posts" role="tab">Hidden Posts</a>
    </li>
  </ul>


  <div class="tab-content mt-4">
    {{-- ACCOUNT INFO --}}
    <div class="tab-pane fade show active" id="account" role="tabpanel">
    @php
      $avatarUrl = $user->profile_photo
        ? asset('storage/' . $user->profile_photo)
        : "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=0D8ABC&color=fff&rounded=true&size=128";
    @endphp
    
      <div class="row">
<div class="box">        
      <div class="col-md-4 text-center">
        <div class="mb-4">
          <img id="mainAvatar"
              src="{{ $avatarUrl }}"
              class="rounded-circle mb-2"
              width="120" height="120">

          <input type="file"
                id="photoInput"
                accept="image/*"
                style="display:none"
                {{ $canChange ? '' : 'disabled' }}>

          <div>
            <button id="pickImageBtn"
                    class="btn btn-sm btn-secondary mt-2"
                    {{ $canChange ? '' : 'disabled' }}>
              Change Photo
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column">
            <h4 class="mb-0"><span id="displayUsername">{{ $user->name }}</span></h4>
            <button id="editUsernameBtn" class="btn btn-sm btn-outline-secondary ml-2">
              <i class="fas fa-pencil-alt"></i>
            </button><br>
            <p class="mb-1">{{ $form->first_name }} {{ $form->middle_name }} {{ $form->last_name }} @if($form->suffix) ({{ $form->suffix }}) @endif</p>
            <p class="mb-1">{{ $user->email }}</p>
            <p><strong>Member since:</strong> {{ $user->created_at->format('F Y') }}</p>
            
          </div>
          <div id="usernameFormContainer" style="display: none;">
            <form id="usernameForm" class="d-flex">
              @csrf
              <input type="text" name="name" id="nameInput" value="{{ $user->name }}" class="form-control mr-2" required>
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="button" id="cancelUsernameEdit" class="btn btn-secondary ml-2">Cancel</button>
            </form>
          </div>
        </div>
        <p><strong>Email:</strong> {{ $user->email }}</p>
</div/
        
        <div class="card mt-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">About Me</h5>
            <button id="editAboutBtn" class="btn btn-sm btn-outline-secondary">
              <i class="fas fa-pencil-alt"></i> Edit
            </button>
          </div>
          <div class="card-body">
            <div id="aboutDisplay">
              <p id="aboutText">{{ $user->about_me ?? 'Tell us about yourself...' }}</p>
            </div>
            <div id="aboutFormContainer" style="display: none;">
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
          </div>
        </div>
      </div>
    </div>
    </div> {{-- /#account --}}

    {{-- FORM RESPONSES --}}
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
              <li class="list-group-item"><strong>Optional Question A:</strong> {{ $form->optional_group_a }}</li>
            @endif
            @if($form->optional_group_b)
              <li class="list-group-item"><strong>Optional Question B:</strong> {{ $form->optional_group_b }}</li>
            @endif
          </ul>
        @else
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
                  <option value="Both">I agree with both statements</option>
                  <option value="Statement1">I agree with statement 1 only</option>
                  <option value="Statement2">I agree with statement 2 only</option>
                  <option value="None">I disagree with both</option>
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
                  <option value="Both">I agree with both statements</option>
                  <option value="Statement3">I agree with statement 3 only</option>
                  <option value="Statement4">I agree with statement 4 only</option>
                  <option value="None">I disagree with both</option>
                </select>
              </div>

              <button class="btn btn-success">Save Optional Answers</button>
            </form>
          @endif
        @endif
    </div> {{-- /#form-responses --}}

    {{-- MY POSTS --}}
    <div class="tab-pane fade" id="my-posts" role="tabpanel">
      <h3>My Posts</h3>
      @if($posts->isEmpty())
        <div class="alert alert-info">No posts yet.</div>
      @else
        <ul class="list-group">
          @foreach($posts as $post)
            <li class="list-group-item">
              <a href="{{ route('forum.show',$post) }}">
                {{ Str::limit($post->title,50) }}
              </a>
              <small class="text-muted float-right">
                {{ $post->created_at->format('M d, Y') }}
              </small>
            </li>
          @endforeach
        </ul>
      @endif
    </div> {{-- /#my-posts --}}

    {{-- MY COMMENTS --}}
    <div class="tab-pane fade" id="my-comments" role="tabpanel">
      <h3>My Comments</h3>
      @if($comments->isEmpty())
        <div class="alert alert-info">No comments yet.</div>
      @else
        <ul class="list-group">
          @foreach($comments as $comment)
            <li class="list-group-item">
              <a href="{{ route('forum.show', $comment->post) . '#comment-' . $comment->id }}">
                {{ Str::limit(strip_tags($comment->body), 80) }}
              </a>
              <small class="text-muted float-right">
                {{ $comment->created_at->diffForHumans() }}
              </small>
            </li>
          @endforeach
        </ul>
      @endif
    </div> {{-- /#my-comments --}}

    <div class="tab-pane fade" id="hidden-posts" role="tabpanel">
      <h4>Hidden Posts</h4>
      @foreach(Auth::user()->hiddenPosts()->with('post')->get() as $hidden)
        @php $p = $hidden->post; @endphp
        <div class="d-flex justify-content-between align-items-center mb-2">
          <a href="{{ route('forum.show',$p) }}">
            {{ $p->title }}
          </a>
          <form action="{{ route('forum.unhide',$p) }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-secondary">Unhide</button>
          </form>
        </div>
      @endforeach
    </div>
  </div> {{-- /.tab-content --}}

 <!-- ▶️ CROPPER MODAL -->
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

@endsection

@section('scripts')
<script>
$(function(){
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