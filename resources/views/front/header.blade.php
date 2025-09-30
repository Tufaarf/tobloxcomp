<style>
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
</style>

<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center">
    <a href="{{Route('front.index')}}" class="logo d-flex align-items-center me-auto">
      <img src="{{asset('assets/img/logo/logo1.png')}}" alt="">
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="{{Route('front.index')}}" class="active">Home</a></li>
        <li><a href="{{Route('robux.topup')}}">Robux</a></li>
        <li><a href="{{Route('order.track')}}">Cek Transaksi</a></li>
      </ul>
      <!-- tambahkan aria agar bisa diakses -->
    </nav>
  </div>
</header>

