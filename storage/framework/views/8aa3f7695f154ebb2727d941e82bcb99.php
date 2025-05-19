<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CLSUHub - <?php echo $__env->yieldContent('title'); ?></title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- FancyBox CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
  <!-- Cropper.js CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet"/>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
  <!-- Quill -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo e(asset('css/forum.css')); ?>">
  <link href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet"/>
  
  <style>
    /* Header styling */
    .site-header {
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      padding: 12px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    .site-logo {
      height: 80px;
      margin-right: 15px;
      transition: transform 0.2s ease;
    }
    
    .site-logo:hover {
      transform: scale(1.05);
    }
    
    .site-title {
      font-weight: 600;
      font-size: 1.5rem;
      color: #333;
      margin-bottom: 0;
    }
    
    /* Search Bar Styling */
    .form-control.rounded-pill {
      padding-left: 20px;
      padding-right: 40px;
      height: 42px;
      border: 1px solid #e1e5eb;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .form-control.rounded-pill:focus {
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
      border-color: #80bdff;
    }
    
    /* Create Post Button */
    .btn-primary.rounded-pill {
      padding: 8px 20px;
      font-weight: 500;
      box-shadow: 0 2px 5px rgba(0,123,255,0.3);
      transition: all 0.3s ease;
    }
    
    .btn-primary.rounded-pill:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,123,255,0.4);
    }
    
    .nav-item .nav-link {
      color: #555;
      font-weight: 500;
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
    }
    
    .nav-item .nav-link:hover, 
    .nav-item .nav-link.active {
      color: #007bff;
    }
    
    .nav-item .nav-link i {
      margin-right: 5px;
    }
    
    /* Profile dropdown styling */
    .profile-dropdown {
      cursor: pointer;
    }
    
    .profile-dropdown .dropdown-toggle::after {
      display: none;
    }
    
    .profile-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e9ecef;
    }
    
    .user-name {
      font-weight: 600;
      margin: 0 8px;
      max-width: 140px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    
    .dropdown-menu {
      border-radius: 0.5rem;
      border: none;
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
      padding: 0.5rem 0;
    }
    
    .dropdown-item {
      padding: 0.5rem 1.5rem;
      font-weight: 500;
    }
    
    .dropdown-item i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }
    
    .dropdown-divider {
      margin: 0.25rem 0;
    }
    
    /* Main content area */
    #content {
      padding-top: 20px;
      min-height: calc(100vh - 70px);
      transition: margin-left 0.3s ease;
    }
    
    /* Sidebar Styling */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 250px;
      background-color: #fff;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      z-index: 1001;
      transition: transform 0.3s ease, width 0.3s ease;
      overflow-y: auto;
      padding-top: 15px;
    }
    
    .sidebar-collapsed {
      transform: translateX(-220px);
      width: 250px;
    }
    
    .sidebar-header {
      padding: 0 15px 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-bottom: 1px solid #e9ecef;
    }
    
    .sidebar-logo {
      height: 60px;
      transition: transform 0.2s ease;
    }
    
    .sidebar-logo:hover {
      transform: scale(1.05);
    }
    
    .desktop-logo {
      display: block;
    }
    
    .mobile-logo {
      display: none;
      height: 40px;
    }
    
    .sidebar-content {
      padding: 15px;
    }
    
    .sidebar-category {
      margin-bottom: 20px;
    }
    
    .sidebar-category-title {
      color: #6c757d;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
      padding-left: 10px;
    }
    
    .sidebar-nav {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .sidebar-item {
      margin-bottom: 5px;
    }
    
    .sidebar-link {
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 5px;
      color: #495057;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    
    .sidebar-link:hover, 
    .sidebar-link.active {
      background-color: rgba(0, 123, 255, 0.1);
      color: #007bff;
    }
    
    .sidebar-link i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }
    
    .sidebar-text {
      font-size: 0.9rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .sidebar-divider {
      height: 1px;
      background-color: #e9ecef;
      margin: 15px 0;
    }
    
    /* Sidebar Toggle Button */
    .sidebar-toggle {
      position: absolute;
      top: 70px;
      right: -18px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background-color: #007bff;
      border: none;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      z-index: 1002;
      cursor: pointer;
      transition: transform 0.3s ease, right 0.3s ease;
    }
    
    .sidebar-toggle:hover {
      background-color: #0069d9;
    }
    
    .sidebar-toggle i {
      transition: transform 0.3s ease;
    }
    
    .sidebar-collapsed .sidebar-toggle i {
      transform: rotate(180deg);
    }
    
    /* Mobile Toggle Button */
    #sidebarToggleMobile {
      background: transparent;
      border: none;
      color: #333;
      font-size: 1.2rem;
      padding: 0;
      margin-right: 10px;
      display: none;
    }
    
    /* Adjust layout when sidebar is expanded */
    body.sidebar-expanded #content {
      margin-left: 250px;
    }
    
    /* Responsive Styles */
    @media (max-width: 991.98px) {
      .site-logo {
        height: 60px;
      }
      
      .desktop-logo {
        display: none;
      }
      
      .mobile-logo {
        display: block;
      }
      
      #sidebarToggleMobile {
        display: block;
      }
      
      #sidebarToggleDesktop {
        display: none;
      }
      
      .sidebar {
        transform: translateX(-100%);
        width: 250px;
      }
      
      .sidebar.show {
        transform: translateX(0);
      }
      
      body.sidebar-expanded #content {
        margin-left: 0;
      }
      
      .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
        display: none;
      }
      
      .sidebar-overlay.show {
        display: block;
      }
    }
    
    @media (max-width: 767.98px) {
      .col-md-5 {
        order: 3;
        margin-top: 10px;
      }
      
      .site-header .row {
        flex-wrap: wrap;
      }
      
      .col-md-3 {
        flex: 0 0 auto;
        width: auto;
      }
      
      .col-md-4 {
        flex: 0 0 auto;
        width: auto;
        margin-left: auto;
      }
    }
  </style>
