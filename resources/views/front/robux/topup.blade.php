@extends('front.master')
@section('content')

<style>
  /* ===== THEME ===== */
  :root{
    --primary:#f187ab;
    --primary-600:#eb6d96;
    --bg:#f7f8fb;
    --surface:#ffffff;
    --text:#2b2f38;
    --muted:#6b7280;
    --border:#e5e7eb;
    --ring: rgba(241,135,171,.25);
    --nav-h: 88px;
    --nav-gap: 20px;
  }
  body{ background:var(--bg); }
  .container--narrow{ max-width:1080px; margin-top: calc(var(--nav-h) + var(--nav-gap)); }
  @media (max-width: 991.98px){
    :root{ --nav-h: 100px; --nav-gap: 16px; }
    .container--narrow{ margin-top: calc(var(--nav-h) + var(--nav-gap)); }
  }

  .text-pink{ color:var(--primary); font-weight:800; letter-spacing:.2px }
  .rp{ color:var(--muted); margin-right:2px }
  .price-num{ font-variant-numeric: tabular-nums; font-weight:800; color:var(--text) }
  .pill{ display:inline-block; padding:4px 10px; border-radius:999px; background:#fff6fa; color:var(--primary); font-weight:800 }

  .section-title{ display:flex; align-items:center; gap:10px; font-weight:800; color:var(--text); margin-bottom:14px; letter-spacing:.2px; }
  .step-badge{ inline-size:28px; block-size:28px; border-radius:999px; display:grid; place-items:center; background:var(--primary); color:#fff; font-weight:900; box-shadow:0 6px 16px rgba(241,135,171,.35); }

  .card{ background:var(--surface); border:1px solid var(--border); border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,.06); }
  .summary-card.sticky-top{ top: calc(var(--nav-h) + var(--nav-gap)) !important; }
  .summary-card .summary-row{ display:flex; justify-content:space-between; align-items:center; padding:10px 0; margin:0; border-bottom:1px dashed var(--border); }
  .summary-card .summary-row:last-of-type{ border-bottom:none }
  .summary-card .summary-row .label{ color:var(--muted); margin:0 }
  .summary-card .summary-row.total .label{ font-weight:800; color:var(--text) }
  .summary-card .summary-row.total .value{ font-size:1.4rem; color:var(--primary); font-weight:900 }

  .form-control{ height:48px; border-radius:12px; border:1px solid var(--border); transition: box-shadow .15s, border-color .15s; }
  .form-control::placeholder{ color:#9aa3af }
  .form-control:focus{ border-color:var(--primary); box-shadow:0 0 0 4px var(--ring); outline:0; }

  select.form-control{
    appearance:none; background-image:
      linear-gradient(45deg, transparent 50%, #9aa3af 50%),
      linear-gradient(135deg, #9aa3af 50%, transparent 50%),
      linear-gradient(to right, #fff, #fff);
    background-position:
      calc(100% - 26px) calc(50% + 1px),
      calc(100% - 18px) calc(50% + 1px),
      100% 0;
    background-size: 8px 8px, 8px 8px, 2.5em 3.5em; background-repeat:no-repeat;
    padding-right:3rem;
  }

  input[type="range"]{ width:100%; height:6px; background:#e9edf3; border-radius:999px; outline:0; -webkit-appearance:none; appearance:none; }
  input[type="range"]::-webkit-slider-thumb{
    -webkit-appearance:none; appearance:none; width:18px; height:18px; border-radius:50%;
    background:#fff; border:2px solid var(--primary); box-shadow:0 4px 10px rgba(0,0,0,.15); cursor:pointer;
  }
  input[type="range"]::-moz-range-thumb{ width:18px; height:18px; border-radius:50%; background:#fff; border:2px solid var(--primary); cursor:pointer; }
  .price-row{ margin:6px 0 0; font-weight:600; color:var(--text) }

  .btn-pink{ background:var(--primary); color:#fff; border:none; padding:10px 20px; border-radius:12px; font-weight:800; box-shadow:0 10px 20px rgba(241,135,171,.35); transition: transform .06s, background .15s; }
  .btn-pink:hover{ background:var(--primary-600) }
  .btn-pink:active{ transform: translateY(1px) }

  /* Modal upload bukti */
 /* ===== Modal upload bukti (fix height) ===== */
.modal-backdrop{
  position:fixed; inset:0; background:rgba(0,0,0,.45);
  display:none; align-items:center; justify-content:center; z-index:1500;
}
/* kartu modal tidak boleh lebih tinggi dari viewport */
.modal-card{
  width:min(640px, 92vw);
  max-height:90vh;             /* <= batas tinggi */
  overflow:auto;               /* scroll jika konten lebih */
  background:#fff; border-radius:16px;
  box-shadow:0 20px 60px rgba(0,0,0,.2);
  padding:20px;
}
.modal-header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
.modal-close{ background:transparent; border:none; font-size:20px; line-height:1; cursor:pointer; }

/* area preview dibatasi tinggi */
.preview{
  margin-top:10px; display:none;
  border:1px solid var(--border); border-radius:12px; background:#fff;
  padding:8px;
}
.proof-img{
  display:block;
  width:100%;          /* skala lebar */
  height:auto;
  max-height:60vh;     /* <= batas tinggi preview */
  object-fit:contain;  /* menjaga proporsi */
}

/* layar kecil: beri batas sedikit lebih longgar */
@media (max-width: 576px){
  .modal-card{ width:94vw; max-height:92vh; }
  .proof-img{ max-height:55vh; }
}

</style>

@include('front.header')

<div class="container py-5 container--narrow">
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <h3 class="mb-4 text-center text-pink">Top Up Robux</h3>

  <form id="topupForm" method="POST" action="{{ route('topup.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="roblox_user_id" id="roblox_user_id">
    <input type="hidden" name="avatar_url" id="avatar_url">

    <div class="row g-4">
      <div class="col-lg-7">
        {{-- Step 1 --}}
        <div class="card card-step p-4 mb-3">
          <div class="section-title"><span class="step-badge">1</span> Masukkan Data Akun</div>
          <label>Masukkan Username Roblox</label>
          <input type="text" id="username" name="username" class="form-control" placeholder="Contoh: tufaarff" value="{{ old('username') }}">
          <button class="btn btn-pink mt-3" type="button" onclick="checkUsername()">Cek Username</button>
          <div id="userResult" class="mt-3 text-center"></div>
          <div id="expResult" class="mt-2 text-center text-muted"></div>
        </div>

        {{-- Step 2 --}}
        <div class="card card-step p-4 mb-3">
          <div class="section-title"><span class="step-badge">2</span> Pilih Nominal</div>
          <label>Pilih Jumlah Robux</label>
          <input type="range" min="50" max="5000" step="50"
                 value="{{ old('robux_amount', 50) }}"
                 id="robuxSlider" name="robux_amount" oninput="updatePrice()">
          <p class="mt-2">Robux: <span id="robuxAmount" class="pill">{{ old('robux_amount', 50) }}</span></p>
          <p class="price-row">Harga: <span class="rp">Rp</span> <span id="robuxPrice" class="price-num">7000</span></p>
        </div>

        {{-- Step 3 --}}
        <div class="card card-step p-4 mb-3">
          <div class="section-title"><span class="step-badge">3</span> Metode Pembayaran</div>
          <label>No WhatsApp</label>
          <input type="text" id="wa" name="wa_number" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('wa_number') }}">

          <label class="mt-3">Metode Pembayaran</label>
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
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card summary-card p-4 sticky-top">
          <h5 class="mb-3">Rincian Pembayaran</h5>
          <p class="summary-row"><span class="label">Harga Robux</span><span class="value"><span class="rp">Rp</span> <span id="priceOnly" class="price-num">7000</span></span></p>
          <p class="summary-row"><span class="label">Pajak</span><span class="value"><span class="rp">Rp</span> <span id="taxAmount" class="price-num">210</span></span></p>
          <p class="summary-row total"><span class="label">Total</span><span class="value"><span class="rp">Rp</span> <span id="totalPrice" class="price-num">7210</span></span></p>

          <button id="submitBtn" class="btn btn-lg btn-pink w-100 mt-3" type="button" onclick="openPayModal()" disabled>Checkout</button>
          <small class="text-muted d-block mt-2">Tombol aktif setelah akun & experience terverifikasi.</small>
        </div>
      </div>
    </div>

    {{-- MODAL UPLOAD BUKTI PEMBAYARAN --}}
    <div id="payModal" class="modal-backdrop">
      <div class="modal-card">
        <div class="modal-header">
          <h5 class="m-0">Konfirmasi Pembayaran</h5>
          <button type="button" class="modal-close" aria-label="Close" onclick="closePayModal()">×</button>
        </div>
        <p class="mb-2">Upload <b>bukti pembayaran</b> (JPG/PNG/PDF, maks 5MB).</p>
        <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*,application/pdf" required>
        <div id="proofPreview" class="preview"></div>

        <div class="d-flex gap-2 justify-content-end mt-3">
          <button type="button" class="btn btn-secondary" onclick="closePayModal()">Batal</button>
          <button type="submit" id="modalSubmitBtn" class="btn btn-pink" disabled>Submit</button>
        </div>
      </div>
    </div>
    {{-- /MODAL --}}
  </form>
</div>

<script>
  const pricePer50 = {{ (int)($pricePer50 ?? 7000) }};
  let experienceOK = false;

  function updatePrice() {
    const robux = +document.getElementById('robuxSlider').value;
    const basePrice = (robux / 50) * pricePer50;
    const method = document.getElementById('paymentMethod');
    const taxRate = parseFloat(method.options[method.selectedIndex].dataset.tax || '0');
    const tax = Math.round(basePrice * (taxRate / 100));
    const total = basePrice + tax;

    document.getElementById('robuxAmount').innerText = robux;
    document.getElementById('robuxPrice').innerText = basePrice.toLocaleString('id-ID');
    document.getElementById('priceOnly').innerText = basePrice.toLocaleString('id-ID');
    document.getElementById('taxAmount').innerText = tax.toLocaleString('id-ID');
    document.getElementById('totalPrice').innerText = total.toLocaleString('id-ID');
  }

  function onPaymentChange() {
    const sel = document.getElementById('paymentMethod');
    const opt = sel.options[sel.selectedIndex];
    const type = opt.dataset.type;
    const target = opt.dataset.target;
    const name = opt.dataset.name;
    const box = document.getElementById('payInfo');

    if (type === 'image') {
      box.innerHTML = `
        <div class="text-muted mb-2">Scan QR <b>${name}</b> di bawah ini:</div>
        <img src="${target}" alt="QR ${name}" style="max-width:100%;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,.08)">
      `;
    } else {
      box.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Tujuan <b>${name}</b></div>
            <div class="fw-bold" id="payTarget" style="font-size:1.05rem">${target}</div>
          </div>
          <button class="btn btn-sm btn-pink" type="button" onclick="copyPayTarget()">Copy</button>
        </div>
      `;
    }
  }
  function copyPayTarget(){
    const el = document.getElementById('payTarget');
    if (!el) return;
    navigator.clipboard.writeText(el.textContent.trim()).then(()=>alert('Disalin'));
  }

  async function checkUsername() {
    const uname = document.getElementById('username').value.trim();
    const resDiv = document.getElementById('userResult');
    const expDiv = document.getElementById('expResult');
    const btn = document.getElementById('submitBtn');

    btn.disabled = true; expDiv.innerHTML = '';
    if (!uname) { resDiv.innerHTML = '<span class="text-danger">Isi username.</span>'; return; }

    resDiv.innerHTML = 'Mencari username...';

    try {
      const r = await fetch('{{ route('roblox.resolve') }}', {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
        body: JSON.stringify({ username: uname })
      });
      const j = await r.json();
      if (!j.status) { resDiv.innerHTML = `<span class="text-danger">${j.message}</span>`; return; }

      resDiv.innerHTML = `
        <img src="${j.avatar ?? ''}" width="100" height="100" style="border-radius:12px">
        <p class="mt-2 fw-bold">${j.username}</p>
      `;
      document.getElementById('roblox_user_id').value = String(j.userId);
      document.getElementById('avatar_url').value = j.avatar || '';

      expDiv.innerHTML = 'Memeriksa experience...';
      const e = await fetch('{{ route('roblox.experience', ['userId' => 'USERID']) }}'.replace('USERID', j.userId));
      const ej = await e.json();
      if (ej.status) {
        expDiv.innerHTML = '<span class="text-success">Experience ditemukan. Anda bisa melanjutkan.</span>';
        experienceOK = true; btn.disabled = false;
      } else {
        expDiv.innerHTML = `<span class="text-danger">${ej.message ?? 'Akun belum punya experience.'}</span>`;
        experienceOK = false; btn.disabled = true;
      }
    } catch (err) {
      resDiv.innerHTML = '<span class="text-danger">Gagal menghubungi server.</span>';
    }
  }

  // Modal upload
  function openPayModal(){
    if (!experienceOK) { alert('Akun & experience belum terverifikasi.'); return; }
    document.getElementById('payModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
  function closePayModal(){
    document.getElementById('payModal').style.display = 'none';
    document.body.style.overflow = '';
  }
  document.addEventListener('change', function(e){
    if (e.target && e.target.id === 'payment_proof') {
      const file = e.target.files[0];
      const btn  = document.getElementById('modalSubmitBtn');
      const prev = document.getElementById('proofPreview');
      btn.disabled = !file;
      if (file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = ev => {
                prev.innerHTML = `<img src="${ev.target.result}" alt="Preview bukti" class="proof-img">`; // <-- pakai class
                prev.style.display = 'block';
            };
            reader.readAsDataURL(file);
            } else {
            prev.innerHTML = `<div class="mt-2">File: <b>${file.name}</b></div>`;
            prev.style.display = 'block';
            }

      } else {
        prev.innerHTML = '';
        prev.style.display = 'none';
      }
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    updatePrice();
    onPaymentChange();
  });
</script>
@endsection
