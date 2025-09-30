@extends('front.master')
@section('content')
<style>
/* Product Detail Styles */
header.header{
  background: #f187ab !important;
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  box-shadow: 0 8px 24px rgba(0, 49, 132, 0.12);
  transition: background .25s ease, box-shadow .25s ease;
}
header.header.scrolled,
.scrolled header.header,
header.header.sticked{
  background: #f187ab !important;
  box-shadow: 0 10px 28px rgba(0, 49, 132, 0.18);
}
header.header .navmenu a{ color:#fff; }
header.header .navmenu a:hover,
header.header .navmenu a.active{ color:#e6f2ff; }
.mobile-nav-active header.header,
.mobile-nav-active .navmenu{ background: rgba(14, 66, 178, 0.95) !important; }


</style>

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <img src="{{asset('assets/img/logo/logo1.png')}}" alt="">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">Robux</a></li>
          <li><a href="#services">Item</a></li>
          <li><a href="#portfolio">Cek Transaksi</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
</header>

<main class="main">

</main>

<!-- Vendor JS Files -->
@include('front.footer')

<!-- Scroll Top -->
<a href="https://wa.me/6281234567890" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-whatsapp"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/php-email-form/validate.js')}}"></script>
<script src="{{asset('assets/aos/aos.js')}}"></script>
<script src="{{asset('assets/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('assets/purecounter/purecounter_vanilla.js')}}"></script>
<script src="{{asset('assets/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('assets/isotope-layout/isotope.pkgd.min.js')}}"></script>

<!-- Main JS File -->


</body>
@endsection
