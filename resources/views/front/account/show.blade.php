@extends('front.master')
@section('content')
    <style>
        /* Product Detail Styles from detail-product.blade.php */
        header.header {
            background: #f187ab !important;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            box-shadow: 0 8px 24px rgba(0, 49, 132, 0.12);
            transition: background .25s ease, box-shadow .25s ease;
        }

        header.header.scrolled,
        .scrolled header.header,
        header.header.sticked {
            background: #f187ab !important;
            box-shadow: 0 10px 28px rgba(0, 49, 132, 0.18);
        }

        header.header .navmenu a {
            color: #fff;
        }

        header.header .navmenu a:hover,
        header.header .navmenu a.active {
            color: #e6f2ff;
        }

        .mobile-nav-active header.header,
        .mobile-nav-active .navmenu {
            background: rgba(14, 66, 178, 0.95) !important;
        }

        .product-detail {
            padding: 100px 0 40px 0;
            background: #fff;
        }

        /* Flexbox for alignment */
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            /* Ensures cards are spaced properly */
        }

        .col-lg-8,
        .col-lg-4 {
            display: flex;
            flex-direction: column;
        }

        /* Resize image and keep it centered */
        .product-image-container {
            width: 100%;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .product-image {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 380px;
            aspect-ratio: 4 / 5;
            margin: 0 auto;
            background: #fff;
            position: relative;
            cursor: zoom-in;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease, opacity 0.4s ease;
        }

        .product-image .slide-out {
            transform: translateX(-100%);
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
        }

        .product-image .slide-in {
            transform: translateX(100%);
            opacity: 0;
        }

        .product-image img.active {
            transform: translateX(0);
            opacity: 1;
        }

        /* Thumbnail Gallery */
        .gallery-thumbs {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .gallery-thumb {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            opacity: 0.7;
            transition: all 0.2s;
        }

        .gallery-thumb:hover {
            opacity: 1;
        }

        .gallery-thumb.active {
            border-color: #f187ab;
            opacity: 1;
            box-shadow: 0 0 10px rgba(241, 135, 171, 0.3);
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        /* Product info container */
        .product-info-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 20px;
            height: 100%;
            /* Ensure same height */
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
            background: #fff;
            border: 1.5px solid #fbd3e2;
            border-radius: 20px;
            padding: 30px;
            margin-top: 0;
            position: sticky;
            top: 100px;
            box-shadow: 0 10px 30px rgba(241, 135, 171, 0.1);
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

        /* Placeholder pink untuk semua input di order-box */
        .order-box input::placeholder {
            color: #f187ab !important;
            opacity: 1;
        }

        .order-box input::-webkit-input-placeholder {
            color: #f187ab !important;
        }

        .order-box input:-ms-input-placeholder {
            color: #f187ab !important;
        }

        .order-box input::-ms-input-placeholder {
            color: #f187ab !important;
        }

        .order-box input::-moz-placeholder {
            color: #f187ab !important;
        }

        .order-box input:-moz-placeholder {
            color: #f187ab !important;
        }

        @media (max-width: 992px) {
            .order-box {
                width: 100%;
                margin-top: 20px;
            }
        }

        /* Modal Styles */
        #payModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1050;
            /* Standard Bootstrap modal z-index */
        }

        .modal-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 400px;
            max-width: 100%;
            max-height: 90vh;
            /* Limit height */
            overflow-y: auto;
            /* Enable scrolling */
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h5 {
            margin: 0;
        }

        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>

    @include('front.header')

    <main class="main">
        <section class="product-detail">
            <div class="container">
                @php
                    $images = $item->images;
                    if (is_string($images))
                        $images = json_decode($images, true);
                    $imageList = is_array($images) ? $images : [];
                    $firstImage = count($imageList) > 0 ? Storage::url($imageList[0]) : asset('assets/img/placeholder.jpg');
                @endphp

                <div class="product-image-container">
                    <div class="product-image" id="image-container">
                        <a id="main-glightbox" href="{{ $firstImage }}" class="glightbox">
                            <img id="main-display-image" class="active" src="{{ $firstImage }}" alt="{{ $item->name }}">
                        </a>
                    </div>
                    <!-- Gallery Thumbs -->
                    @if(count($imageList) > 1)
                        <div class="gallery-thumbs">
                            @foreach($imageList as $img)
                                <div class="gallery-thumb {{ $loop->first ? 'active' : '' }}"
                                    onclick="changeImage(this, '{{ Storage::url($img) }}')">
                                    <img src="{{ Storage::url($img) }}" alt="Thumb">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="product-info-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h1 class="product-title mb-0">{{ $item->name }}</h1>
                            </div>
                            <div class="product-description rich-text-content">
                                {!! $item->description !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="order-box">
                            <h4 class="mb-4 text-center" style="color:#f187ab;font-weight:700;">Order Information</h4>

                            <form id="orderForm" method="POST" action="{{ route('account.store') }}"
                                enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <input type="hidden" name="account_product_id" value="{{ $item->id }}">

                                {{-- Nama Lengkap --}}
                                <div class="mb-3">
                                    <input type="text" id="name" name="name" class="form-control"
                                        style="background:#fbd3e2;border:none;border-radius:999px;height:48px;font-weight:600;color:#f187ab;"
                                        placeholder="Nama Lengkap" required value="{{ old('name') }}">
                                </div>

                                {{-- Nomor WhatsApp --}}
                                <div class="mb-3">
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        style="background:#fbd3e2;border:none;border-radius:999px;height:48px;font-weight:600;color:#f187ab;"
                                        placeholder="Nomor WA" required value="{{ old('phone') }}" />
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <input type="email" id="email" name="email" class="form-control"
                                        style="background:#fbd3e2;border:none;border-radius:999px;height:48px;font-weight:600;color:#f187ab;"
                                        placeholder="Email" required value="{{ old('email') }}" />
                                </div>

                                <div class="mb-3" style="color:#f187ab;font-size:.9rem;">
                                    <div>Stok Tersedia: <strong>{{ $item->stock }}</strong></div>
                                    <div class="mt-1">Data akun akan dikirimkan ke WhatsApp anda setelah pembayaran
                                        dikonfirmasi.</div>
                                </div>

                                <hr style="border-top:1.5px solid #f187ab;">

                                <div class="d-flex justify-content-between align-items-center mb-4 mt-3"
                                    style="font-weight:700;">
                                    <span style="color:#f187ab;">Total</span>
                                    <span id="totalPriceDisplay" style="color:#f187ab;font-size:1.3rem;">Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>

                                @if($item->stock > 0)
                                    <div class="d-flex gap-2 justify-content-between">
                                        <button type="button" class="btn btn-checkout"
                                            style="background:#f187ab;color:#fff;font-weight:700;border-radius:999px;padding:12px 0;width:100%;"
                                            id="btnOpenPayModal">Beli Sekarang</button>
                                    </div>
                                @else
                                    <div class="alert alert-secondary text-center w-100">Stok Habis</div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Payment Modal -->
    <div id="payModal" class="modal-backdrop">
        <div class="modal-card">
            <div class="modal-header">
                <h5 class="m-0">Pembayaran</h5>
                <button type="button" class="modal-close" aria-label="Close" onclick="closePayModal()">×</button>
            </div>

            <label class="mt-3" for="paymentMethod">Metode Pembayaran</label>
            <select id="paymentMethod" name="payment_method" class="form-control" onchange="onPaymentChange();">
                @foreach($paymentMethods as $pm)
                    <option value="{{ $pm['code'] }}" data-type="{{ $pm['type'] }}" data-target="{{ $pm['target'] }}"
                        data-name="{{ $pm['name'] }}" @selected(old('payment_method') === $pm['code'])>
                        {{ $pm['name'] }}
                    </option>
                @endforeach
            </select>
            <div id="payInfo" class="mt-3 p-3"
                style="border:1px dashed var(--border); border-radius:12px; background:#fff;"></div>

            <hr class="my-3">

            {{-- Upload Bukti (Optional but recommended) --}}
            <p class="mb-2">Upload <b>bukti pembayaran</b> (Opsional, mempercepat proses).</p>
            {{-- Note: name='payment_proof' matched with controller --}}
            <input type="file" name="payment_proof" id="payment_proof" class="form-control"
                accept="image/*,application/pdf">

            <div class="d-flex gap-2 justify-content-end mt-3">
                <button type="button" class="btn btn-secondary" onclick="closePayModal()">Batal</button>
                <button type="button" id="modalSubmitBtn" class="btn btn-pink">Konfirmasi Pesanan</button>
            </div>
        </div>
    </div>

    @include('front.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        // Image Gallery with Slide Animation
        let isAnimating = false;
        function changeImage(el, src) {
            if (isAnimating) return;
            const container = document.getElementById('image-container');
            const currentImg = container.querySelector('img.active');
            
            if (currentImg.src === src) return;

            isAnimating = true;
            
            // Create new image element
            const newImg = document.createElement('img');
            newImg.src = src;
            newImg.classList.add('slide-in');
            container.appendChild(newImg);

            // Trigger animation
            setTimeout(() => {
                currentImg.classList.add('slide-out');
                currentImg.classList.remove('active');
                
                newImg.classList.add('active');
                newImg.classList.remove('slide-in');
            }, 50);

            // Cleanup
            setTimeout(() => {
                currentImg.remove();
                isAnimating = false;
            }, 450);

            // Update Lightbox href
            document.getElementById('main-glightbox').href = src;
            const lightbox = GLightbox({ selector: '.glightbox' }); // Refresh lightbox

            document.querySelectorAll('.gallery-thumb').forEach(thumb => thumb.classList.remove('active'));
            el.classList.add('active');
        }

        // Payment Modal Logic
        document.getElementById('btnOpenPayModal').addEventListener('click', function () {
            // Validate inputs first
            const form = document.getElementById('orderForm');
            if (form.reportValidity()) {
                openPayModal();
                updatePayInfo();
            }
        });

        function openPayModal() {
            document.getElementById('payModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            updatePayInfo();
        }
        function closePayModal() {
            document.getElementById('payModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function updatePayInfo() {
            const paymentMethodSelect = document.getElementById('paymentMethod');
            const selectedMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex];
            const price = {{ $item->price }};

            const payInfoDiv = document.getElementById('payInfo');

            if (selectedMethod.dataset.type === 'image') {
                payInfoDiv.innerHTML = `
                                            <div class="mb-2"><b>Scan QR ${selectedMethod.dataset.name}:</b></div>
                                            <img src="${selectedMethod.dataset.target}" alt="QR" style="max-width:100%;max-height:300px;object-fit:contain;border-radius:12px;">
                                            <div class="mt-2 text-center">
                                                 <a href="${selectedMethod.dataset.target}" download="QRIS.jpg" class="btn btn-sm btn-secondary" style="background:#f187ab;color:#fff;border:none;">Download QR</a>
                                            </div>
                                            <div class="mt-2"><b>Total:</b> Rp ${price.toLocaleString('id-ID')}</div>
                                        `;
            } else {
                payInfoDiv.innerHTML = `
                                            <div class="mb-2"><b>Tujuan ${selectedMethod.dataset.name}:</b></div>
                                            <div class="fw-bold fs-5" id="payTarget">${selectedMethod.dataset.target}</div>
                                            <button class="btn btn-sm btn-outline-primary mt-2" type="button" onclick="copyPayTarget()">Copy</button>
                                            <div class="mt-2"><b>Total:</b> Rp ${price.toLocaleString('id-ID')}</div>
                                        `;
            }
        }

        function onPaymentChange() {
            updatePayInfo();
        }

        function copyPayTarget() {
            const el = document.getElementById('payTarget');
            if (!el) return;
            navigator.clipboard.writeText(el.textContent.trim()).then(() => {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Disalin!', timer: 1000, showConfirmButton: false });
            });
        }

        // Submit Logic
        document.getElementById('modalSubmitBtn').addEventListener('click', async function (e) {
            e.preventDefault();

            // Prepare FormData
            const form = document.getElementById('orderForm');
            const formData = new FormData(form);

            // Add payment method from modal
            formData.append('payment_method', document.getElementById('paymentMethod').value);

            // Add file if exists
            const fileForData = document.getElementById('payment_proof').files[0];
            if (fileForData) {
                formData.append('payment_proof', fileForData);
            }

            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch('{{ route('account.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Success Alert with Order ID and Buttons
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Berhasil!',
                        html: `
                                        <p class="mb-2">Order ID Anda:</p>
                                        <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                                            <h3 class="m-0" style="color:#f187ab; font-weight:800;" id="successOrderId">${data.order_id}</h3>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyOrderId('${data.order_id}')">
                                                Copy
                                            </button>
                                        </div>
                                        <p class="small text-muted mb-0">Silahkan simpan Order ID ini untuk cek status transaksi.</p>
                                    `,
                        showCancelButton: true,
                        confirmButtonText: 'Track Order',
                        cancelButtonText: 'Kembali ke Beranda',
                        confirmButtonColor: '#f187ab',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true,
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.href = "{{ url('/') }}";
                        }
                    });


                } else {
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\n');
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: errorMessages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Terjadi kesalahan saat memproses pesanan.'
                        });
                    }
                }
            } catch (err) {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan' });
            }
        });

        // Global function for SweetAlert
        function copyOrderId(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Visual feedback inside Swal
                const btn = Swal.getHtmlContainer().querySelector('button.btn-outline-secondary');
                if (btn) {
                    const originalText = btn.innerHTML;
                    btn.innerHTML = 'Copied!';
                    btn.classList.replace('btn-outline-secondary', 'btn-success');
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.replace('btn-success', 'btn-outline-secondary');
                    }, 2000);
                }
            });
        }

    </script>

    <script src="{{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/php-email-form/validate.js')}}"></script>
    <script src="{{asset('assets/aos/aos.js')}}"></script>
    <script src="{{asset('assets/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('assets/purecounter/purecounter_vanilla.js')}}"></script>
    <script src="{{asset('assets/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('assets/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('assets/isotope-layout/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}?v={{ time() }}"></script>
@endsection