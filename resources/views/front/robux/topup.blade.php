@extends('front.master')
@section('content')

<style>
:root{
  --primary:#f187ab; --primary-600:#eb6d96; --bg:#f7f8fb; --surface:#ffffff;
  --text:#2b2f38; --muted:#6b7280; --border:#e5e7eb; --ring: rgba(241,135,171,.25);
  --nav-h: 88px; --nav-gap: 20px;
}
body{ background:var(--bg) }
.container--narrow{ max-width:1080px; margin-top: calc(var(--nav-h) + var(--nav-gap)); }
@media (max-width: 991.98px){ :root{ --nav-h:100px; --nav-gap:16px } }
.text-pink{ color:var(--primary); font-weight:800; letter-spacing:.2px }
.rp{ color:var(--muted); margin-right:2px }
.price-num{ font-variant-numeric: tabular-nums; font-weight:800; color:var(--text) }
.pill{ display:inline-block; padding:4px 10px; border-radius:999px; background:#fff6fa; color:var(--primary); font-weight:800 }
.section-title{ display:flex; align-items:center; gap:10px; font-weight:800; color:var(--text); margin-bottom:14px; letter-spacing:.2px }
.step-badge{ inline-size:28px; block-size:28px; border-radius:999px; display:grid; place-items:center; background:var(--primary); color:#fff; font-weight:900; box-shadow:0 6px 16px rgba(241,135,171,.35) }
.card{ background:var(--surface); border:1px solid var(--border); border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,.06) }
.summary-card.sticky-top{ top: calc(var(--nav-h) + var(--nav-gap)) !important }
.summary-card .summary-row{ display:flex; justify-content:space-between; align-items:center; padding:10px 0; margin:0; border-bottom:1px dashed var(--border) }
.summary-card .summary-row:last-of-type{ border-bottom:none }
.summary-card .summary-row .label{ color:var(--muted); margin:0 }
.summary-card .summary-row.total .value{ font-size:1.4rem; color:var(--primary); font-weight:900 }
.form-control{ height:48px; border-radius:12px; border:1px solid var(--border); transition: box-shadow .15s, border-color .15s }
.form-control::placeholder{ color:#9aa3af }
.form-control:focus{ border-color:var(--primary); box-shadow:0 0 0 4px var(--ring); outline:0 }
input[type="range"]{ width:100%; height:6px; background:#e9edf3; border-radius:999px; outline:0; appearance:none }
input[type="range"]::-webkit-slider-thumb{ appearance:none; width:18px; height:18px; border-radius:50%; background:#fff; border:2px solid var(--primary); box-shadow:0 4px 10px rgba(0,0,0,.15); cursor:pointer }
.price-row{ margin:6px 0 0; font-weight:600; color:var(--text) }
.btn-pink{ background:var(--primary); color:#fff; border:none; padding:10px 20px; border-radius:12px; font-weight:800; box-shadow:0 10px 20px rgba(241,135,171,.35); transition: transform .06s, background .15s }
.btn-pink:hover{ background:var(--primary-600) }
.btn-pink:disabled{ background:#f187ab80; cursor:not-allowed; box-shadow:none }

/* Modal */
.modal-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.45); display:none; align-items:center; justify-content:center; z-index:1500 }
.modal-card{ width:min(720px, 94vw); max-height:92vh; overflow:auto; background:#fff; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.2); padding:20px }
.modal-header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:10px }
.modal-close{ background:transparent; border:none; font-size:20px; line-height:1; cursor:pointer }

.pill-input{
  height:44px; min-width:120px; padding:0 14px;
  border-radius:999px; border:1px solid var(--border);
  background:#fff6fa; color:var(--primary); font-weight:800;
  text-align:center; outline:none;
  transition:border-color .15s, box-shadow .15s;
}
.pill-input:focus{ border-color:var(--primary); box-shadow:0 0 0 4px var(--ring) }
/* Hilangkan spinner number di browser tertentu */
.pill-input::-webkit-outer-spin-button,
.pill-input::-webkit-inner-spin-button{ -webkit-appearance:none; margin:0 }
.pill-input[type=number]{ -moz-appearance:textfield }

/* Spinner */
.spinner{ width:34px; height:34px; border-radius:50%; border:3px solid #eee; border-top-color:var(--primary); animation: spin 1s linear infinite; margin:auto }
@keyframes spin{ to{ transform:rotate(360deg) } }

/* Video responsive: tinggi fleksibel, penuh lebar, tanpa area kosong samping */
.video-wrap{
  width:100%;
  height: clamp(220px, 55vh, 520px); /* <= perbaikan ukuran */
  border-radius:12px;
  overflow:hidden;
  background:#000;
}
.video-wrap > div,
.video-wrap iframe{ width:100%; height:100%; display:block }

/* Proof preview */
.preview{ margin-top:10px; display:none }
.preview img{ max-width:100%; border-radius:12px }
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
    <input type="hidden" name="gamepass_id" id="gamepass_id">
    <input type="hidden" name="gamepass_name" id="gamepass_name">

    <div class="row g-4">
      <div class="col-lg-7">
        {{-- Step 1 --}}
        <div class="card card-step p-4 mb-3">
          <div class="section-title"><span class="step-badge">1</span> Masukkan Data Akun</div>
          <label for="username" class="mb-1">Masukkan Username Roblox</label>
          <div class="d-flex gap-2">
            <input type="text" id="username" name="username" class="form-control" placeholder="Contoh: builderman" value="{{ old('username') }}" oninput="onUsernameInput()">
            <button type="button" class="btn btn-pink" onclick="verifyUsername()" id="btnVerify">Cek</button>
          </div>
          <div id="checkResult" class="mt-3 text-center"></div>
        </div>

        {{-- Step 2 --}}
        <div class="card card-step p-4 mb-3">
          <div class="section-title"><span class="step-badge">2</span> Pilih Nominal</div>
          <label for="robuxSlider">Pilih Jumlah Robux</label>
          <input type="range" min="50" max="5000" step="50"
                 value="{{ old('robux_amount', 50) }}"
                 id="robuxSlider" name="robux_amount" oninput="updatePrice()">
         <p class="mt-2 d-flex align-items-center gap-2 flex-wrap">
  <span>Robux:</span>

  <!-- INPUT BARU: tampil sebagai "badge pill" -->
  <input
    id="robuxPill"
    type="number"
    inputmode="numeric"
    class="pill pill-input"
    value="{{ old('robux_amount', 50) }}"
    aria-label="Jumlah Robux (ketik angka)"
  />

  <!-- SPAN LAMA: tetap ada tapi disembunyikan agar updatePrice() tidak error -->
  <span id="robuxAmount" class="pill" style="display:none">{{ old('robux_amount', 50) }}</span>
</p>
          <p class="price-row">Harga: <span class="rp">Rp</span> <span id="robuxPrice" class="price-num">7000</span></p>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card summary-card p-4 sticky-top">
          <h5 class="mb-3">Rincian Pembayaran</h5>
          <p class="summary-row"><span class="label">Harga Robux</span><span class="value"><span class="rp">Rp</span> <span id="priceOnly" class="price-num">7000</span></span></p>
          <p class="summary-row"><span class="label">Pajak</span><span class="value"><span class="rp">Rp</span> <span id="taxAmount" class="price-num">0</span></span></p>
          <p class="summary-row total"><span class="label">Total</span><span class="value"><span class="rp">Rp</span> <span id="totalPrice" class="price-num">7000</span></span></p>

          <button id="submitBtn" class="btn btn-lg btn-pink w-100 mt-3" type="button" onclick="openVideoGate()" disabled>Checkout</button>
          <small class="text-muted d-block mt-2">Tombol aktif setelah username <b>valid</b>.</small>
        </div>
      </div>
    </div>

    {{-- MODAL VIDEO (GATE) --}}
    <div id="videoModal" class="modal-backdrop">
      <div class="modal-card">
        <div class="modal-header">
          <h5 class="m-0">Panduan Singkat Pembayaran</h5>
          <button type="button" class="modal-close" aria-label="Close" onclick="closeVideoModal()">×</button>
        </div>
        <div class="video-wrap mb-3">
          <div id="ytPlayer"></div>
        </div>
        <small class="text-muted d-block mb-2 text-center">Tonton sampai selesai untuk melanjutkan.</small>
        <div class="d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-secondary" onclick="closeVideoModal()">Tutup</button>
          <button type="button" class="btn btn-pink" id="btnVideoProceed" onclick="proceedFromVideo()" disabled>Lanjut</button>
        </div>
      </div>
    </div>

    {{-- MODAL PENGECEKAN GAMEPASS --}}
    <div id="checkModal" class="modal-backdrop">
      <div class="modal-card">
        <div class="modal-header">
          <h5 class="m-0">Pengecekan Akun & Gamepass</h5>
          <button type="button" class="modal-close" aria-label="Close" onclick="closeCheckModal()">×</button>
        </div>

        <div id="checkModalBody" class="text-center">
          <div class="spinner"></div>
          <p class="mt-3 mb-0">Memvalidasi akun dan mencari gamepass yang sesuai...</p>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3" id="checkModalActions" style="display:none">
          <button type="button" class="btn btn-secondary" onclick="closeCheckModal()">Tutup</button>
          <button type="button" class="btn btn-pink" id="btnProceed" onclick="proceedToUpload()" disabled>Lanjut</button>
        </div>
      </div>
    </div>

    {{-- MODAL PEMBAYARAN & UPLOAD BUKTI (WA + metode pembayaran) --}}
    <div id="payModal" class="modal-backdrop">
      <div class="modal-card">
        <div class="modal-header">
          <h5 class="m-0">Pembayaran & Upload Bukti</h5>
          <button type="button" class="modal-close" aria-label="Close" onclick="closePayModal()">×</button>
        </div>

        <label for="wa" class="mt-1">No WhatsApp</label>
        <input type="text" id="wa" name="wa_number" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('wa_number') }}">

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
          <button type="submit" id="modalSubmitBtn" class="btn btn-pink" disabled>Submit</button>
        </div>
      </div>
    </div>
  </form>
</div>




<script>
const pricePer50 = {{ (int)($pricePer50 ?? 7000) }};
const csrfToken  = "{{ csrf_token() }}";
const tutorialVideoId = "{{ $tutorialVideoId ?? '4iPUSVZ0FrU' }}"; // ganti ID YouTube kamu

let isUserResolved = false;
let resolvedFor    = "";

/* ===== PRICE ===== */
function formatIDR(n){ return n.toLocaleString('id-ID'); }
function updatePrice() {
  const robux = +document.getElementById('robuxSlider').value;
  const method = document.getElementById('paymentMethod');
  const basePrice = (robux / 50) * pricePer50;
  const taxRate = parseFloat(method?.options[method.selectedIndex].dataset.tax || '0');
  const tax = Math.round(basePrice * (taxRate / 100));
  const total = basePrice + tax;

  document.getElementById('robuxAmount').innerText = robux;
  document.getElementById('robuxPrice').innerText  = formatIDR(basePrice);
  document.getElementById('priceOnly').innerText   = formatIDR(basePrice);
  document.getElementById('taxAmount').innerText   = formatIDR(tax);
  document.getElementById('totalPrice').innerText  = formatIDR(total);
}

/* ===== USERNAME ===== */
function onUsernameInput() {
  const uname = document.getElementById('username').value.trim();
  const canCheckout = isUserResolved && uname !== "" && uname.toLowerCase() === resolvedFor.toLowerCase();
  document.getElementById('submitBtn').disabled = !canCheckout;
  if (!canCheckout) document.getElementById('checkResult').innerHTML = '';
}

async function verifyUsername(){
  const uname = document.getElementById('username').value.trim();
  const resultDiv = document.getElementById('checkResult');
  const btn = document.getElementById('btnVerify');

  if (!uname){
    resultDiv.innerHTML = '<span class="text-danger fw-bold">Harap isi username Roblox.</span>';
    return;
  }

  btn.disabled = true;
  resultDiv.innerHTML = '<div class="spinner"></div><div class="mt-2 text-muted">Memeriksa username...</div>';

  try{
    const res = await fetch('{{ route('roblox.resolve') }}', {
      method:'POST',
      headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json' },
      body: JSON.stringify({ username: uname })
    });

    const data = await res.json();

    if (data.status){
      document.getElementById('roblox_user_id').value = data.userId;
      isUserResolved = true;
      resolvedFor    = data.username || uname;

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
  } catch (e){
    console.error(e);
    isUserResolved = false; resolvedFor = "";
    resultDiv.innerHTML = '<span class="text-danger fw-bold">Gagal menghubungi server.</span>';
  } finally{
    onUsernameInput();
    btn.disabled = false;
  }
}

/* ===== PAYMENT BOX ===== */
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
  navigator.clipboard.writeText(el.textContent.trim()).then(()=>alert('Nomor/rekening tujuan disalin!'));
}

/* ===== MODALS ===== */
function openModal(id){ document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden' }
function closeModal(id){ document.getElementById(id).style.display='none'; document.body.style.overflow='' }
function openVideoModal(){ openModal('videoModal'); }
function closeVideoModal(){
  closeModal('videoModal');
  if (window.ytPlayer && ytPlayer.stopVideo) ytPlayer.stopVideo();
}
function openCheckModal(){ openModal('checkModal'); }
function closeCheckModal(){ closeModal('checkModal'); }
function openPayModal(){ openModal('payModal'); onPaymentChange(); updatePrice(); }
function closePayModal(){ closeModal('payModal'); }

/* ===== VIDEO GATE FLOW ===== */
function openVideoGate(){
  const uname  = document.getElementById('username').value.trim();
  if (!(isUserResolved && uname.toLowerCase() === resolvedFor.toLowerCase())) {
    document.getElementById('checkResult').innerHTML = '<span class="text-danger fw-bold">Silakan cek username dulu sebelum checkout.</span>';
    return;
  }
  document.getElementById('btnVideoProceed').disabled = true; // lock until ended
  openVideoModal();
  loadYouTubeAPIOnce();
}
function proceedFromVideo(){
  closeVideoModal();
  startGamepassCheck();
}

/* ===== GAMEPASS CHECK ===== */
async function startGamepassCheck() {
  const uname  = document.getElementById('username').value.trim();
  const amount = +document.getElementById('robuxSlider').value;

  const modalBody    = document.getElementById('checkModalBody');
  const modalActions = document.getElementById('checkModalActions');
  const btnProceed   = document.getElementById('btnProceed');

  modalActions.style.display = 'none';
  btnProceed.disabled = true;
  modalBody.innerHTML = '<div class="spinner"></div><p class="mt-3 mb-0">Mencari gamepass yang sesuai...</p>';
  openCheckModal();

  try{
    const response = await fetch('{{ route('roblox.check') }}', {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrfToken, 'Accept':'application/json' },
      body: JSON.stringify({ username: uname, amount: amount })
    });
    const result = await response.json();

    if (result.status){
      const user = result.data;

      document.getElementById('roblox_user_id').value = user.userId;
      document.getElementById('gamepass_id').value    = user.gamepass.id;
      document.getElementById('gamepass_name').value  = user.gamepass.name;

      modalBody.innerHTML = `
        <div class="mb-2"><b>${user.displayName}</b> (@${user.username})</div>
        <div class="p-3" style="border:1px dashed var(--border); border-radius:12px;">
          <div class="fw-bold mb-1">Gamepass ditemukan</div>
          <div>Nama: <b>${user.gamepass.name}</b></div>
          <div>ID Gamepass: <code>${user.gamepass.id}</code></div>
          <div>Universe ID: <code>${user.gamepass.universeId ?? '-'}</code></div>
          <div>Harga: <b>${user.gamepass.price}</b> Robux</div>
          <div class="mt-2"><a href="https://www.roblox.com/game-pass/${user.gamepass.id}" target="_blank" rel="noopener">Lihat di Roblox</a></div>
        </div>
        <small class="text-muted d-block mt-2">Jika nominal diubah, lakukan checkout lagi untuk pencarian ulang.</small>
      `;
      modalActions.style.display = 'flex';
      btnProceed.disabled = false;
    } else {
      modalBody.innerHTML = `
        <p class="text-danger fw-bold mb-2">${result.message || 'Gamepass tidak ditemukan.'}</p>
        <small class="text-muted d-block">Coba ubah nominal Robux lalu checkout lagi.</small>
      `;
      modalActions.style.display = 'flex';
      btnProceed.disabled = true;
    }

  } catch (err){
    console.error(err);
    modalBody.innerHTML = `<p class="text-danger fw-bold mb-2">Gagal menghubungi server. Silakan coba lagi.</p>`;
    modalActions.style.display = 'flex';
    btnProceed.disabled = true;
  }
}

/* ===== PROCEED TO PAY ===== */
function proceedToUpload(){ closeCheckModal(); openPayModal(); }

/* ===== UPLOAD PREVIEW ===== */
document.addEventListener('change', function(e){
  if (e.target && e.target.id === 'payment_proof') {
    const file = e.target.files[0];
    const btn  = document.getElementById('modalSubmitBtn');
    const prev = document.getElementById('proofPreview');
    btn.disabled = !file;

    if (!file){ prev.innerHTML=''; prev.style.display='none'; return; }
    if (file.size > (5 * 1024 * 1024)) {
      alert('Ukuran file maksimal 5MB');
      e.target.value = ''; btn.disabled = true; prev.innerHTML=''; prev.style.display='none'; return;
    }
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = ev => { prev.innerHTML = `<img src="${ev.target.result}" alt="Preview bukti">`; prev.style.display='block'; };
      reader.readAsDataURL(file);
    } else {
      prev.innerHTML = `<div class="mt-2">File: <b>${file.name}</b></div>`; prev.style.display='block';
    }
  }
});

/* ===== YOUTUBE IFRAME API ===== */
let ytApiLoaded = false;
let ytPlayer = null;

function loadYouTubeAPIOnce(){
  if (ytApiLoaded) return;
  const tag = document.createElement('script');
  tag.src = "https://www.youtube.com/iframe_api";
  document.head.appendChild(tag);
  ytApiLoaded = true;
}
window.onYouTubeIframeAPIReady = function(){
  ytPlayer = new YT.Player('ytPlayer', {
    videoId: tutorialVideoId,
    playerVars: { rel:0, modestbranding:1, controls:1, playsinline:1 },
    events: {
      'onStateChange': function(e){
        if (e.data === YT.PlayerState.ENDED) {
          document.getElementById('btnVideoProceed').disabled = false; // enable setelah selesai
        }
      }
    }
  });
};

document.addEventListener('DOMContentLoaded', () => {
  updatePrice();
  onUsernameInput();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const slider = document.getElementById('robuxSlider');
  const pill   = document.getElementById('robuxPill'); // input pill yang kamu tambahkan
  if (!slider || !pill) return;

  // Nilai awal
  pill.value = slider.value;

  // SLIDER -> PILL (biar tetap sinkron saat digeser)
  slider.addEventListener('input', () => {
    pill.value = slider.value; // updatePrice sudah dipanggil via oninput di HTML slider
  });

  // Helper parse angka (biarkan user ketik bebas dulu)
  const parseDigits = (str) => {
    const m = String(str).match(/\d+/);
    return m ? parseInt(m[0], 10) : NaN;
  };

  // PILL -> SLIDER (tanpa clamp saat mengetik)
  pill.addEventListener('input', () => {
    const v = parseDigits(pill.value);
    if (Number.isNaN(v)) return;               // kosong/parsial: jangan ganggu proses ketik

    const min = +slider.min || 0;
    const max = +slider.max || 999999;

    // Hanya sinkronkan ke slider kalau SUDAH berada di rentang.
    // Ini mencegah "lompat" saat baru ketik 1, 13, 135 menuju 1350.
    if (v >= min && v <= max) {
      slider.value = v;       // tidak dibulatkan, biarkan apa adanya
      updatePrice();          // pakai logika harga milikmu
    }
  });

  // Saat blur, baru pastikan valid & sinkron total
  pill.addEventListener('blur', () => {
    let v = parseDigits(pill.value);
    const min = +slider.min || 0;
    const max = +slider.max || 999999;

    if (Number.isNaN(v)) v = min;
    v = Math.min(max, Math.max(min, v));

    slider.value = v;
    pill.value   = v;
    updatePrice();
  });
});
</script>

@endsection