</head>
<body class="sidebar-expanded">
  <!-- Sidebar Overlay (for mobile) -->
  <div class="sidebar-overlay"></div>
  
  <!-- Sidebar Navigation -->
  <?php echo $__env->make('layouts.sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  
  <!-- Header Navigation -->
  <header class="site-header">
    <div class="container-fluid">
      <div class="row align-items-center">
        <!-- Mobile Toggle Button and Logo -->
        <div class="col-md-3 d-flex align-items-center">
          <!-- Mobile Toggle Button -->
          <button id="sidebarToggleMobile" class="d-md-none">
            <i class="fas fa-bars"></i>
          </button>
          
          <!-- Logo -->
          <a href="<?php echo e(url('/forum')); ?>" class="d-flex align-items-center text-decoration-none">
            <img src="<?php echo e(asset('css/uploads/logo1.png')); ?>" alt="CLSUHub Logo" class="site-logo d-none d-md-block">
            <img src="<?php echo e(asset('css/uploads/logo3.png')); ?>" alt="CLSUHub Logo" class="site-logo d-md-none">
          </a>
        </div>
        
        <!-- Search Bar -->
        <div class="col-md-5">
          <form action="<?php echo e(route('forum.index')); ?>" method="GET" class="w-100">
            <div class="input-group">
              <input type="text" class="form-control rounded-pill" placeholder="Search Forum..." name="query" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary rounded-pill border-left-0" type="submit" style="margin-left: -40px; z-index: 10; background: transparent;">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
        
        <!-- Profile Dropdown -->
        <div class="col-md-4 d-flex justify-content-end align-items-center">
          <div class="dropdown profile-dropdown">
            <div class="d-flex align-items-center dropdown-toggle" id="profileDropdown" data-toggle="dropdown" aria-expanded="false">
              <img src="<?php echo e(auth()->user()->profile_photo 
                ? asset('storage/'.auth()->user()->profile_photo) 
                : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&rounded=true'); ?>" 
                class="profile-avatar" alt="Profile">
              <span class="user-name d-none d-sm-inline"><?php echo e(auth()->user()->name); ?></span>
              <i class="fas fa-chevron-down ml-1"></i>
            </div>
            
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="<?php echo e(url('/profile')); ?>">
                <i class="fas fa-user"></i> Profile
              </a>
              <a class="dropdown-item" href="<?php echo e(url('/account')); ?>">
                <i class="fas fa-cog"></i> Settings
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-danger" href="<?php echo e(route('logout')); ?>"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
              <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  
  <div id="content">
    <?php echo $__env->yieldContent('content'); ?>
  </div>

  <template id="profile-card-template">
  <div class="profile-card">
    <div class="loading-spinner">
      <div class="spinner"></div>
    </div>
    
    <div class="profile-card-header">
      <img class="profile-card-img" src="" alt="Profile Image">
      <h3 class="profile-card-name"></h3>
      <p class="profile-card-email"></p>
    </div>
    
    <div class="profile-card-stats">
      <div class="stat-item">
        <div class="stat-value posts-count">0</div>
        <div class="stat-label">Posts</div>
      </div>
      <div class="stat-item">
        <div class="stat-value likes-count">0</div>
        <div class="stat-label">Total Likes</div>
      </div>
    </div>
    
    <div class="profile-card-badges">
      <div class="badges-title">Badges</div>
      <div class="badges-container">
        
      </div>
    </div>
  </div>
</template>
  
  <!-- Include jQuery before Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
          integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa9GQJQnN4MFpJQWmvf+XRMp7"
          crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
  <!-- Cropper.js JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

  <script>
    $(document).ready(function() {
      // Sidebar toggle functionality
      $('#sidebarToggleDesktop, #sidebarToggleMobile').on('click', function() {
        if ($(window).width() >= 992) {
          // Desktop behavior
          $('body').toggleClass('sidebar-expanded');
          $('.sidebar').toggleClass('sidebar-collapsed');
        } else {
          // Mobile behavior
          $('.sidebar').toggleClass('show');
          $('.sidebar-overlay').toggleClass('show');
        }
      });
      
      // Close sidebar when clicking overlay (mobile)
      $('.sidebar-overlay').on('click', function() {
        $('.sidebar').removeClass('show');
        $('.sidebar-overlay').removeClass('show');
      });
      
      // Handle window resize
      $(window).resize(function() {
        if ($(window).width() >= 992) {
          // Desktop behavior
          $('.sidebar-overlay').removeClass('show');
          if ($('body').hasClass('sidebar-expanded')) {
            $('.sidebar').removeClass('sidebar-collapsed');
          } else {
            $('.sidebar').addClass('sidebar-collapsed');
          }
        } else {
          // Mobile behavior
          $('body').removeClass('sidebar-expanded');
          $('.sidebar').removeClass('sidebar-collapsed');
          if ($('.sidebar').hasClass('show')) {
            $('.sidebar-overlay').addClass('show');
          }
        }
      });
    });
  </script>

  <?php echo $__env->yieldContent('scripts'); ?>
<script>
  
$(document).ready(function(){

  // Sidebar toggle (the toggle button is positioned in the layout already)
  $('#sidebarToggle').on('click', function(){
    $('#sidebar').toggleClass('collapsed');
  });
  $('#sidebarToggle').off('click').on('click', function(){
      console.log("Sidebar toggle clicked");
      $('#sidebar').toggleClass('collapsed');
    });
  });

  // Format date tooltips for elements with class "date-tooltip"
  document.querySelectorAll('.date-tooltip').forEach(function(el){
    var iso = el.getAttribute('data-iso');
    var dt = new Date(iso);
    var options = { 
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', 
        hour: 'numeric', minute: 'numeric', second: 'numeric', 
        hour12: true, timeZoneName: 'short' 
    };
    var formatter = new Intl.DateTimeFormat(navigator.language, options);
    el.setAttribute('title', formatter.format(dt));
  });

  // Toggle comment details when header is clicked (unless clicking interactive elements)
  $('.comment-header').off('click').on('click', function(e){
    // Do not toggle if clicking inside a button, link, or dropdown.
    if($(e.target).closest('button, a, .dropdown').length === 0){
      // If the comment is marked as deleted (has class "deleted"), do nothing.
      if($(this).closest('.thread-comment').hasClass('deleted')){
        return;
      }
      $(this).next('.comment-details').stop(true, true).slideToggle();
    }
  });

  // Toggle "show more replies" (and remove the link once clicked)
  $('.toggle-hidden-replies').off('click').on('click', function(){
    var target = $(this).data('target');
    $(target).collapse('toggle');
    $(this).remove();
  });

  // Toggle "show more comments" for direct post comments
  $('.toggle-hidden-comments').off('click').on('click', function(){
    var target = $(this).data('target');
    $(target).collapse('toggle');
    $(this).remove();
  });

  // Delete confirmation using SweetAlert2 for delete buttons
  $(document).on('click', '.delete-btn', function(e) {
    e.preventDefault();
    var form = $(this).closest('form');
    var type = form.data('type') || 'item';
    Swal.fire({
      title: 'Are you sure?',
      text: "You cannot restore " + type + "s that have been deleted",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });

// AJAX Voting for posts and comments
$(document).ready(function() {
  // Remove any existing event handlers to prevent duplicates
  $('.vote-form button').off('click');
  $('.vote-form').off('submit');
  
  // Unified click handler for both post and comment voting
  $('.vote-form button').on('click', function() {
    const button = $(this);
    const form = button.closest('.vote-form');
    const voteType = form.data('vote-type'); // "post" or "comment"
    const vote = form.find('input[name="vote"]').val(); // Gets 1 or -1
    const url = form.attr('action') || form.data('vote-url');
    
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content') || form.find('input[name="_token"]').val(),
        vote: vote
      },
      success: function(response) {
        if (voteType === 'comment') {
          // Update comment vote UI
          const container = form.closest('.vote-controls');
          // Update the vote count with the net value
          const netVotes = response.likes - response.dislikes;
          container.find('.vote-count').text(netVotes);
          
          // Apply text-danger class if negative votes
          if (netVotes < 0) {
            container.find('.vote-count').addClass('text-danger');
          } else {
            container.find('.vote-count').removeClass('text-danger');
          }
          
          // Reset all buttons first
          container.find('button').filter(function() { 
            return $(this).find('i.fas.fa-arrow-up').length; 
          }).removeClass('btn-primary').addClass('btn-outline-primary');
          
          container.find('button').filter(function() { 
            return $(this).find('i.fas.fa-arrow-down').length; 
          }).removeClass('btn-danger').addClass('btn-outline-danger');
          
          // Then set the active button
          if (response.user_vote == 1) {
            container.find('button').filter(function() { 
              return $(this).find('i.fas.fa-arrow-up').length; 
            }).removeClass('btn-outline-primary').addClass('btn-primary');
          } else if (response.user_vote == -1) {
            container.find('button').filter(function() { 
              return $(this).find('i.fas.fa-arrow-down').length; 
            }).removeClass('btn-outline-danger').addClass('btn-danger');
          }
        } else {
          // Update post vote UI
          const container = form.closest('.vote-controls');
          
          // Update the vote count with the net value
          const netVotes = response.likes - response.dislikes;
          container.find('.vote-count').text(netVotes);
          
          // Apply text-danger class if negative votes
          if (netVotes < 0) {
            container.find('.vote-count').addClass('text-danger');
          } else {
            container.find('.vote-count').removeClass('text-danger');
          }
          
          // Reset all buttons first
          container.find('button').removeClass('btn-primary btn-outline-primary btn-danger btn-outline-danger');
          
          // Then set the active buttons based on user's vote
          if (response.user_vote == 1) {
            container.find('button').filter(function() { 
              return $(this).find('i.fas.fa-arrow-up').length; 
            }).addClass('btn-primary');
            container.find('button').filter(function() { 
              return $(this).find('i.fas.fa-arrow-down').length; 
            }).addClass('btn-outline-danger');
          } else if (response.user_vote == -1) {
            container.find('button').filter(function() { 
              return $(this).find('i.fas.fa-arrow-up').length; 
            }).addClass('btn-outline-primary');
            container.find('button').filter(function() { 
              return $(this).find('i.fas.fa-arrow-down').length; 
            }).addClass('btn-danger');
          } else {
            container.find('button').filter(function() { 
              return $(this).find('i.fas.fa-arrow-up').length; 
            }).addClass('btn-outline-primary');
            container.find('button').filter(function() { 
              return $(this).find('i.fas.fa-arrow-down').length; 
            }).addClass('btn-outline-danger');
          }
        }
      },
      error: function() {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: 'An error occurred while voting.',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        });
      }
    });
  });
});



