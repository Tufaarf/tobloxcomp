@extends('front.master')
@section('content')
<style>
/* Product Detail Styles */
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
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
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
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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

/* Placeholder pink untuk semua input di order-box */
.order-box input::placeholder {
  color: #f187ab !important;
  opacity: 1;
}
.order-box input::-webkit-input-placeholder { color: #f187ab !important; }
.order-box input:-ms-input-placeholder { color: #f187ab !important; }
.order-box input::-ms-input-placeholder { color: #f187ab !important; }
.order-box input::-moz-placeholder { color: #f187ab !important; }
.order-box input:-moz-placeholder { color: #f187ab !important; }

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
}

.modal-card {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  width: 400px;
  max-width: 100%;
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
            <div class="product-image-container">
                <div class="product-image">
                    <img src="{{ Storage::url($product->banner) }}" alt="{{ $product->name }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="product-info-container">
                        <h1 class="product-title">{{ $product->name }}</h1>
                        <div class="product-description">
                            {{ $product->description }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                  <div class="order-box" style="border:1.5px solid #f187ab; border-radius:18px; background:#fff6fa; box-shadow:none;">
                    <h4 class="mb-4 text-center" style="color:#f187ab;font-weight:700;">Order Information</h4>
                    <form id="orderForm" method="POST" action="{{ route('order.store') }}" enctype="multipart/form-data" autocomplete="off">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
                      <div class="mb-3">
                        <input type="text" id="username" name="username"
                          class="form-control"
                          style="background:#fbd3e2;border:none;border-radius:999px;height:48px;font-weight:600;color:#f187ab;"
                          placeholder="Username Roblox"
                          oninput="onUsernameInput()"
                          value="{{ old('username') }}">
                        <div class="d-flex justify-content-end mt-2">
                          <button type="button" class="btn btn-checkout" style="width:auto;padding:6px 18px;border-radius:999px;font-weight:600;" onclick="verifyUsername()" id="btnVerify">Cek</button>
                        </div>
                        <div id="checkResult" class="mt-2 text-center"></div>
                      </div>
                      <div class="mb-3">
                        <input type="text" id="wa_number" name="wa_number"
                          class="form-control"
                          style="background:#fbd3e2;border:none;border-radius:999px;height:48px;font-weight:600;color:#f187ab;"
                          placeholder="Nomor WA"
                          value="{{ old('wa_number') }}"/>
                      </div>
                      <div class="mb-3">
                        <input type="email" id="email" name="email"
                          class="form-control"
                          style="background:#fbd3e2;border:none;border-radius:999px;height:48px;font-weight:600;color:#f187ab;"
                          placeholder="Email Untuk Notifikasi Pesanan (optional)"
                          value="{{ old('email') }}"/>
                      </div>
                      <div class="mb-3" style="color:#f187ab;font-size:.98rem;">
                        <div>Produk gift gamepass hanya dapat diproses jam 10 pagi sampai jam 10 malam, diluar jam tersebut akan diproses pada jam operasional.</div>
                        <div class="mt-2">Proses pengiriman memakan waktu 5-12 jam</div>
                      </div>
                      <hr style="border-top:1.5px solid #f187ab;">
                      <div class="d-flex justify-content-between align-items-center mb-4 mt-3" style="font-weight:700;">
        <span style="color:#f187ab;">Total</span>
        <span id="totalPrice" style="color:#f187ab;font-size:1.3rem;">Rp {{ number_format($product->price,0,',','.') }}</span>
      </div>
                      <div class="d-flex gap-2 justify-content-between">
                        <button type="button" class="btn btn-checkout" style="background:#f187ab;color:#fff;font-weight:700;border-radius:999px;padding:12px 0;width:50%;" id="btnOpenPayModal">Beli Sekarang</button>
                      </div>
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
      <h5 class="m-0">Pembayaran & Upload Bukti</h5>
      <button type="button" class="modal-close" aria-label="Close" onclick="closePayModal()">×</button>
    </div>

    <label class="mt-3" for="paymentMethod">Metode Pembayaran</label>
    <select id="paymentMethod" name="payment_method" class="form-control" onchange="onPaymentChange(); updatePrice();">
      @foreach($paymentMethods as $pm)
        <option value="{{ $pm['code'] }}"
          data-tax="{{ $pm['fee'] }}"
          data-type="{{ $pm['type'] }}"
          data-target="{{ $pm['target'] }}"
          data-name="{{ $pm['name'] }}"
          @selected(old('payment_method') === $pm['code'])>
          {{ $pm['name'] }} ({{ rtrim(rtrim(number_format($pm['fee'],2,',','.'),'0'),',') }}%)
        </option>
      @endforeach
    </select>
    <div id="payInfo" class="mt-3 p-3" style="border:1px dashed var(--border); border-radius:12px; background:#fff;"></div>

    <hr class="my-3">

    <p class="mb-2">Upload <b>bukti pembayaran</b> (JPG/PNG/PDF, maks 5MB).</p>
    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*,application/pdf" required>
    <div id="proofPreview" class="preview"></div>

    <div class="d-flex gap-2 justify-content-end mt-3">
      <button type="button" class="btn btn-secondary" onclick="closePayModal()">Batal</button>
      <button type="button" id="modalSubmitBtn" class="btn btn-pink">Submit</button>
    </div>
  </div>
</div>

@include('front.footer')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Open Modal when 'Beli Sekarang' is clicked
document.getElementById('btnOpenPayModal').addEventListener('click', function() {
    openPayModal();
    updatePrice();
});

function openPayModal() {
    document.getElementById('payModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    updatePrice();
}
function closePayModal() {
    document.getElementById('payModal').style.display = 'none';
    document.body.style.overflow = '';
}

// Validasi Username Roblox sebelum Checkout
let isUserResolved = false;
let resolvedFor    = "";

function verifyUsername() {
    const uname = document.getElementById('username').value.trim();
    const resultDiv = document.getElementById('checkResult');
    const btn = document.getElementById('btnVerify');

    if (!uname) {
        resultDiv.innerHTML = '<span class="text-danger fw-bold">Harap isi username Roblox.</span>';
        return;
    }

    btn.disabled = true;
    resultDiv.innerHTML = '<div class="spinner"></div><div class="mt-2 text-muted">Memeriksa username...</div>';

    fetch('{{ route('roblox.resolve') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ username: uname })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            isUserResolved = true;
            resolvedFor = data.username || uname;
            resultDiv.innerHTML = `
                <div>
                    <p class="m-0 fw-bold">@${data.username}</p>
                    <p class="m-0 text-success fw-bold">Username valid</p>
                </div>
            `;
        } else {
            isUserResolved = false; resolvedFor = "";
            resultDiv.innerHTML = `<span class="text-danger fw-bold">${data.message || 'Username tidak ditemukan.'}</span>`;
        }
    })
    .catch(() => {
        isUserResolved = false; resolvedFor = "";
        resultDiv.innerHTML = '<span class="text-danger fw-bold">Gagal menghubungi server.</span>';
    })
    .finally(() => {
        btn.disabled = false;
    });
}

// Update Harga dan Info Pembayaran
function updatePrice() {
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const selectedMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex];
    const taxRate = parseFloat(selectedMethod.dataset.tax || 0);
    const basePrice = parseFloat("{{ $product->price }}");
    const tax = Math.round(basePrice * (taxRate / 100));
    const totalPrice = basePrice + tax;

    // Tampilkan info pembayaran sesuai tipe
    const payInfoDiv = document.getElementById('payInfo');
    if (selectedMethod.dataset.type === 'image') {
        payInfoDiv.innerHTML = `
            <div class="mb-2"><b>Scan QR ${selectedMethod.dataset.name}:</b></div>
            <img src="${selectedMethod.dataset.target}" alt="QR ${selectedMethod.dataset.name}" style="max-width:100%;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,.08)">
            <div class="mt-2"><b>Total Harga:</b> Rp ${totalPrice.toLocaleString('id-ID')}</div>
        `;
    } else {
        payInfoDiv.innerHTML = `
            <div class="mb-2"><b>Tujuan ${selectedMethod.dataset.name}:</b></div>
            <div class="fw-bold" id="payTarget" style="font-size:1.05rem">${selectedMethod.dataset.target}</div>
            <button class="btn btn-sm btn-checkout mt-2" type="button" onclick="copyPayTarget()">Copy</button>
            <div class="mt-2"><b>Total Harga:</b> Rp ${totalPrice.toLocaleString('id-ID')}</div>
        `;
    }
    document.getElementById('totalPrice').innerText = totalPrice.toLocaleString('id-ID');
}

function onPaymentChange() {
    updatePrice();
}

function copyPayTarget(){
    const el = document.getElementById('payTarget');
    if (!el) return;
    navigator.clipboard.writeText(el.textContent.trim()).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Nomor/rekening tujuan disalin!',
            timer: 1500
        });
    });
}

