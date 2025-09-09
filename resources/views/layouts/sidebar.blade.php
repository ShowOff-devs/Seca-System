<style>
/* Show full logo, hide icon by default */
.sidebar .brand-logo { display: block; }
.sidebar .brand-logo-mini { display: none; }

/* When collapsed, hide full logo, show icon */
.sidebar.collapsed .brand-logo { display: none !important; }
.sidebar.collapsed .brand-logo-mini { display: block !important; }
</style>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <!-- Logo Section with Toggle -->
  <div class="sidebar-brand-wrapper d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo sidebar-toggle" href="javascript:void(0);">
      <img src="{{ asset('assets/images/secawhite.png') }}" alt="logo" class="sidebar-logo">
    </a>
    <a class="sidebar-brand brand-logo-mini sidebar-toggle" href="javascript:void(0);">
      <img src="{{ asset('assets/images/fav.png') }}" alt="icon" class="sidebar-logo-mini">
    </a>
  </div>

  <!-- Navigation Section -->
  <ul class="nav">
    <!-- Profile Section -->
    @if(Auth::check()) <!-- Prevents errors if user is not logged in -->
      <li class="nav-item profile">
        <div class="profile-desc">
          <div class="profile-pic">
            <div class="count-indicator">
              <img class="img-xs rounded-circle profile-image" src="{{ asset('assets/images/faces/face15.jpg') }}" alt="">
              <span class="count bg-success"></span>
            </div>
            <div class="profile-name">
              <h5 class="mb-0 font-weight-normal profile-name-text" style="font-size: 1rem !important;">{{ Auth::user()->name }}</h5>
              <span class="profile-role">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
          </div>
        </div>
      </li>
    @endif

    <!-- Navigation Category -->
    <li class="nav-item nav-category">
      <span class="nav-link">Navigation</span>
    </li>

    <!-- Dynamic Menu Items -->
    @foreach($menuItems as $menuItem)
      @if(in_array($userRole, $menuItem['roles']))
        <li class="nav-item menu-items">
          <a class="nav-link" href="{{ $menuItem['route'] }}">
            <span class="menu-icon">
              <i class="{{ $menuItem['icon'] }}"></i>
            </span>
            <span class="menu-title">{{ $menuItem['name'] }}</span>
          </a>
        </li>
      @endif
    @endforeach

    

  </ul>
</nav>

<!-- Sidebar Toggle Script -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const sidebarToggle = document.querySelectorAll(".sidebar-toggle");

    // Restore sidebar state from localStorage
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
      sidebar.classList.add('collapsed');
    }

    sidebarToggle.forEach(button => {
      button.addEventListener("click", function () {
        sidebar.classList.toggle("collapsed");
        // Save state
        localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
      });
    });
  });
</script>