$(document).ready(function() {
  console.log('Comment system debugging enabled');
  // Store initialized editors to prevent double initialization
  const initializedEditors = {};
  
  // Function to safely initialize Quill only once per element
  function initQuillEditor(elementId, placeholder) {
    // Check if this editor has already been initialized
    if (initializedEditors[elementId]) {
      return initializedEditors[elementId];
    }
    
    const element = document.getElementById(elementId);
    if (!element) return null;
    
    // Before creating a new instance, destroy any existing Quill instance
    const existingQuill = element.__quill;
    if (existingQuill) {
      existingQuill.off();
      // Remove the previous toolbar and editor content
      const parent = element.parentNode;
      const toolbars = parent.querySelectorAll('.ql-toolbar');
      toolbars.forEach(toolbar => toolbar.remove());
      
      // Reset the editor content container
      while (element.firstChild) {
        element.removeChild(element.firstChild);
      }
    }
    
    // Create a new Quill instance
    const quill = new Quill(element, {
      theme: 'snow',
      placeholder: placeholder || 'Type your content here...',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          ['link'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }]
        ]
      }
    });
    
    // Store the reference to avoid re-initialization
    initializedEditors[elementId] = quill;
    return quill;
  }
  
  // Initialize reply editors when reply button is clicked
  $('.reply-btn').on('click', function() {
    const commentId = $(this).closest('.media').data('comment-id');
    const editorId = 'editor-reply-' + commentId;
    
    // Use setTimeout to ensure the editor container is visible when initializing
    setTimeout(() => {
      initQuillEditor(editorId, 'Type your reply here...');
    }, 50);
  });
  
  // Initialize edit editors when edit button is clicked
  $('.edit-btn').on('click', function() {
    const commentId = $(this).closest('.media').data('comment-id');
    const editorId = 'editor-edit-' + commentId;
    
    // Use setTimeout to ensure the editor container is visible when initializing
    setTimeout(() => {
      const editor = initQuillEditor(editorId, 'Edit your comment...');
      
      // Fix: Set the editor content from the hidden input or from the comment body
      if (editor) {
        // Get content from the visible comment body
        const commentBody = $(this).closest('.media').find('.comment-body').html().trim();
        editor.clipboard.dangerouslyPasteHTML(commentBody);
        
        // Also update the hidden input
        $('#input-edit-' + commentId).val(commentBody);
      }
    }, 50);
  });
  // When any edit‐form is submitted, grab its Quill contents…
