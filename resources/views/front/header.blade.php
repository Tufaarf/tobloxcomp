{{-- File: resources/views/front/header.blade.php --}}

<style>
    /* STYLE HEADER BARU YANG BERDIRI SENDIRI
      Menggunakan class '.custom-header' agar tidak bentrok dengan template.
    */

    /* Style Utama Header */
    .custom-header {
        background: #f187ab;
        padding: 15px 0; /* Diperbesar dari 12px */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1030;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: padding 0.3s ease;
    }

    /* Logo */
    .custom-header .logo img {
        max-height: 45px; /* Diperbesar dari 40px */
        transition: max-height 0.3s ease;
    }

    /* Navigasi */
    .custom-header .navigation ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        align-items: center;
    }

    .custom-header .navigation a {
        color: #ffffff;
        text-decoration: none;
        padding: 8px 16px;
        font-size: 16px;
        font-weight: 500;
        white-space: nowrap;
        border-radius: 6px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .custom-header .navigation a:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .custom-header .navigation a.active {
        color: #f187ab;
        background-color: #ffffff;
    }


    /* === Responsif untuk Mobile === */
    @media (max-width: 768px) {
        .custom-header {
            padding: 12px 0; /* Diperbesar dari 10px */
        }

        .custom-header .logo img {
            max-height: 35px; /* Diperbesar dari 32px */
        }

        .custom-header .navigation a {
            padding: 6px 8px;
            font-size: 14px;
        }
    }
</style>

<header class="custom-header">
    <div class="container-fluid container-xl d-flex justify-content-between align-items-center">

        <a href="{{ Route('front.index') }}" class="logo">
            <img src="{{ asset('assets/img/logo/logo1.png') }}" alt="Logo Mayoblox">
        </a>

        <nav class="navigation">
            <ul>
                <li><a href="{{ Route('front.index') }}" class="{{ Route::is('front.index') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ Route('robux.topup') }}" class="{{ Route::is('robux.topup') ? 'active' : '' }}">Robux</a></li>
                <li><a href="{{ Route('order.track') }}" class="{{ Route::is('order.track') ? 'active' : '' }}">Cek Transaksi</a></li>
            </ul>
        </nav>

    </div>
</header>
