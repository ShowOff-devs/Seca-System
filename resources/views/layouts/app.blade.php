<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SECA - Security Asset Tracking</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <!-- Core styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <!-- Custom styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/secacss.css') }}">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/images/fav.png') }}" />
</head>
<body>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <div class="container-scroller">
    @include('layouts.sidebar')
    <div class="container-fluid page-body-wrapper">
      @include('layouts.navbar')
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        {{-- @include('layouts.footer') --}}
      </div>
    </div>
  </div>
  <!-- Plugins JS -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- Misc JS -->
  <script src="{{ asset('assets/js/misc.js') }}"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('scripts')
</body>
</html>
