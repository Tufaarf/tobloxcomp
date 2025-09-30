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

.product-detail {
    padding: 40px 0;
    background: #fff;
}

/* Flexbox for alignment */
.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; /* Ensures cards are spaced properly */
}

.col-lg-8, .col-lg-4 {
    display: flex;
    flex-direction: column;
}

/* Resize image and keep it centered */
.product-image-container {
    width: 100%;
    margin-bottom: 30px;
    display: flex;
    justify-content: center;
}

.product-image {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    max-width: 80%; /* Resize the image */
    display: block;
    margin: 0 auto; /* Center the image */
}

.product-image img {
    width: 100%;
    height: auto;
    display: block;
}

/* Product info container */
.product-info-container {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    padding: 25px;
    margin-bottom: 20px;
    height: 100%; /* Ensure same height */
}

.product-title {
    color: #f187ab;
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.product-description {
    color: #666;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

/* Order box styling */
.order-box {
    background: #ffeef4;
    border-radius: 15px;
    padding: 20px;
    margin-top: 0; /* Ensure it does not follow card height */
    position: relative; /* Align within column */
    top: 0; /* Align top of the order box */
}

.quantity-control {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.quantity-btn {
    background: #f187ab;
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    font-size: 20px;
    cursor: pointer;
}

.quantity-input {
    width: 60px;
    height: 40px;
    text-align: center;
    margin: 0 10px;
    border: 2px solid #f187ab;
    border-radius: 8px;
}

.total-price {
    color: #f187ab;
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 20px;
}

.btn-checkout {
    background: #f187ab;
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
    text-align: center;
    display: block;
}

.btn-checkout:hover {
    background: #eb6d96;
    color: white;
    transform: translateY(-2px);
}

/* Optional: Add responsiveness for smaller screens */
@media (max-width: 992px) {
    .order-box {
        width: 100%;
        margin-top: 20px;
    }
}
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
    <section class="product-detail">
        <div class="container">
            <div class="product-image-container">
                <div class="product-image">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="product-info-container">
                        <h1 class="product-title">{{ $product->name }}</h1>
                        <div class="product-description">
                            {!! $product->detail !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="order-box">
                        <div class="quantity-control">
                            <button class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                            <input type="number" class="quantity-input" id="quantity" value="1" min="1">
                            <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
                        </div>
                        <div class="total-price">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <a href="#" class="btn btn-checkout">Beli Sekarang!</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
<script src="{{asset('js/main.js')}}"></script>

<script>
    function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value) || 1;
    const newValue = Math.max(1, currentValue + change);
    input.value = newValue;

    // Update total price
    const basePrice = {{ $product->price }};
    const totalPrice = basePrice * newValue;
    document.querySelector('.total-price').textContent =
        'Rp ' + totalPrice.toLocaleString('id-ID');
}

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
