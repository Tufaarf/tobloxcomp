@extends('front.master')
@section('content')
<style>
/* ===== Header ===== */

/* ===== GLightbox layout fix ===== */
.glightbox-container .gslide-description,
.glightbox-container .gdesc { position: static !important; width: auto !important; }
.glightbox-container .ginner-container{ width: min(96vw, 1440px) !important; height: 90vh !important; }
.glightbox-container .gslide{ display:flex !important; align-items:stretch; gap:0; }
.glightbox-container .gslide-media{
  flex:1 1 auto; height:90vh; aspect-ratio:4/3; max-width:calc(96vw - 420px);
}
.glightbox-container .gslide-media img,
.glightbox-container .gslide-media video{ width:100%; height:100%; object-fit:cover; display:block; }
.glightbox-container .gslide-description{
  flex:0 0 420px; max-width:420px; background:#fff; border-left:1px solid rgba(0,0,0,.08);
  display:flex; flex-direction:column; overflow:hidden;
}
.glightbox-container .gdesc-inner{ height:100%; overflow-y:auto; padding:18px 20px; }
.glightbox-container .gdesc-inner h1,
.glightbox-container .gdesc-inner h2,
.glightbox-container .gdesc-inner h3{ margin:0 0 .5rem; }
.glightbox-container .gdesc-inner ul,
.glightbox-container .gdesc-inner ol{ padding-left:1.25rem; margin:.25rem 0 .75rem; }
@media (max-width: 992px){
  .glightbox-container .gslide{ display:block !important; }
  .glightbox-container .gslide-media{ height:50vh; aspect-ratio:auto; max-width:100%; }
  .glightbox-container .gslide-description{ max-width:100%; flex-basis:auto; }
  .glightbox-container .gdesc-inner{ height:calc(40vh - 0px); }
}

/* ===== Global spacings (lebih rapat antar section) ===== */
body { padding-top: 72px; }
.hero-wrap { margin-top: 18px; margin-bottom: 24px; }

section.section,
section[id] {                                 /* kurangi jarak antar semua section */
  padding: 36px 0;
}
@media (min-width: 992px){
  section.section,
  section[id]{ padding: 56px 0; }
}

/* default jarak judul ke konten lebih rapat */
.section-title { margin-bottom: 24px; }

/* khusus services: tambahkan jarak antara teks “Featured Services” dan kartu */
.services .section-title { margin-bottom: 32px; }

/* spacer utilitas (dipakai sekali di bawah): lebih kecil */
.section-spacer { height: 24px; }
@media (min-width: 992px){
  .section-spacer { height: 32px; }
}

/* ===== Hero controls ===== */
.hero-img{
  height: 320px; /* reduced from 420px */
  object-fit: cover;
  display: block;
}
.hero-ctrl{ width:3.25rem; }
.hero-ctrl-icon{
  background-color: rgba(0,0,0,.65);
  border-radius: 999px;
  padding:14px;
  background-size:45% 45%;
}
.carousel-control-prev{ left:-12px; }
.carousel-control-next{ right:-12px; }
@media (max-width: 768px){
  body{ padding-top:64px; }
  .hero-img{
    height: 200px; /* reduced from 260px */
  }
  .carousel-control-prev{ left:-8px; }
  .carousel-control-next{ right:-8px; }
}

/* ===== Theme accents ===== */
.light-background { background:#ffeef4; }
.community .badge-app{
  width:48px; height:48px; display:inline-flex; align-items:center; justify-content:center;
  border-radius:999px; background:#fff; box-shadow:0 6px 20px rgba(0,0,0,.06); font-size:22px;
}
.community .btn-guide{ background:#f187ab; border:0; color:#fff; }
.community .btn-guide:hover{ filter:brightness(0.95); }

/* ===== FAQ ===== */
.faq .accordion-button{
  font-weight: 600;
  color: #f187ab;
  background: #fff5f8;
}
.faq .accordion-item{
  border-radius: 14px;
  overflow: hidden;
  margin-bottom: 14px;
  border: 1px solid rgba(241, 135, 171, 0.2);  /* Lighter pink border */
}
.faq .accordion-button:not(.collapsed),
.faq .accordion-button{
  color: #f187ab;
  background: #fff5f8;
}
.faq .accordion-button:focus {
  border-color: rgba(241, 135, 171, 0.25);
  box-shadow: 0 0 0 0.25rem rgba(241, 135, 171, 0.25);
}
.faq .accordion-button::after {
  color: #f187ab;
}

/* ===== Services cards ===== */
.services .service-item{
  border-radius:12px; background:#fff; box-shadow:0 8px 24px rgba(0,0,0,.06);
}
.services .service-item .icon{
  width:56px; height:56px; border-radius:999px;
  display:flex; align-items:center; justify-content:center;
}

/* Jarak antar card (sudah pakai g-4, ini hanya memastikan konsisten) */
.services .row{ --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.5rem; }

/* ===== Fix: jarak judul 'Featured Services' dengan kartu ===== */
#services .section-title{              /* tambah jarak dari judul ke kartu */
  margin-bottom: 56px;                 /* sebelumnya 24–32px */
}
#services .cards-row{                  /* kompensasi ikon yang menonjol keluar kartu */
  padding-top: 28px;
}