// Submit form utama saat klik tombol submit di modal
document.getElementById('modalSubmitBtn').addEventListener('click', async function(e) {
    e.preventDefault();

    // Validasi minimal
    if (!isUserResolved) {
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Silakan cek username Roblox terlebih dahulu.'
        });
        return;
    }

    // File validation
    const paymentProof = document.getElementById('payment_proof').files[0];
    if (!paymentProof) {
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Harap upload bukti pembayaran'
        });
        return;
    }

    // Show loading
    Swal.fire({
        title: 'Memproses pesanan...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const form = document.getElementById('orderForm');
    const formData = new FormData();
    formData.append('username', document.getElementById('username').value);
    formData.append('wa_number', document.getElementById('wa_number').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('product_id', '{{ $product->id }}');
    formData.append('game_id', '{{ $product->game->id }}');
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('payment_method', document.getElementById('paymentMethod').value);
    const paymentProofFile = document.getElementById('payment_proof').files[0];
    if (paymentProofFile) {
        formData.append('payment_proof', paymentProofFile);
    }

    try {
        const response = await fetch('{{ route('order.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (response.ok) {
            await Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 2000
            });
            window.location.href = data.redirect_url;
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
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengirim pesanan.'
        });
    }
});

// Mengaktifkan tombol submit ketika form diisi dengan benar
toggleSubmitButton();
</script>

<script src="{{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/php-email-form/validate.js')}}"></script>
<script src="{{asset('assets/aos/aos.js')}}"></script>
<script src="{{asset('assets/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('assets/purecounter/purecounter_vanilla.js')}}"></script>
<script src="{{asset('assets/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('assets/isotope-layout/isotope.pkgd.min.js')}}"></script>

<script src="{{asset('js/main.js')}}"></script>

</script>

<script src="{{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/php-email-form/validate.js')}}"></script>
<script src="{{asset('assets/aos/aos.js')}}"></script>
<script src="{{asset('assets/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('assets/purecounter/purecounter_vanilla.js')}}"></script>
<script src="{{asset('assets/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('assets/isotope-layout/isotope.pkgd.min.js')}}"></script>

<script src="{{asset('js/main.js')}}"></script>
@endsection
