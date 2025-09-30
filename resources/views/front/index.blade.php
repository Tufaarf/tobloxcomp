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
section[id] {                         /* kurangi jarak antar semua section */
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
#services .section-title{            /* tambah jarak dari judul ke kartu */
  margin-bottom: 56px;               /* sebelumnya 24–32px */
}
#services .cards-row{                /* kompensasi ikon yang menonjol keluar kartu */
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
</style>

<body class="index-page">

  @include('front.header')

  <main class="main">

    <!-- Hero Section -->
    <section class="hero-wrap container-xxl px-3" id="hero">
      @if($herosections->count() > 0)
      <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner rounded-4 shadow-lg overflow-hidden">
          @foreach($herosections as $i => $hero)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
              <img src="{{ Storage::url($hero->image_url) }}" class="d-block w-100 hero-img" alt="Hero {{ $i+1 }}">
            </div>
          @endforeach
        </div>
        <button class="carousel-control-prev hero-ctrl" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon hero-ctrl-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Prev</span>
        </button>
        <button class="carousel-control-next hero-ctrl" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon hero-ctrl-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      @endif
    </section>
    <!-- /Hero Section -->

    <!-- Services Section -->
    <section id="services" class="services section">
      <div class="container section-title" data-aos="fade-up">
        <h2 style="color: #f187ab">Services</h2>
        <p style="color: #f187ab">Featured Services<br></p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
       <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 cards-row">
          @forelse ($services->take(3) as $service)
            <div class="col d-flex">
              <div class="service-item h-100 w-100 position-relative p-4">
                <div class="details">
                  <div class="icon mb-3 d-flex align-items-center justify-content-center">
                    <i class="bi bi-{{$service->icon_class}}"></i>
                  </div>
                  <a href="{{Route('robux.topup')}}" class="stretched-link">
                    <h3 class="h5 mb-2">{{ $service->title }}</h3>
                  </a>
                  <p class="mb-0">{!! $service->description !!}</p>
                </div>
              </div>
            </div>
          @empty
            <div class="col"><p class="mb-0">Tidak ada Data</p></div>
          @endforelse
        </div>
      </div>
    </section>
    <!-- /Services Section -->

    <!-- Product Section -->
    <section class="product-section">
      <div class="container">
        <div class="section-title text-center" data-aos="fade-up">
          <h2>🔥 Product Terbaru!!</h2>
        </div>

        <div class="swiper product-swiper" data-aos="fade-up">
          <div class="swiper-wrapper">
            @forelse ($products as $product)
              <div class="swiper-slide">
                <div class="product-card">
                  <a href="{{ route('front.product.detail', $product->id) }}" class="text-decoration-none">
                    <img src="{{ Storage::url($product->image) }}" class="card-img" alt="{{ $product->name }}">
                    <div class="card-body">
                      <h5 class="card-title">{{ $product->name }}</h5>
                      <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                  </a>
                </div>
              </div>
            @empty
              <div class="swiper-slide">
                <p class="text-center">Tidak ada produk</p>
              </div>
            @endforelse
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>
    <!-- /Product Section -->

    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container">
        <div class="row gy-4">
          @foreach ($abouts as $about)
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <h3>{{$about->headline}}</h3>
              <img src="{{Storage::url($about->image)}}" class="img-fluid rounded-4 mb-4" alt="">
              <p>{!! $about->description !!}</p>
            </div>

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
              <div class="content ps-0 ps-lg-5">
                <p>{!! $about->sub_description !!}</p>
                <div class="position-relative mt-4">
                  <img src="{{Storage::url($about->second_image)}}" class="img-fluid rounded-4" alt="">
                </div>
              </div>
            </div>
        </div>
          @endforeach
      </div>
    </section>
    <!-- /About Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section light-background">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          @forelse ($companyStats as $stat)
            <div class="col-lg-3 col-md-6">
              <div class="stats-item d-flex align-items-center w-100 h-100">
                <div>
                  <span style="color: #f187ab">{{$stat->goals}}</span>
                  <p style="color: #f187ab; font-weight: bold;">{{$stat->title}}</p>
                </div>
              </div>
            </div>
          @empty
            <p>Data Kosong</p>
          @endforelse
        </div>
      </div>
    </section>
    <!-- /Stats Section -->

    <div class="section-spacer"></div>

    <!-- Community Section -->
    <section id="community" class="community section light-background">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-center g-4">
          <div class="col-lg-6 text-center">
            <img src="{{ asset('assets/img/mascot/maskot.png') }}" alt="Community" class="img-fluid rounded-4 shadow-sm">
          </div>
          <div class="col-lg-6">
            <h2 class="fw-bold mb-3" style="color: #f187ab">{{ $community->header ?? 'Mengalami kesulitan saat membeli Robux?' }}</h2>
            <div class="mb-4" style="color: #f187ab">{!! $community->description ?? '...' !!}</div>

            <div class="d-flex align-items-center gap-3 mb-4">
              @if(!empty($community?->link_whatsapp))
                <a href="{{ $community->link_whatsapp }}" class="badge-app" target="_blank" rel="noopener">
                  <i class="bi bi-whatsapp"></i>
                </a>
              @endif
              @if(!empty($community?->link_instagram))
                <a href="{{ $community->link_instagram }}" class="badge-app" target="_blank" rel="noopener">
                  <i class="bi bi-instagram"></i>
                </a>
              @endif
              @if(!empty($community?->link_discord))
                <a href="{{ $community->link_discord }}" class="badge-app" target="_blank" rel="noopener">
                  <i class="bi bi-discord"></i>
                </a>
              @endif
            </div>
            {{-- <a href="#" class="btn btn-guide px-4 py-2 rounded-3">Panduan</a> --}}
          </div>
        </div>
      </div>
    </section>
    <!-- /Community Section -->

    <!-- FAQ Section -->
    <section id="faq" class="faq section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="section-title text-center">
          <p style="color: #f187ab">Frequently Ask Question (FAQ)</p>
        </div>

        <div class="row">
          <div class="col-lg-10 mx-auto">
            <div class="accordion accordion-flush" id="faqAccordion">
              @forelse ($faqs as $idx => $faq)
                @php $collapseId = "faq" . ($idx+1); @endphp
                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading-{{ $collapseId }}">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                            aria-expanded="false" aria-controls="{{ $collapseId }}">
                      {{ $faq->question }}
                    </button>
                  </h2>
                  <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                       aria-labelledby="heading-{{ $collapseId }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      {!! $faq->answer !!}
                    </div>
                  </div>
                </div>
              @empty
                <p class="text-center text-muted">Belum ada FAQ.</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /FAQ Section -->

    <!-- Testimonials Section -->

    <!-- /Testimonials Section -->

  </main>

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
  <script src="{{asset('js/main.js')}}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      GLightbox({
        selector: '.portfolio .glightbox',
        descPosition: 'right',
        width: '96vw',
        height: '90vh',
        loop: true,
      });

      // Initialize product swiper
      new Swiper('.product-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 25,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        breakpoints: {
          320: {
            slidesPerView: 1,
            spaceBetween: 20
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 25
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 25
          }
        }
      });
    });
  </script>

</body>
@endsection
