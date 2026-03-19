<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'Purple Admin')</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('backend/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('backend/images/favicon.png') }}" />
  @stack('styles')
</head>
<body>
  <div class="container-scroller">
    <!-- Navbar partial -->
    @include('backend.components.header')
    
    <div class="container-fluid page-body-wrapper">
      <!-- Sidebar partial -->
      @include('backend.components.sidebar')
      
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        <!-- content-wrapper ends -->
        
        <!-- Footer partial -->
        @include('backend.components.footer')
        
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{ asset('backend/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('backend/vendors/js/vendor.bundle.addons.js') }}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{ asset('backend/js/off-canvas.js') }}"></script>
  <script src="{{ asset('backend/js/misc.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('backend/js/dashboard.js') }}"></script>
  <!-- End custom js for this page-->
  @stack('scripts')
</body>

</html>
