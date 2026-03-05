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
  padding: 100px 0 40px 0; /* Add top padding to avoid navbar overlap */
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
  width: 100%;
  max-width: 1920px; /* Maximize to 1920px if container allows, but keep responsive */
  aspect-ratio: 16 / 9;
  margin: 0 auto;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
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
  max-height: 90vh; /* Limit height */
  overflow-y: auto; /* Enable scrolling */
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

.result-card {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  border: 1px solid #f187ab;
  background: #fff6fa;
  border-radius: 16px;
  margin-top: 16px;
  text-align: left;
}
.result-card img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  background: #fff;
  border: 2px solid #fff;
  box-shadow: 0 4px 10px rgba(241, 135, 171, .25);
}
.result-card .username {
  font-size: 1.1rem;
  font-weight: 800;
  color: #f187ab;
}
</style>

@include('front.header')

<main class="main">
    <section class="product-detail">
        <div class="container">
            <div class="product-image-container">
                <a href="{{ Storage::url($product->banner) }}" class="glightbox product-image">
                    <img src="{{ Storage::url($product->banner) }}" alt="{{ $product->name }}">
                </a>
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
                        <div class="d-flex gap-2">
                          <input type="text" id="username" name="username"
                            class="form-control"
                            style="background:#fbd3e2;border:none;border-radius:999px;height:48px;font-weight:600;color:#f187ab;"
                            placeholder="Username Roblox"
                            oninput="onUsernameInput()"
                            value="{{ old('username') }}">
                          <button type="button" class="btn btn-checkout" style="width:auto;padding:0 24px;border-radius:999px;font-weight:600;white-space:nowrap;" onclick="verifyUsername()" id="btnVerify">Cek</button>
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
          {{ $pm['name'] }}
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

@endsection