/* Sesuaikan di mobile agar tetap proporsional */
@media (max-width: 576px){
  #services .section-title{ margin-bottom: 44px; }
  #services .cards-row{ padding-top: 22px; }
}

/* ===== Product cards ===== */
.product-section {
  background: #ffeef4;
  padding: 50px 0;
  overflow: hidden;
}
.product-section .section-title {
  color: #f187ab;
  margin-bottom: 40px;
}
.product-section .swiper {
  padding: 10px 5px 30px;
}
.product-section .swiper-slide {
  width: 280px;
  margin-right: 25px;
}
.product-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  transition: transform 0.3s ease;
  height: 100%;
}
.product-card:hover {
  transform: translateY(-5px);
}
.product-card .card-img {
  border-radius: 12px 12px 0 0;
  height: 200px;
  width: 100%;
  object-fit: cover;
}
.product-card .card-body {
  padding: 1.25rem;
}
.product-card .card-title {
  color: #f187ab;
  font-weight: 600;
  margin-bottom: 0.5rem;
}
.product-price {
  color: #f187ab;
  font-weight: bold;
  font-size: 1.1rem;
  margin-top: 0.5rem;
}

/* ===== KODE BARU: Robux Product Cards ===== */
.robux-section {
  background: #fff5f8; /* Light pink background from screenshot */
  padding: 50px 0;
}
.robux-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 2.5rem; /* Space between cards */
}
.robux-card {
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  width: 100%;
  max-width: 360px; /* Max width for each card */
  box-shadow: 0 8px 24px rgba(241, 135, 171, 0.1);
  text-align: center;
  transition: transform 0.3s ease;
}
.robux-card:hover {
  transform: translateY(-5px);
}
.robux-card h3 {
  color: #f187ab; /* Main pink from theme */
  font-weight: 700;
  font-size: 1.5rem; /* 24px */
  margin-bottom: 12px;
}
.robux-card p {
  color: #f187ab;
  font-size: 0.9rem; /* 14.4px */
  line-height: 1.6;
  margin-bottom: 24px;
  min-height: 90px; /* To keep cards aligned */
}
.robux-card-footer {
  display: flex;
  align-items: center;
  justify-content: center; /* DIUBAH: Menengahkan tombol */
}
/* CSS untuk icon-box dihapus */
.robux-card .btn-beli {
  background: #f187ab;
  color: #fff;
  border: 0;
  padding: 12px 28px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.95rem;
  text-decoration: none;
  transition: background 0.3s;
}
.robux-card .btn-beli:hover {
  background: #e0799d; /* Slightly darker pink */
}
/* CSS untuk card-mascot dihapus */
/* ===== AKHIR KODE BARU ===== */

</style>

<body class="index-page">

  @include('front.header')

  <main class="main">

    <section class="robux-section">
  <div class="container">
    <div class="robux-container">

      <div class="robux-card" data-aos="fade-up" data-aos-delay="100">
        <h3>Robux Gamepass PO</h3>
        <p>
          Pesan Robux dengan harga termurah! Proses pengiriman estimasi 8-10 hari. Ideal untuk
          Kamu yang tidak terburu-buru dan mencari harga paling ekonomis.
        </p>
        <div class="robux-card-footer">
          <a href="{{ route('robux.topup') }}" class="btn-beli">Beli Sekarang</a>
          </div>
      </div>

      <div class="robux-card" data-aos="fade-up" data-aos-delay="200">
        <h3>Robux Promo</h3>
        <p>
          Dapatkan Robux dengan harga promo spesial! Penawaran terbatas, proses cepat dan
          aman. Jangan sampai kehabisan slot promo!
        </p>
        <div class="robux-card-footer">
          <a href="{{ route('promo.index') }}" class="btn-beli">Beli Sekarang</a>
          </div>
      </div>

    </div>
  </div>
</section>
</main>

  @include('front.footer')
  <a href="https://wa.me/6281234567890" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-whatsapp"></i></a>

  <div id="preloader"></div>

  <script src="{{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets/aos/aos.js')}}"></script>
  <script src="{{asset('assets/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('assets/purecounter/purecounter_vanilla.js')}}"></script>
  <script src="{{asset('assets/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
  <script src="{{asset('assets/isotope-layout/isotope.pkgd.min.js')}}"></script>

  <script src="{{asset('js/main.js')}}"></script>



</body>
@endsection