$(document).on('submit', '.edit-form', function(e) {
  // find the comment ID
  const commentId = $(this).closest('.media').data('comment-id');
  // grab the Quill instance we stored earlier
  const quill = initializedEditors['editor-edit-' + commentId];
  if (quill) {
    // copy its HTML output into the hidden input
    const html = quill.root.innerHTML;
    $('#input-edit-' + commentId).val(html);
  }
  // allow the form to continue submitting…
});

  
  // Handle reply form submission
  $('.reply-form').submit(function(e) {
    e.preventDefault();
    
    const form = $(this);
    const commentId = form.data('comment-id');
    const editorId = 'editor-reply-' + commentId;
    const quillEditor = initializedEditors[editorId];
    
    // Get content from Quill and put it in the hidden input
    if (quillEditor) {
      const content = quillEditor.root.innerHTML.trim();
      $('#input-reply-' + commentId).val(content);
    }
    
    $.ajax({
      url: form.attr('action'),
      type: 'POST',
      data: form.serialize(),
      success: function(response) {
        // Close the reply form
        $('#replyForm' + commentId).collapse('hide');
        
        // Clear the editor
        if (quillEditor) {
          quillEditor.setText('');
        }
        
        // Show success message
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Reply posted successfully!',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        });
        
        // Reload page to show the new reply
        window.location.reload();
      },
      error: function(xhr) {
        let errorMessage = 'An error occurred while posting your reply.';
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: errorMessage,
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        });
      }
    });
  });
  
  
  
  // Reset editor when cancel button is clicked
  $('.cancel-reply-btn').on('click', function() {
    const commentId = $(this).closest('.reply-form').data('comment-id');
    const editorId = 'editor-reply-' + commentId;
    const quillEditor = initializedEditors[editorId];
    
    if (quillEditor) {
      quillEditor.setText('');
    }
  });

  $('.cancel-edit-btn').on('click', function() {
    const commentId = $(this).closest('form').parent().attr('id').replace('editForm', '');
    const editorId = 'editor-edit-' + commentId;
    const quillEditor = initializedEditors[editorId];
    
    if (quillEditor) {
      // Reset to original content
      const commentBody = $('#comment-' + commentId).find('.comment-body').html().trim();
      quillEditor.clipboard.dangerouslyPasteHTML(commentBody);
    }
  });
});

  // Share button for post (assuming it's separate)
  $('#sharePostBtn').on('click', function() {
    var dummy = document.createElement('input');
    var text = window.location.href;
    document.body.appendChild(dummy);
    dummy.value = text;
    dummy.select();
    document.execCommand('copy');
    document.body.removeChild(dummy);
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: 'Post link copied to clipboard!',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  });
  $(function(){

  offAndOn('#sharePostBtn click', () => {
    const postSlug = $('#sharePostBtn').data('post-slug');
    const url      = `${location.origin}/forum/${postSlug}`;
    copyToClipboard(url, 'Post link copied to clipboard!');
  });

  offAndOn('.share-post-btn click', function(e){
    e.stopPropagation();
    const postSlug = $(this).data('post-slug');
    const url      = `${location.origin}/forum/${postSlug}`;
    copyToClipboard(url, 'Post link copied to clipboard!');
  });

  offAndOn('.share-comment-btn click', function(e){
  e.stopPropagation();

  const postSlug = location.pathname.split('/')[2];       // "forum/{postSlug}…"
  const commentSlug = $(this).data('comment-slug');       // must add data-attr!
  
  // Remove the hash fragment, just use the post and comment slugs
  const url = `${location.origin}/forum/${postSlug}/${commentSlug}`;

  copyToClipboard(url, 'Comment link copied to clipboard!');
});

  function offAndOn(eventSpec, handler){
    const [selector, evt] = eventSpec.split(' ');
    $(selector).off(evt).on(evt, handler);
  }

  function copyToClipboard(text, msg){
    if(navigator.clipboard && window.isSecureContext){
      navigator.clipboard.writeText(text).then(() => toast(msg));
    } else {
      const ta = $('<textarea>').text(text).appendTo('body').select();
      document.execCommand('copy');
      ta.remove();
      toast(msg);
    }
  }

  function toast(message){
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: message,
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true
    });
  }

  });

    

  
