@extends('front.master')
@section('content')
    <style>
        /* ===== Header ===== */

        /* ===== GLightbox layout fix ===== */
        .glightbox-container .gslide-description,
        .glightbox-container .gdesc {
            position: static !important;
            width: auto !important;
        }

        .glightbox-container .ginner-container {
            width: min(96vw, 1440px) !important;
            height: 90vh !important;
        }

        .glightbox-container .gslide {
            display: flex !important;
            align-items: stretch;
            gap: 0;
        }

        .glightbox-container .gslide-media {
            flex: 1 1 auto;
            height: 90vh;
            aspect-ratio: 4/3;
            max-width: calc(96vw - 420px);
        }

        .glightbox-container .gslide-media img,
        .glightbox-container .gslide-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .glightbox-container .gslide-description {
            flex: 0 0 420px;
            max-width: 420px;
            background: #fff;
            border-left: 1px solid rgba(0, 0, 0, .08);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .glightbox-container .gdesc-inner {
            height: 100%;
            overflow-y: auto;
            padding: 18px 20px;
        }

        .glightbox-container .gdesc-inner h1,
        .glightbox-container .gdesc-inner h2,
        .glightbox-container .gdesc-inner h3 {
            margin: 0 0 .5rem;
        }

        .glightbox-container .gdesc-inner ul,
        .glightbox-container .gdesc-inner ol {
            padding-left: 1.25rem;
            margin: .25rem 0 .75rem;
        }

        @media (max-width: 992px) {
            .glightbox-container .gslide {
                display: block !important;
            }

            .glightbox-container .gslide-media {
                height: 50vh;
                aspect-ratio: auto;
                max-width: 100%;
            }

            .glightbox-container .gslide-description {
                max-width: 100%;
                flex-basis: auto;
            }

            .glightbox-container .gdesc-inner {
                height: calc(40vh - 0px);
            }
        }

        /* ===== Global spacings (lebih rapat antar section) ===== */
        body {
            padding-top: 72px;
        }

        .hero-wrap {
            margin-top: 18px;
            margin-bottom: 24px;
        }

        section.section,
        section[id] {
            /* kurangi jarak antar semua section */
            padding: 36px 0;
        }

        @media (min-width: 992px) {

            section.section,
            section[id] {
                padding: 56px 0;
            }
        }

        /* default jarak judul ke konten lebih rapat */
        .section-title {
            margin-bottom: 24px;
        }

        /* khusus services: tambahkan jarak antara teks “Featured Services” dan kartu */
        .services .section-title {
            margin-bottom: 32px;
        }

        /* spacer utilitas (dipakai sekali di bawah): lebih kecil */
        .section-spacer {
            height: 24px;
        }

        @media (min-width: 992px) {
            .section-spacer {
                height: 32px;
            }
        }

        /* ===== Hero controls ===== */
        .hero-img {
            height: 320px;
            /* reduced from 420px */
            object-fit: cover;
            display: block;
        }

        .hero-ctrl {
            width: 3.25rem;
        }

        .hero-ctrl-icon {
            background-color: rgba(0, 0, 0, .65);
            border-radius: 999px;
            padding: 14px;
            background-size: 45% 45%;
        }

        .carousel-control-prev {
            left: -12px;
        }

        .carousel-control-next {
            right: -12px;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 64px;
            }

            .hero-img {
                height: 200px;
                /* reduced from 260px */
            }

            .carousel-control-prev {
                left: -8px;
            }

            .carousel-control-next {
                right: -8px;
            }
        }

        /* ===== Theme accents ===== */
        .light-background {
            background: #ffeef4;
        }

        .community .badge-app {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .06);
            font-size: 22px;
        }

        .community .btn-guide {
            background: #f187ab;
            border: 0;
            color: #fff;
        }

        .community .btn-guide:hover {
            filter: brightness(0.95);
        }

        /* ===== FAQ ===== */
        .faq .accordion-button {
            font-weight: 600;
            color: #f187ab;
            background: #fff5f8;
        }

        .faq .accordion-item {
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 14px;
            border: 1px solid rgba(241, 135, 171, 0.2);
            /* Lighter pink border */
        }

        .faq .accordion-button:not(.collapsed),
        .faq .accordion-button {
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
        .services .service-item {
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        .services .service-item .icon {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Jarak antar card (sudah pakai g-4, ini hanya memastikan konsisten) */
        .services .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 1.5rem;
        }

        /* ===== Fix: jarak judul 'Featured Services' dengan kartu ===== */
        #services .section-title {
            /* tambah jarak dari judul ke kartu */
            margin-bottom: 56px;
            /* sebelumnya 24–32px */
        }

        #services .cards-row {
            /* kompensasi ikon yang menonjol keluar kartu */
            padding-top: 28px;
        }

        /* Sesuaikan di mobile agar tetap proporsional */
        @media (max-width: 576px) {
            #services .section-title {
                margin-bottom: 44px;
            }

            #services .cards-row {
                padding-top: 22px;
            }
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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



        @include('front.header')

        <main class="main">

            <!-- Hero Section -->


            <!-- Account/Game Items Section -->
            <section class="container my-3">
                <div class="container section-title" data-aos="fade-up">
                    <h2 style="color: #f187ab">Akun</h2>
                    <p style="color: #f187ab">Akun Game Roblox<br></p>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="sticky-top">
                            <select id="gameFilter" class="form-select mb-3">
                                <option value="all" {{ request('game_id') == 'all' ? 'selected' : '' }}>Semua Game</option>
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                                        {{ $game->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Item List Column -->
                    <div class="col-md-9">
                        <div class="row g-3" id="itemList">
                            @if($items->isEmpty())
                                <div class="col-12">
                                    <div class="alert alert-info">Belum ada akun yang tersedia.</div>
                                </div>
                            @else
                                @foreach($items as $item)
                                    @php
                                        $images = $item->images;
                                        if (is_string($images))
                                            $images = json_decode($images, true);
                                        $firstImage = (is_array($images) && count($images) > 0) ? $images[0] : null;
                                        $imageUrl = $firstImage ? Storage::url($firstImage) : asset('assets/img/placeholder.jpg');
                                    @endphp
                                    <div class="col-6 col-md-3 item-card" data-game="{{ $item->game ? $item->game->id : '' }}">
                                        <a href="{{ route('account.show', $item->id) }}" class="text-decoration-none">
                                            <div class="card product-card h-100">
                                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $item->name }}"
                                                    style="height:140px;object-fit:cover;">
                                                <div class="card-body p-3">
                                                    <h5 class="card-title h6 mb-1 text-truncate" title="{{ $item->name }}">
                                                        {{ $item->name }}</h5>
                                                    <p class="card-text small mb-2 text-muted"
                                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                        {{ strip_tags($item->description) }}</p>
                                                    <div class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </div>
                                                    <div class="mt-1 text-muted small">Game:
                                                        {{ $item->game ? $item->game->name : '-' }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </section>

        </main>

        @include('front.footer')
        <!-- Scroll Top -->
        <a href="https://wa.me/6281234567890" class="scroll-top d-flex align-items-center justify-content-center"><i
                class="bi bi-whatsapp"></i></a>



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
        <script src="{{asset('js/main.js')}}?v={{ time() }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Filter items by game
                document.getElementById('gameFilter').addEventListener('change', function () {
                    var selected = this.value;
                    const form = this.closest('form');
                    if (form) {
                        form.submit(); // Server side filtering if using form, but here for consistency with item.blade.php which handled client side
                    } else {
                        // Client side filter
                        window.location.href = "{{ route('account.index') }}?game_id=" + selected;
                    }
                });
            });
        </script>


@endsection