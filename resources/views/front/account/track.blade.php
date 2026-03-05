@extends('front.master')
@section('content')
@php
    // Map status for progress bar
    $statusMap = ['review'=>1, 'on_progress'=>2, 'delivered'=>3, 'rejected'=>4]; // Modified for Account Order statuses
    $current   = $order ? ($statusMap[$order->status] ?? 0) : 0;
@endphp

<style>
  :root{
    --primary:#f187ab; --primary-600:#eb6d96;
    --bg:#f7f8fb; --surface:#fff; --text:#2b2f38; --muted:#6b7280; --border:#e5e7eb;
    --ring: rgba(241,135,171,.25); --nav-h:88px; --nav-gap:20px;
    --success:#10b981; --danger:#ef4444;
  }
  body{ background:var(--bg); }
  .container--narrow{ max-width:980px; margin-top: calc(var(--nav-h) + var(--nav-gap)); }
  @media (max-width: 991.98px){ :root{ --nav-h:100px; --nav-gap:16px; } }

  .card{ background:var(--surface); border:1px solid var(--border); border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,.06); }
  .form-control{ height:48px; border-radius:12px; border:1px solid var(--border); }
  .btn-pink{ background:var(--primary); color:#fff; border:none; padding:10px 20px; border-radius:12px; font-weight:800; box-shadow:0 10px 20px rgba(241,135,171,.35); }
  .btn-pink:hover{ background:var(--primary-600) }

  .summary-row{ display:flex; justify-content:space-between; border-bottom:1px dashed var(--border); padding:10px 0; margin:0; }
  .summary-row:last-child{ border-bottom:none }

  /* timeline */
  .timeline{ display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
  .step{ display:flex; align-items:center; gap:8px; color:var(--muted); font-weight:600; }
  .step .dot{ width:14px; height:14px; border-radius:999px; background:#e5e7eb; }
  .step.done{ color:var(--success); }
  .step.done .dot{ background:var(--success); }
  .step.active{ color:var(--primary); }
  .step.active .dot{ background:var(--primary); }
  .pipe{ width:36px; height:2px; background:#e5e7eb; }
  .pipe.done{ background:var(--success); }
  .pipe.active{ background:var(--primary); }

  .badge{ display:inline-block; padding:6px 10px; border-radius:999px; font-weight:700; font-size:.85rem; }
  .badge-warning{ background:#fef3c7; color:#b45309; }
  .badge-primary{ background:#e9d5ff; color:#6b21a8; }
  .badge-info{ background:#cffafe; color:#0369a1; }
  .badge-success{ background:#dcfce7; color:#166534; }
  .badge-danger{ background:#fee2e2; color:#b91c1c; }

  /* notice / disclaimer */
  .notice{
    border:1px dashed var(--border);
    border-left:4px solid var(--primary);
    background:#fff6fa;
    color:#8b3a55;
    border-radius:12px;
    padding:12px 14px;
    font-weight:700;
  }
</style>
@include('front.header')
<div class="container container--narrow py-4">
  <h3 class="text-center mb-4" style="color:var(--primary); font-weight:800;">Cek Transaksi</h3>

  <div class="d-flex justify-content-center gap-2 mb-4">
    <a href="{{ route('order.track') }}" class="btn btn-outline-secondary" style="border-radius:99px; padding:10px 24px; border-color:var(--border); background:#fff;">Top Up Robux</a>
    <a href="{{ route('account.track') }}" class="btn btn-pink" style="border-radius:99px; padding:10px 24px;">Jual Beli Akun</a>
  </div>

  {{-- Form pencarian --}}
  <div class="card p-4 mb-4">
    <form method="GET" action="{{ route('account.track') }}">
      <label for="order_id" class="mb-2">Masukkan Order ID</label>
      <div class="d-flex gap-2">
        <input type="text" id="order_id" name="order_id" value="{{ $query }}" class="form-control" placeholder="Contoh: ACC-XXXXXX-DDMMYYYY" required>
        <button type="submit" class="btn btn-pink">Cek</button>
      </div>
      <small class="text-muted d-block mt-2">Order ID didapatkan setelah checkout sukses.</small>
    </form>
  </div>

  {{-- Hasil --}}
  @if($query !== '')
    @if(!$order)
      <div class="card p-4">
        <p class="mb-0" style="color:var(--danger)">Order tidak ditemukan. Pastikan Order ID benar.</p>
      </div>
    @else
      <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
          <div>
            <div style="font-size:1.1rem; font-weight:800;">Order ID: {{ $order->order_id }}</div>
            <div class="mt-1 text-muted">Dibuat: {{ $order->created_at->format('d M Y H:i') }}</div>
          </div>
          <div>
            @php
              $badge = match($order->status){
                'review'      => 'badge-warning',
                'on_progress' => 'badge-info',
                'delivered'   => 'badge-success',
                'rejected'    => 'badge-danger',
                default       => 'badge-warning'
              };
              $label = strtoupper(str_replace('_',' ', $order->status));
            @endphp
            <span class="badge {{ $badge }}">{{ $label }}</span>
          </div>
        </div>

        {{-- timeline --}}
        <div class="timeline mt-3">
          @php
            $steps = [
              ['key'=>'review','label'=>'Review'],
              ['key'=>'on_progress','label'=>'On Progress'],
              ['key'=>'delivered','label'=>'Delivered'],
            ];
          @endphp
          @foreach($steps as $i => $s)
            @php
              $idx = $statusMap[$s['key']];
              // If status is rejected, show everything as done or just stop? Assuming normal flow here.
              $isActive = ($idx === $current); 
              $isDone = ($idx < $current);
              
              $cls = $isDone ? 'done' : ($isActive ? 'active' : '');
            @endphp
            <div class="step {{ $cls }}">
              <span class="dot"></span><span>{{ $s['label'] }}</span>
            </div>
            @if($i < count($steps)-1)
              @php 
                $pipeCls = $isDone ? 'done' : ($isActive ? 'active' : '');
              @endphp
              <div class="pipe {{ $pipeCls }}"></div>
            @endif
          @endforeach
        </div>

        {{-- DISCLAIMER saat delivered --}}
        @if($order->status === 'delivered')
          <div class="notice mt-3">
            Akun telah dikirim! Cek WhatsApp anda.
          </div>
        @endif
      </div>

      {{-- Ringkasan --}}
      <div class="card p-4">
        <h5 class="mb-3" style="font-weight:800;">Ringkasan Pesanan</h5>
        <p class="summary-row"><span>Akun</span><span>{{ $order->account_name }}</span></p>
        <p class="summary-row"><span>Game</span><span>{{ $order->game ? $order->game->name : '-' }}</span></p>
        <p class="summary-row"><span>Pembeli</span><span>{{ $order->name }}</span></p>
        <p class="summary-row"><span>Total</span><span>Rp {{ number_format($order->total_price,0,',','.') }}</span></p>

        <div class="mt-3 d-flex flex-wrap gap-2">
          <a href="{{ url('/') }}" class="btn btn-pink">Kembali ke Beranda</a>
        </div>
      </div>
    @endif
  @endif
</div>

<script src="{{asset('js/main.js')}}?v={{ time() }}"></script>
@include('front.footer')
@endsection