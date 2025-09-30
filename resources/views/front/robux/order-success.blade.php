@extends('front.master')
@section('content')
<style>
  :root{ --primary:#f187ab; --bg:#f7f8fb; --surface:#fff; --border:#e5e7eb; }
  body{ background:var(--bg); }
  .center-wrap{ min-height: calc(100vh - 120px); display:flex; align-items:center; justify-content:center; }
  .modal-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.45); display:flex; align-items:center; justify-content:center; z-index:1500; }
  .modal-card{ width:min(560px,92vw); background:#fff; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.2); padding:22px; }
  .modal-header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
  .btn{ padding:10px 16px; border-radius:12px; border:1px solid var(--border); background:#fff; }
  .btn-primary{ background:var(--primary); color:#fff; border:none; }
  .btn-row{ display:flex; gap:10px; justify-content:flex-end; margin-top:14px; }
  .order-box{ border:1px dashed var(--border); border-radius:12px; padding:12px; display:flex; gap:8px; align-items:center; }
  .order-id{ font-weight:800; letter-spacing:.4px; }
  .img-preview{ margin-top:12px; border:1px solid var(--border); border-radius:12px; padding:8px; background:#fff }
</style>

<div class="center-wrap">
  <div class="modal-backdrop" id="successBackdrop">
    <div class="modal-card">
      <div class="modal-header">
        <h5 class="m-0">Pesanan Berhasil</h5>
      </div>

      <p class="mb-2">Simpan <b>Order ID</b> untuk melacak status pesanan Anda.</p>
      <div class="order-box">
        <span>Order ID:</span>
        <span class="order-id" id="orderIdText">{{ $orderId }}</span>
        <button class="btn" type="button" onclick="copyId()">Copy</button>
      </div>

      <div class="img-preview">
        <p class="mb-2">Unduh <b>invoice</b> sebagai gambar (PNG) untuk arsip Anda.</p>
        @if($invoiceUrl)
          <img src="{{ $invoiceUrl }}" alt="Invoice" style="max-width:100%; border-radius:10px;">
          <div class="btn-row">
            <a class="btn" href="{{ $invoiceUrl }}" download>Download Invoice</a>
          </div>
        @else
          <p>Invoice otomatis tidak tersedia. Anda bisa mencetak halaman ini sebagai bukti.</p>
        @endif
      </div>

      <div class="btn-row">
        <button class="btn-primary" type="button" onclick="goHome()">Lanjut ke Beranda</button>
      </div>
    </div>
  </div>
</div>

<script>
  function copyId(){
    const t = document.getElementById('orderIdText').innerText.trim();
    navigator.clipboard.writeText(t).then(()=>alert('Order ID disalin'));
  }
  function goHome(){
    window.location.href = @json($redirectTo ?? url('/'));
  }
</script>
@endsection
