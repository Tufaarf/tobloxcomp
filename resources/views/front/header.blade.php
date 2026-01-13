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

    /* Navigasi Desktop */
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

    /* Hamburger Menu Button (Hidden on Desktop) */
    .menu-toggle {
        display: none;
        flex-direction: column;
        justify-content: space-between;
        width: 30px;
        height: 21px;
        cursor: pointer;
        z-index: 1031;
    }

    .menu-toggle span {
        display: block;
        width: 100%;
        height: 3px;
        background: #ffffff;
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    /* === Responsif untuk Mobile === */
    @media (max-width: 768px) {
        .custom-header {
            padding: 20px 0;
        }

        .custom-header .logo img {
            max-height: 40px;
        }

        .menu-toggle {
            display: flex;
        }

        .custom-header .navigation {
            position: fixed;
            top: 0;
            right: -100%; /* Sembunyikan di kanan */
            width: 250px;
            height: 100vh;
            background: #f187ab;
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
            padding-top: 60px;
            transition: right 0.3s ease;
            z-index: 1029;
        }

        .custom-header .navigation.active {
            right: 0; /* Munculkan */
        }

        .custom-header .navigation ul {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        .custom-header .navigation ul li {
            width: 100%;
        }

        .custom-header .navigation a {
            display: block;
            padding: 15px 20px;
            font-size: 16px;
            width: 100%;
            border-radius: 0;
        }
        
        .custom-header .navigation a:hover, 
        .custom-header .navigation a.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* Animasi Hamburger saat aktif */
        .menu-toggle.active span:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }
        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        .menu-toggle.active span:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }
    }

    /* Extra Small Devices */
    @media (max-width: 576px) {
        .custom-header .logo img {
            max-height: 28px;
        }
    }
</style>

<header class="custom-header">
    <div class="container-fluid container-xl d-flex justify-content-between align-items-center">

        <a href="{{ Route('front.index') }}" class="logo">
            <img src="{{ asset('assets/img/logo/logo1.png') }}" alt="Logo Mayoblox">
        </a>

        <!-- Hamburger Menu Button -->
        <div class="menu-toggle" id="mobile-menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="navigation" id="main-navigation">
            <ul>
                <li><a href="{{ Route('front.index') }}" class="{{ Route::is('front.index') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ Route('robux.services') }}" class="{{ Route::is('robux.services', 'robux.topup') ? 'active' : '' }}">Robux</a></li>
                {{-- <li><a href="{{ Route('promo.index') }}" class="{{ Route::is('promo.index') ? 'active' : '' }}">Robux Promo</a></li> --}}
                <li><a href="{{ Route('front.items') }}" class="{{ Route::is('front.items', 'front.items.*') ? 'active' : '' }}">Item</a></li>
                <li><a href="{{ Route('order.track') }}" class="{{ Route::is('order.track') ? 'active' : '' }}">Cek Transaksi</a></li>
            </ul>
        </nav>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('mobile-menu-toggle');
        const navigation = document.getElementById('main-navigation');

        menuToggle.addEventListener('click', function() {
            menuToggle.classList.toggle('active');
            navigation.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navigation.contains(event.target) && !menuToggle.contains(event.target)) {
                menuToggle.classList.remove('active');
                navigation.classList.remove('active');
            }
        });
    });
</script>
