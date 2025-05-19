<!-- Side Navigation -->
<div id="sidenav" class="sidenav">
  <!-- Overlay that closes the sidenav on click -->
<div id="sidenavOverlay" class="sidenav-overlay"></div>

  <div class="sidenav-header">
    <a href="{{ url('/forum') }}" class="d-flex align-items-center text-decoration-none">
      <img src="{{ asset('css/uploads/logo1.png') }}" alt="CLSUHub Logo" class="sidenav-logo desktop-logo">
      <img src="{{ asset('css/uploads/logo3.png') }}" alt="CLSUHub Logo" class="sidenav-logo mobile-logo">
    </a>
  </div>
  
  <div class="sidenav-content">
    <!-- Home Category -->
    <div class="nav-category">
      <h6 class="nav-category-title">Main</h6>
      <ul class="nav-items">
        <li class="nav-item">
          <a href="{{ url('/forum') }}" class="nav-link {{ request()->is('forum') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span class="nav-link-text">Home</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/profile') }}" class="nav-link {{ request()->is('profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span class="nav-link-text">Profile</span>
          </a>
        </li>
      </ul>
    </div>
    
    <!-- Faculty Category -->
    @auth
      @if(auth()->user()->role === 'faculty')
      <div class="nav-category">
        <h6 class="nav-category-title">Faculty</h6>
        <ul class="nav-items">
          <li class="nav-item">
            <a href="{{ route('faculty.dashboard') }}" class="nav-link {{ request()->is('faculty/dashboard') ? 'active' : '' }}">
              <i class="fas fa-chart-line"></i>
              <span class="nav-link-text">Dashboard</span>
            </a>
          </li>
          <!-- Add more faculty navigation items here -->
        </ul>
      </div>
      @endif
    @endauth
    
    <!-- Settings Category -->
    <div class="nav-category">
      <h6 class="nav-category-title">Settings</h6>
      <ul class="nav-items">
        <li class="nav-item">
          <a href="{{ url('/account') }}" class="nav-link {{ request()->is('account') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="nav-link-text">Account Settings</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link text-danger" 
             onclick="event.preventDefault(); document.getElementById('sidenav-logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span class="nav-link-text">Logout</span>
          </a>
          <form id="sidenav-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>
 
        <!-- Toggle Button -->
        <button id="sidenavToggle" class="sidenav-toggle">
  <i class="fas fa-bars"></i>
</button>