<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>L192 Marketplace – @yield('title', 'Shop Online Cambodia')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #F5F5F5; color: #1A1A1A; }
        a { text-decoration: none; color: inherit; }
        button { font-family: inherit; }

        /* ── Navbar ── */
        .navbar {
            background: linear-gradient(135deg, #2D0A7A 0%, #6C3EE8 50%, #9B59E8 100%);
            position: sticky; top: 0; z-index: 200;
            box-shadow: 0 2px 12px rgba(108,62,232,0.35);
        }
        .navbar-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; gap: 16px; padding: 10px 20px;
        }
        .navbar-logo {
            display: flex; align-items: center; gap: 8px; cursor: pointer; flex: 0 0 auto;
        }
        .navbar-logo-icon {
            width: 36px; height: 36px; background: #E8192C; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff; font-weight: 900;
        }
        .navbar-logo-text { color: #fff; font-weight: 900; font-size: 22px; letter-spacing: -1px; }
        .navbar-search { flex: 1; position: relative; max-width: 680px; }
        .navbar-search input {
            width: 100%; height: 38px; background: #fff; border: none;
            border-radius: 4px; padding: 0 50px 0 16px;
            font-size: 13px; outline: none; color: #1A1A1A;
        }
        .navbar-search button {
            position: absolute; right: 0; top: 0; height: 38px; width: 46px;
            background: #E8192C; border: none; border-radius: 0 4px 4px 0;
            cursor: pointer; font-size: 18px; color: #fff;
        }
        .navbar-actions { display: flex; align-items: center; gap: 20px; flex: 0 0 auto; }
        .navbar-action {
            color: rgba(255,255,255,0.85); font-size: 12px;
            cursor: pointer; text-align: center; text-decoration: none;
            display: flex; flex-direction: column; align-items: center; gap: 2px;
            position: relative;
        }
        .navbar-action span { font-size: 20px; }
        .cart-badge {
            position: absolute; top: -4px; right: -8px;
            background: #E8192C; color: #fff;
            width: 18px; height: 18px; border-radius: 9px;
            font-size: 10px; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
        }
        .navbar-cats {
            border-top: 1px solid rgba(255,255,255,0.12);
            background: rgba(0,0,0,0.15);
        }
        .navbar-cats-inner {
            max-width: 1200px; margin: 0 auto;
            padding: 0 20px; display: flex;
        }
        .navbar-cat {
            padding: 9px 16px; font-size: 12px; font-weight: 600;
            color: rgba(255,255,255,0.8); cursor: pointer;
            transition: all 0.15s; white-space: nowrap; text-decoration: none;
        }
        .navbar-cat:hover { color: #fff; background: rgba(255,255,255,0.15); }

        /* ── Footer ── */
        .footer { background: #1A1D2E; margin-top: 48px; padding: 36px 20px 20px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 36px; margin-bottom: 28px;
        }
        .footer-logo { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
        .footer-logo-icon {
            width: 36px; height: 36px; background: #E8192C; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff; font-weight: 900;
        }
        .footer-logo-text { color: #fff; font-weight: 900; font-size: 20px; }
        .footer-desc { color: rgba(255,255,255,0.45); font-size: 13px; line-height: 1.7; margin-bottom: 14px; }
        .footer-apps { display: flex; gap: 8px; }
        .footer-app-btn {
            background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.65);
            border: 1px solid rgba(255,255,255,0.12);
            padding: 6px 12px; border-radius: 6px; font-size: 11px; cursor: pointer;
        }
        .footer-col-title {
            color: rgba(255,255,255,0.35); font-size: 10px;
            font-weight: 800; letter-spacing: 1.2px; margin-bottom: 12px;
        }
        .footer-col-link {
            color: rgba(255,255,255,0.6); font-size: 13px;
            margin-bottom: 8px; cursor: pointer; display: block;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.07);
            padding-top: 16px; display: flex;
            justify-content: space-between; align-items: center;
        }
        .footer-copy { color: rgba(255,255,255,0.25); font-size: 12px; }
        .footer-legal { display: flex; gap: 16px; }
        .footer-legal a { color: rgba(255,255,255,0.25); font-size: 12px; }

        /* ── Breadcrumb ── */
        .breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: #757575; margin-bottom: 16px;
        }
        .breadcrumb-sep { color: #ABABAB; }
        .breadcrumb-link { color: #E8192C; }
        .breadcrumb-current { color: #1A1A1A; font-weight: 600; }

        /* ── Product Cards ── */
        .product-card {
            background: #fff; border-radius: 8px; border: 1px solid #E8E8E8;
            padding: 8px; cursor: pointer; text-decoration: none;
            display: block; transition: all 0.2s; position: relative;
        }
        .product-card:hover {
            transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-color: #E8192C;
        }
        .card-img {
            border-radius: 6px; display: flex; align-items: center;
            justify-content: center; font-size: 48px; color: #1A1A1A;
            margin-bottom: 8px; position: relative; overflow: hidden;
        }
        .card-img-compact { height: 110px; font-size: 36px; }
        .card-img-normal { height: 160px; font-size: 48px; }
        .card-body { padding: 4px 0; }
        .card-title {
            font-size: 13px; font-weight: 600; color: #1A1A1A;
            margin-bottom: 4px; line-height: 1.3;
        }
        .card-price {
            color: #E8192C; font-weight: 800;
        }
        .in-stock {
            color: #10B981; font-size: 10px; font-weight: 800;
            margin-bottom: 2px;
        }
        .out-of-stock-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.9); display: flex;
            align-items: center; justify-content: center;
            font-size: 12px; font-weight: 800; color: #757575;
            z-index: 1;
        }
        .badge {
            background: #E8192C; color: #fff; font-size: 10px;
            font-weight: 800; padding: 2px 6px; border-radius: 3px;
        }

        /* ── Section Titles ── */
        .section-title {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 14px;
        }
        .section-title h3 {
            font-size: 18px; font-weight: 900; color: #1A1A1A;
            margin: 0;
        }
        .section-title a {
            color: #E8192C; font-size: 13px; font-weight: 700;
            text-decoration: none;
        }

        /* ── Utilities ── */
        .container { max-width: 1200px; margin: 0 auto; padding: 14px 20px; }
        .btn-primary {
            background: #E8192C; color: #fff; border: none;
            border-radius: 8px; font-weight: 800; cursor: pointer;
            font-family: inherit; padding: 12px 24px;
        }
        .btn-outline {
            background: transparent; color: #E8192C;
            border: 2px solid #E8192C; border-radius: 8px;
            font-weight: 800; cursor: pointer; font-family: inherit;
            padding: 10px 24px;
        }
        .card {
            background: #fff; border-radius: 8px;
            border: 1px solid #E8E8E8;
        }

        @yield('styles')
    </style>
    @stack('styles')
</head>
<body>

{{-- ── NAVBAR ── --}}
<header class="navbar">
    <div class="navbar-inner">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="navbar-logo">
            <div class="navbar-logo-icon">L</div>
            <span class="navbar-logo-text">192</span>
        </a>

        {{-- Search --}}
        <form action="{{ route('category.index') }}" method="GET" class="navbar-search">
            <input type="text" name="q" placeholder="Search products, stores, brands..."
                   value="{{ request('q') }}">
            <button type="submit">🔍</button>
        </form>

        {{-- Right actions --}}
        <div class="navbar-actions">
            <a href="{{ route('profile') }}" class="navbar-action">
                <span>👤</span> Account
            </a>
            <a href="{{ route('cart.index') }}" class="navbar-action">
                <span>🛒</span> Cart
                @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                @if($cartCount > 0)
                    <div class="cart-badge">{{ $cartCount }}</div>
                @endif
            </a>
            <a href="{{ route('orders.index') }}" class="navbar-action">
                <span>📦</span> Orders
            </a>
        </div>
    </div>

    {{-- Category nav --}}
    <nav class="navbar-cats">
        <div class="navbar-cats-inner">
            @foreach(['Electronics','Fashion','Beauty','Home & Living','Baby & Toys','Sports','Automotive','Books'] as $cat)
                <a href="{{ route('category.index', ['cat' => Str::slug($cat)]) }}" class="navbar-cat">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </nav>
</header>

{{-- ── MAIN CONTENT ── --}}
<main>
    @yield('content')
</main>

{{-- ── FOOTER ── --}}
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    <div class="footer-logo-icon">L</div>
                    <span class="footer-logo-text">L192 Marketplace</span>
                </div>
                <p class="footer-desc">Cambodia's leading marketplace. Buy and sell with confidence — millions of products, verified sellers, secure payments.</p>
                <div class="footer-apps">
                    <button class="footer-app-btn">📱 App Store</button>
                    <button class="footer-app-btn">🤖 Google Play</button>
                </div>
            </div>
            @foreach([
                ['ABOUT', ['About L192','Careers','Press','Investors']],
                ['CUSTOMER SERVICE', ['Help Center','Returns','Track Order','Contact Us']],
                ['CONNECT', ['Facebook','Instagram','Twitter','YouTube']],
            ] as [$title, $links])
                <div>
                    <div class="footer-col-title">{{ $title }}</div>
                    @foreach($links as $link)
                        <a href="#" class="footer-col-link">{{ $link }}</a>
                    @endforeach
                </div>
            @endforeach
        </div>
        <div class="footer-bottom">
            <span class="footer-copy">© {{ date('Y') }} L192 Marketplace. All rights reserved.</span>
            <div class="footer-legal">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Cookies</a>
            </div>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>