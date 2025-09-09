<nav class="navbar p-0 fixed-top d-flex flex-row">
  <!-- Logo Section -->
  <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
    <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
      <img src="{{ asset('assets/images/fav.png') }}" alt="logo" />
    </a>
  </div>

  <!-- Navbar Menu Wrapper -->
  <div class="navbar-menu-wrapper flex-grow d-flex align-items-center justify-content-between">
    <!-- Search Bar -->
    {{-- <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
      <input type="text" class="form-control" placeholder="Search Assets">
    </form> --}}

    <!-- Right Section -->
    <ul class="navbar-nav navbar-nav-right">
      <!-- Add New Button with Dropdown -->
      <li class="nav-item dropdown">
        <a class="btn btn-add-new nav-link dropdown-toggle" href="#" id="addNewDropdown" data-toggle="dropdown" aria-expanded="false">
          + Add New
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="addNewDropdown">
          <h6 class="dropdown-header">Select</h6>
          <a class="dropdown-item d-flex align-items-center" href="{{ route('inventory.index') }}">
            <i class="mdi mdi-package-variant dropdown-icon"></i>
            <span class="ml-2">Add Assets</span>
          </a>
          <a class="dropdown-item d-flex align-items-center" href="{{ route('laboratories.index') }}">
            <i class="mdi mdi-office-building dropdown-icon"></i>
            <span class="ml-2">Add Laboratory</span>
          </a>
        </div>
      </li>

      <!-- Icons Section -->
      {{-- <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="mdi mdi-view-grid"></i>
        </a>
      </li> --}}
      <li class="nav-item dropdown">
        @php
            $notifications = Auth::user()->unreadNotifications->take(5);
        @endphp
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
          <i class="mdi mdi-bell"></i>
          @if($notifications->count())
            <span class="count bg-danger">{{ $notifications->count() }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list">
          <h6 class="p-3 mb-0">Notifications</h6>
          @php
            $movements = App\Models\RfidLog::orderBy('id', 'DESC')->limit(5)->get();

          @endphp
          @foreach ($movements as $movement)
          @php
            $asset = App\Models\Asset::where('rfid_no', $movement->rfid_no)->first();
          @endphp
            <div class="dropdown-divider"></div>
          <span class="dropdown-item">
            Asset {{$asset ? $asset->name : 'Unknown Asset ID: '. $movement->rfid_no}} has been moved {{$movement->scan_type}} from {{$movement->device_id}}
          </span>
          @endforeach
        </div>
      </li>

      <!-- User Profile Section -->
      <li class="nav-item dropdown">
        <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
          <div class="navbar-profile d-flex align-items-center">
            <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/face15.jpg') }}" alt="">
            <p class="mb-0 d-none d-sm-block navbar-profile-name">ADMINISTRATOR</p>
            <i class="mdi mdi-menu-down d-none d-sm-block"></i>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
          <h6 class="p-3 mb-0" style="font-size: 1rem !important;">Profile</h6>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <i class="mdi mdi-settings dropdown-icon"></i>
            <span class="ml-2">Settings</span>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="mdi mdi-logout dropdown-icon text-danger"></i>
            <span class="ml-2">Log out</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mark-as-read').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var notificationId = this.getAttribute('data-id');
            fetch('/notifications/mark-as-read/' + notificationId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            }).then(response => {
                if (response.ok) {
                    this.classList.add('text-muted');
                    this.querySelector('.preview-subject').innerText += ' (Read)';
                    location.reload();
                }
            });
        });
    });
});
</script>