</script>
<script>
$(document).ready(function(){
  // Find all <img> elements within #postContent that are not already wrapped in an anchor with data-fancybox
  $('#postContent img').each(function(){
    if($(this).parent('a[data-fancybox]').length === 0){
      var imgSrc = $(this).attr('src');
      // Wrap the image in an anchor tag for FancyBox
      $(this).wrap('<a data-fancybox="post-gallery" href="'+imgSrc+'"></a>');
    }
  });
});
</script>
<!-- Forum scripts -->
<script>
  // 1) Fancybox
  Fancybox.bind("[data-fancybox]", {});
$(function(){
  // only apply on pages that are NOT the show view:
  if (!$('.view-post-page').length) {
    $('.post-card').on('click', function(e){
      if (!$(e.target).closest('button, a, .dropdown-menu, .post-media-container').length) {
        window.location = $(this).data('href');
      }
    });
  }
});


$(function(){
  // For each carousel...
  $('.post-media-container').each(function(){
  const C = $(this);
  const slides   = C.find('.post-media-slide');
  const dots     = C.find('.dot');
  const counter  = C.find('.current-slide');
  let current    = 0;
  const total    = slides.length;
  const leftArrow  = C.find('.slide-arrow.left');
  const rightArrow = C.find('.slide-arrow.right');

  function showSlide(i){
    // clear old classes
    slides.removeClass('active prev');
    // only mark the _previous_ slide if we’re actually moving
    if (i !== current) {
      slides.eq(current).addClass('prev');
    }
    // activate the new one
    slides.eq(i).addClass('active');

    // update dots & counter
    dots.removeClass('active').eq(i).addClass('active');
    counter.text(i + 1);

    // hide arrows at the ends
    leftArrow.toggleClass('disabled',  i === 0);
    rightArrow.toggleClass('disabled', i === total - 1);

    current = i;
  }

  // arrow clicks
  leftArrow.on('click', e => {
    e.stopPropagation();
    if (current > 0) showSlide(current - 1);
  });
  rightArrow.on('click', e => {
    e.stopPropagation();
    if (current < total - 1) showSlide(current + 1);
  });

  // dot clicks
  dots.on('click', function(e){
    e.stopPropagation();
    showSlide(+$(this).data('index'));
  });

  // swipe gestures (but _don’t_ intercept touches on the video itself)
  let startX = 0, endX = 0;
  C.on('touchstart', e => {
    if ($(e.target).closest('video').length) return;
    startX = e.originalEvent.touches[0].clientX;
    C.addClass('no-transition');
  });
  C.on('touchmove', e => {
    if ($(e.target).closest('video').length) return;
    endX = e.originalEvent.touches[0].clientX;
    slides.eq(current).css('transform', `translateX(${endX - startX}px)`);
  });
  C.on('touchend', e => {
    if ($(e.target).closest('video').length) return;
    C.removeClass('no-transition');
    const diff = endX - startX;
    slides.css('transform','');  // reset
    if (Math.abs(diff) > 50) {
      if (diff > 0 && current > 0)       showSlide(current - 1);
      else if (diff < 0 && current < total - 1) showSlide(current + 1);
    }
  });

  // allow native video scrubbing by _not_ overriding click
  C.find('video.post-media').off('click');

  // zoom gallery for images
  C.find('img.post-media').off('click').on('click', function(e){
    e.stopPropagation();
    const gallery = slides.map((_, slide) => ({
      src: $(slide).find('img').attr('src'),
      type: 'image'
    })).get();
    Fancybox.show(gallery, { startIndex: current });
  });

  // initialize properly
  showSlide(0);
});

});
</script>
<!-- Profile Hovercards -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Cache DOM elements
  const hoverTriggers = document.querySelectorAll('.profile-card-trigger');
  const cardTemplate = document.getElementById('profile-card-template');
  
  // Cache to store already fetched profile data
  const profileCache = {};
  
  // Delay timing for hover
  const showDelay = 300;
  const hideDelay = 400;
  let showTimer, hideTimer;
  let activeCard = null;
  
  // Attach event listeners to all profile hover triggers
  hoverTriggers.forEach(trigger => {
    let currentCard = null;
    
    trigger.addEventListener('mouseenter', () => {
      clearTimeout(hideTimer);
      
      // Show profile card after a short delay
      showTimer = setTimeout(() => {
        const username = trigger.dataset.username;
        
        // Don't show cards for deleted users
        if (username === undefined || trigger.textContent.trim() === '[Deleted User]') {
          return;
        }
        
        // Create the card if it doesn't exist
        if (!currentCard) {
          currentCard = createProfileCard(trigger);
          document.body.appendChild(currentCard);
          positionCard(currentCard, trigger);
        }
        
        // Show the card with loading state
        currentCard.classList.add('visible', 'loading');
        activeCard = currentCard;
        
        // Check if data is already cached
        if (profileCache[username]) {
          populateProfileCard(currentCard, profileCache[username]);
          currentCard.classList.remove('loading');
        } else {
          // Fetch user data from API
          fetchUserProfile(username)
            .then(userData => {
              profileCache[username] = userData;
              if (currentCard) {
                populateProfileCard(currentCard, userData);
                currentCard.classList.remove('loading');
              }
            })
            .catch(error => {
              console.error('Error fetching user profile:', error);
              if (currentCard) {
                currentCard.classList.remove('loading');
              }
            });
        }
      }, showDelay);
    });
    
    trigger.addEventListener('mouseleave', () => {
      clearTimeout(showTimer);
      
      // Hide profile card after a short delay
      if (currentCard) {
        hideTimer = setTimeout(() => {
          currentCard.classList.remove('visible');
          setTimeout(() => {
            if (currentCard && currentCard.parentNode) {
              document.body.removeChild(currentCard);
              currentCard = null;
              activeCard = null;
            }
          }, 300); // Wait for fade animation
        }, hideDelay);
      }
    });
  });
  
  // Create a new profile card from template
  function createProfileCard(trigger) {
    const card = cardTemplate.content.cloneNode(true).querySelector('.profile-card');
    
    // Stop propagation of mouseenter/leave events on the card itself
    card.addEventListener('mouseenter', () => {
      clearTimeout(hideTimer);
    });
    
    card.addEventListener('mouseleave', () => {
      hideTimer = setTimeout(() => {
        card.classList.remove('visible');
        setTimeout(() => {
          if (card && card.parentNode) {
            document.body.removeChild(card);
            if (activeCard === card) {
              activeCard = null;
            }
          }
        }, 300);
      }, hideDelay);
    });
    
    return card;
  }
  
  // Position the card relative to the trigger
  function positionCard(card, trigger) {
    const triggerRect = trigger.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
    
    // Position the card below the trigger
    card.style.top = (triggerRect.bottom + scrollTop + 5) + 'px';
    
    // Check if card would go off screen to the right
    const cardWidth = 280; // Width of the card
    if (triggerRect.left + cardWidth > window.innerWidth) {
      // Align to the right edge of the trigger
      card.style.left = (triggerRect.right - cardWidth + scrollLeft) + 'px';
    } else {
      // Align to the left edge of the trigger
      card.style.left = (triggerRect.left + scrollLeft) + 'px';
    }
  }
  
  // Populate the profile card with data
  function populateProfileCard(card, userData) {
    // Set profile image
    const profileImg = card.querySelector('.profile-card-img');
    profileImg.src = userData.avatar_url;
    profileImg.alt = userData.display_name;
    
    // Set name and email
    card.querySelector('.profile-card-name').textContent = userData.display_name;
    card.querySelector('.profile-card-email').textContent = userData.email;
    
    // Set stats
    card.querySelector('.posts-count').textContent = userData.posts_count;
    card.querySelector('.likes-count').textContent = userData.total_likes;
    
    // Add badges
    const badgesContainer = card.querySelector('.badges-container');
    badgesContainer.innerHTML = userData.badges_html;
  }
  
  // Fetch user profile data
  async function fetchUserProfile(username) {
    const response = await fetch(`/api/users/${username}/profile-card`);
    
    if (!response.ok) {
      throw new Error('Failed to fetch user profile');
    }
    
    return await response.json();
  }
});
</script>
<script>
  // Sidenav Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
  const sidenav = document.getElementById('sidenav');
  const sidenavToggle = document.getElementById('sidenavToggle');
  const sidenavOverlay = document.getElementById('sidenavOverlay');
  const content = document.getElementById('content');
  const body = document.body;
  
  // Functions to open and close the sidenav
  function openSidenav() {
    sidenav.classList.add('open');
    sidenavToggle.classList.add('open');
    sidenavOverlay.classList.add('open');
    body.classList.add('sidenav-open');
    
    // Update toggle button icon
    const icon = sidenavToggle.querySelector('i');
    icon.classList.remove('fa-bars');
    icon.classList.add('fa-chevron-left');
  }
  
  function closeSidenav() {
    sidenav.classList.remove('open');
    sidenavToggle.classList.remove('open');
    sidenavOverlay.classList.remove('open');
    body.classList.remove('sidenav-open');
    
    // Update toggle button icon
    const icon = sidenavToggle.querySelector('i');
    icon.classList.remove('fa-chevron-left');
    icon.classList.add('fa-bars');
  }
  
  // Toggle sidenav on button click
  sidenavToggle.addEventListener('click', function() {
    if (sidenav.classList.contains('open')) {
      closeSidenav();
    } else {
      openSidenav();
    }
  });
  
  // Close sidenav when clicking outside
  sidenavOverlay.addEventListener('click', closeSidenav);
  
  // Close sidenav on window resize in mobile view
  window.addEventListener('resize', function() {
    if (window.innerWidth <= 768 && sidenav.classList.contains('open')) {
      closeSidenav();
    }
  });
  
  // Handle logo switch for mobile
  function updateLogoForMobile() {
    const regularLogo = document.querySelector('.site-logo');
    const mobileLogo = document.querySelector('.mobile-logo');
    
    if (window.innerWidth <= 768) {
      // If there's no mobile logo yet, create one
      if (!mobileLogo) {
        const newMobileLogo = document.createElement('img');
        newMobileLogo.src = regularLogo.src.replace('logo1.png', 'logo3.png');
        newMobileLogo.alt = 'CLSUHub Logo Mobile';
        newMobileLogo.className = 'mobile-logo';
        regularLogo.parentNode.insertBefore(newMobileLogo, regularLogo.nextSibling);
      }
    }
  }
  
  // Initialize mobile logo
  updateLogoForMobile();
  
  // Update on resize
  window.addEventListener('resize', updateLogoForMobile);
});
  </script>

<?php if(session('swal_error')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: <?php echo json_encode(session('swal_error')); ?>,
      });
    });
  </script>
<?php endif; ?>
    <?php if(session('success')): ?>
      <script>
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: '<?php echo e(session("success")); ?>',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        });
      </script>
    <?php endif; ?>

    <?php if(session('popup')): ?>
    <script>
      Swal.fire({
        icon: 'info',
        title: <?php echo json_encode(session('popup'), 15, 512) ?>,
        confirmButtonText: 'OK'
      });
    </script>
  <?php endif; ?>
  <?php if(session('status')): ?>
  <script>
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: <?php echo json_encode(session('status'), 15, 512) ?>,
      showConfirmButton: false,
      timer: 3000
    });
  </script>
<?php endif; ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\CLSUHub\resources\views/layouts/main.blade.php ENDPATH**/ ?>