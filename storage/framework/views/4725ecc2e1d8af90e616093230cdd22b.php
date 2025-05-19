<!-- Sidebar Toggle Button (only visible on desktop) -->
<button id="sidebarToggleDesktop" class="sidebar-toggle">
  <i class="fas fa-bars"></i>
</button>

<!-- Sidebar Navigation -->
<div id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <img src="<?php echo e(asset('css/uploads/logo1.png')); ?>" alt="CLSUHub Logo" class="sidebar-logo desktop-logo">
    <img src="<?php echo e(asset('css/uploads/logo3.png')); ?>" alt="CLSUHub Logo" class="sidebar-logo mobile-logo">
  </div>
  
  <div class="sidebar-content">
    <!-- Home Category -->
    <div class="sidebar-category">
      <h6 class="sidebar-category-title">Main</h6>
      <ul class="sidebar-nav">
        <li class="sidebar-item">
          <a href="<?php echo e(url('/forum')); ?>" class="sidebar-link <?php echo e(request()->is('forum') ? 'active' : ''); ?>">
            <i class="fas fa-home"></i>
            <span class="sidebar-text">Home</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="<?php echo e(url('/forum/popular')); ?>" class="sidebar-link <?php echo e(request()->is('forum/popular') ? 'active' : ''); ?>">
            <i class="fas fa-fire"></i>
            <span class="sidebar-text">Popular</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="<?php echo e(url('/forum/latest')); ?>" class="sidebar-link <?php echo e(request()->is('forum/latest') ? 'active' : ''); ?>">
            <i class="fas fa-clock"></i>
            <span class="sidebar-text">Latest</span>
          </a>
        </li>
      </ul>
    </div>
    
    <!-- Divider -->
    <div class="sidebar-divider"></div>
    
    <?php if(auth()->guard()->check()): ?>
      <!-- Faculty Category (only visible to faculty users) -->
      <?php if(auth()->user()->role === 'faculty'): ?>
        <div class="sidebar-category">
          <h6 class="sidebar-category-title">Faculty</h6>
          <ul class="sidebar-nav">
            <li class="sidebar-item">
              <a href="<?php echo e(route('faculty.dashboard')); ?>" class="sidebar-link <?php echo e(request()->is('faculty/dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-chart-line"></i>
                <span class="sidebar-text">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="<?php echo e(route('faculty.students')); ?>" class="sidebar-link <?php echo e(request()->is('faculty/students') ? 'active' : ''); ?>">
                <i class="fas fa-user-graduate"></i>
                <span class="sidebar-text">Students</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="<?php echo e(route('faculty.analytics')); ?>" class="sidebar-link <?php echo e(request()->is('faculty/analytics') ? 'active' : ''); ?>">
                <i class="fas fa-chart-bar"></i>
                <span class="sidebar-text">Analytics</span>
              </a>
            </li>
          </ul>
        </div>
        
        <!-- Divider -->
        <div class="sidebar-divider"></div>
      <?php endif; ?>
      
      <!-- User Category -->
      <div class="sidebar-category">
        <h6 class="sidebar-category-title">User</h6>
        <ul class="sidebar-nav">
          <li class="sidebar-item">
            <a href="<?php echo e(url('/profile')); ?>" class="sidebar-link <?php echo e(request()->is('profile') ? 'active' : ''); ?>">
              <i class="fas fa-user"></i>
              <span class="sidebar-text">Profile</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a href="<?php echo e(url('/account')); ?>" class="sidebar-link <?php echo e(request()->is('account') ? 'active' : ''); ?>">
              <i class="fas fa-cog"></i>
              <span class="sidebar-text">Settings</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a href="<?php echo e(route('logout')); ?>" class="sidebar-link text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i>
              <span class="sidebar-text">Logout</span>
            </a>
          </li>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</div><?php /**PATH C:\xampp\htdocs\CLSUHub\resources\views/layouts/sidenav.blade.php ENDPATH**/ ?>