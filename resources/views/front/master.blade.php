<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Toblox ID</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{asset('assets/img/logo/logo1.png')}}" rel="icon">
  <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Fonts -->
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('assets/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/glightbox/css/glightbox.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('css/main.css')}}?v={{ time() }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Dewi
  * Template URL: https://bootstrapmade.com/dewi-free-multi-purpose-html-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    #preloader {
      position: fixed;
      inset: 0;
      z-index: 999999;
      background: #ffffff;
      display: flex !important;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease-out;
    }
    #preloader:before,
    #preloader:after {
      display: none !important;
      content: none !important;
    }
    #preloader img {
      width: 120px !important;
      height: auto !important;
      animation: breathe 2s ease-in-out infinite;
    }
    #preloader.loaded {
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
    }
    @keyframes breathe {
      0%, 100% { transform: scale(0.85); opacity: 0.7; }
      50% { transform: scale(1.15); opacity: 1; }
    }
  </style>
</head>

<body class="@yield('body-class', 'index-page')">

  <div id="preloader">
    <img src="{{ asset('assets/img/logo/logo1.png') }}" alt="Loading...">
  </div>

  <main class="main">
    @yield('content')
  </main>

  @include('front.footer')
 
  <!-- Vendor JS Files -->
  <script src="{{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/aos/aos.js')}}"></script>
  <script src="{{asset('assets/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('assets/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
  <script src="{{asset('assets/isotope-layout/isotope.pkgd.min.js')}}"></script>
  
  <script src="{{asset('js/main.js')}}?v={{ time() }}"></script>

  @stack('before-scripts')
  @stack('after-scripts')

</body>

</html>
