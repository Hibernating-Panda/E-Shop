@extends('layouts.app')

@section('title', 'L192 Marketplace – Cambodia\'s #1 Online Store')

@section('styles')
<style>
    .hero-banner { border-radius: 8px; overflow: hidden; position: relative; height: 210px; display: flex; align-items: center; padding: 0 32px; transition: background 0.6s; }
    .hero-content { z-index: 1; flex: 1; }
    .hero-badge { display: inline-block; color: #fff; font-size: 10px; font-weight: 800; letter-spacing: 1.5px; padding: 3px 10px; border-radius: 3px; margin-bottom: 8px; }
    .hero-title { color: #fff; font-size: 26px; font-weight: 900; margin: 0 0 6px; line-height: 1.2; }
    .hero-sub { color: rgba(255,255,255,0.75); font-size: 13px; margin: 0 0 16px; }
    .hero-cta { color: #fff; border: none; padding: 9px 22px; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; }
    .hero-dots { position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); display: flex; gap: 5px; }
    .hero-dot { height: 6px; border-radius: 3px; background: rgba(255,255,255,0.38); cursor: pointer; transition: all 0.3s; border: none; }
    .hero-dot.active { width: 20px; background: #fff; }
    .hero-dot:not(.active) { width: 6px; }

    .page-wrap { max-width: 1200px; margin: 0 auto; padding: 14px 20px; }
    .above-fold { display: grid; grid-template-columns: 190px 1fr 175px; gap: 12px; margin-bottom: 14px; }

    /* Sidebar */
    .sidebar { background: #fff; border-radius: 8px; border: 1px solid #E8E8E8; overflow: hidden; }
    .sidebar-heading { padding: 9px 14px; border-bottom: 1px solid #E8E8E8; font-size: 11px; font-weight: 800; color: #757575; letter-spacing: 0.8px; }
    .sidebar-link { padding: 9px 14px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 8px; border-left: 3px solid transparent; transition: all 0.15s; text-decoration: none; color: #1A1A1A; }
    .sidebar-link:hover { background: #FFF0F1; color: #E8192C; border-left-color: #E8192C; }

    /* Brand grid */
    .brands-card { background: #fff; border-radius: 8px; border: 1px solid #E8E8E8; padding: 10px; flex: 1; }
    .brands-heading { font-size: 10px; font-weight: 800; color: #757575; margin-bottom: 8px; letter-spacing: 0.8px; }
    .brands-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 4px; }
    .brand-tile { border-radius: 5px; padding: 5px 2px; text-align: center; cursor: pointer; }
    .brand-tile span { color: #fff; font-size: 9px; font-weight: 800; }
    .flash-sale-banner { border-radius: 8px; padding: 12px; cursor: pointer; background: linear-gradient(135deg, #E8192C, #FF6B35); }

    /* Quick cats */
    .quick-cats { background: #fff; border-radius: 8px; border: 1px solid #E8E8E8; padding: 12px 16px; margin-bottom: 24px; }
    .quick-cats-inner { display: flex; gap: 0; overflow-x: auto; }
    .quick-cat { display: flex; flex-direction: column; align-items: center; gap: 5px; cursor: pointer; padding: 5px 14px; min-width: 76px; border-radius: 8px; text-decoration: none; transition: background 0.15s; }
    .quick-cat:hover { background: #FFF0F1; }
    .quick-cat-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .quick-cat span { font-size: 11px; color: #1A1A1A; font-weight: 500; white-space: nowrap; }

    /* Featured stores */
    .stores-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    .store-card { background: #fff; border-radius: 8px; border: 1px solid #E8E8E8; padding: 14px; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 8px; transition: box-shadow 0.2s; }
    .store-card:hover { box-shadow: 0 4px 14px rgba(232,25,44,0.1); }
    .store-avatar { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .store-name { font-size: 13px; font-weight: 700; color: #1A1A1A; text-align: center; }
    .store-meta { font-size: 11px; color: #757575; margin-top: 2px; }
    .store-visit-btn { background: #FFF0F1; color: #E8192C; border: none; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; cursor: pointer; }

    /* Partner stores */
    .partner-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .partner-card { border-radius: 8px; border: 1px solid #E8E8E8; padding: 14px 18px; cursor: pointer; display: flex; align-items: center; gap: 12px; }
    .partner-logo { width: 46px; height: 46px; background: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }

    /* Promo banners */
    .promo-banner { border-radius: 10px; padding: 20px 28px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
    .promo-btn-white { background: #fff; color: #E8192C; border: none; padding: 10px 24px; border-radius: 24px; font-size: 13px; font-weight: 800; cursor: pointer; }
    .promo-btn-gold  { background: #F6AD55; color: #fff; border: none; padding: 10px 24px; border-radius: 24px; font-size: 13px; font-weight: 800; cursor: pointer; }
    .countdown { display: flex; gap: 12px; }
    .countdown-box { text-align: center; background: rgba(255,255,255,0.12); padding: 8px 14px; border-radius: 8px; }
    .countdown-num { color: #fff; font-size: 22px; font-weight: 900; }
    .countdown-label { color: rgba(255,255,255,0.55); font-size: 10px; }

    /* Product grids */
    .product-grid-8 { display: grid; grid-template-columns: repeat(8, 1fr); gap: 10px; }
    .product-grid-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
    .card-img-compact { height: 110px; }
    .card-img-normal  { height: 160px; }
</style>
@endsection

@section('content')
<div class="page-wrap">

    {{-- Above the fold --}}
    <div class="above-fold">

        {{-- Sidebar categories --}}
        <div class="sidebar">
            <div class="sidebar-heading">☰ CATEGORY</div>
            @foreach($sidebarCategories as $cat)
                <a href="{{ route('category.index', ['category' => $cat['label']]) }}" class="sidebar-link">
                    <span>{{ $cat['icon'] }}</span> {{ $cat['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Hero Banner --}}
        <div class="hero-banner" id="heroBanner"
             style="background: {{ $heroSlides[0]['bg'] }}">
            <div class="hero-content">
                <div class="hero-badge" id="heroBadge"
                     style="background: {{ $heroSlides[0]['accent'] }}">{{ $heroSlides[0]['badge'] }}</div>
                <h2 class="hero-title" id="heroTitle">{{ $heroSlides[0]['title'] }}</h2>
                <p class="hero-sub" id="heroSub">{{ $heroSlides[0]['subtitle'] }}</p>
                <button class="hero-cta" id="heroCta"
                        style="background: {{ $heroSlides[0]['accent'] }}">{{ $heroSlides[0]['cta'] }} →</button>
            </div>
            <div style="position:absolute;right:20px;bottom:0;font-size:110px;opacity:0.12;">📱</div>
            <div class="hero-dots" id="heroDots">
                @foreach($heroSlides as $i => $slide)
                    <button class="hero-dot {{ $i === 0 ? 'active' : '' }}"
                            onclick="goSlide({{ $i }})"></button>
                @endforeach
            </div>
        </div>

        {{-- Brands + Flash Sale --}}
        <div style="display:flex;flex-direction:column;gap:10px;">
            <div class="brands-card">
                <div class="brands-heading">POPULAR BRANDS</div>
                <div class="brands-grid">
                    @foreach($brands as $brand)
                        <div class="brand-tile" style="background:{{ $brand['bg'] }}">
                            <span>{{ $brand['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flash-sale-banner">
                <div style="color:#fff;font-size:11px;font-weight:800;margin-bottom:2px;">🔥 FLASH SALE</div>
                <div style="color:rgba(255,255,255,0.82);font-size:10px;" id="flashCountdown">Ends in 02:45:12</div>
            </div>
        </div>
    </div>

    {{-- Quick categories --}}
    <div class="quick-cats">
        <div class="quick-cats-inner">
            @foreach($quickCats as $i => $cat)
                <a href="{{ route('category.index') }}" class="quick-cat">
                    <div class="quick-cat-icon" style="background:hsl({{ $i * 36 }}, 55%, 92%)">{{ $cat['icon'] }}</div>
                    <span>{{ $cat['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Featured Stores --}}
    <div style="margin-bottom:28px;">
        <div class="section-title">
            <h3>Featured Stores</h3>
        </div>
        <div class="stores-grid">
            @foreach($featuredStores as $i => $store)
                <div class="store-card">
                    <div class="store-avatar" style="background:hsl({{ $i * 70 + 180 }}, 50%, 90%)">{{ $store['logo'] }}</div>
                    <div>
                        <div class="store-name">
                            {{ $store['name'] }}
                            @if($store['verified'])<span style="margin-left:3px;color:#6C3EE8;font-size:11px;">✓</span>@endif
                        </div>
                        <div class="store-meta">⭐ {{ $store['rating'] }} · {{ number_format($store['items']) }} items</div>
                    </div>
                    <button class="store-visit-btn">Visit Store</button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Partner Stores --}}
    <div style="margin-bottom:28px;">
        <div class="section-title"><h3>Partner Stores</h3></div>
        <div class="partner-grid">
            @foreach($partnerStores as $store)
                <div class="partner-card" style="background:{{ $store['color'] }}">
                    <div class="partner-logo">{{ $store['logo'] }}</div>
                    <div>
                        <div style="font-weight:700;font-size:14px;">{{ $store['name'] }}</div>
                        <div style="font-size:12px;color:#757575;margin-top:2px;">{{ $store['discount'] }}</div>
                        <div style="font-size:11px;color:#E8192C;margin-top:4px;font-weight:600;">Browse →</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Flash Sale Promo --}}
    <div class="promo-banner" style="background:linear-gradient(135deg,#E8192C 0%,#FF6B35 100%);">
        <div>
            <div style="color:rgba(255,255,255,0.8);font-size:11px;font-weight:700;letter-spacing:1px;">LIMITED OFFER</div>
            <div style="color:#fff;font-size:22px;font-weight:900;margin:4px 0;">Flash Sale — Up to 50% Off</div>
            <div style="color:rgba(255,255,255,0.78);font-size:13px;">Electronics, Fashion, Home Essentials & more</div>
        </div>
        <a href="{{ route('category.index') }}"><button class="promo-btn-white">Shop Flash Sale</button></a>
    </div>

    {{-- Recommended --}}
    <div style="margin-bottom:28px;">
        <div class="section-title">
            <h3>You Might Want to Buy</h3>
            <a href="{{ route('category.index') }}">See all →</a>
        </div>
        <div class="product-grid-8">
            @foreach($recommended as $product)
                @include('components.product_card', ['product' => $product, 'compact' => true])
            @endforeach
        </div>
    </div>

    {{-- Trending --}}
    <div style="margin-bottom:28px;">
        <div class="section-title">
            <h3>Trending Now</h3>
            <a href="{{ route('category.index') }}">See all →</a>
        </div>
        <div class="product-grid-5">
            @foreach($trending as $product)
                @include('components.product_card', ['product' => $product, 'compact' => false])
            @endforeach
        </div>
    </div>

    {{-- Samsung Promo --}}
    <div class="promo-banner" style="background:linear-gradient(135deg,#1428A0 0%,#185FA5 100%);">
        <div>
            <div style="color:rgba(255,255,255,0.65);font-size:11px;font-weight:700;letter-spacing:1.2px;">NEW ARRIVAL</div>
            <div style="color:#fff;font-size:20px;font-weight:900;margin:4px 0;">Samsung Galaxy S25 Ultra</div>
            <div style="color:rgba(255,255,255,0.72);font-size:13px;">Starting from $899 · Official Store</div>
        </div>
        <div class="countdown">
            @foreach([['48','HRS'],['23','MIN'],['07','SEC']] as [$n, $l])
                <div class="countdown-box">
                    <div class="countdown-num">{{ $n }}</div>
                    <div class="countdown-label">{{ $l }}</div>
                </div>
            @endforeach
        </div>
        <button class="promo-btn-gold">Grab Deal</button>
    </div>

    {{-- More Products --}}
    <div style="margin-bottom:28px;">
        <div class="section-title">
            <h3>More Products</h3>
            <a href="{{ route('category.index') }}">See all →</a>
        </div>
        <div class="product-grid-5">
            @foreach($moreProducts as $product)
                @include('components.product_card', ['product' => $product, 'compact' => false])
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
const slides = @json($heroSlides);
let current = 0;

function goSlide(i) {
    current = i;
    const banner = document.getElementById('heroBanner');
    const slide  = slides[i];
    banner.style.background        = slide.bg;
    document.getElementById('heroBadge').style.background = slide.accent;
    document.getElementById('heroBadge').textContent      = slide.badge;
    document.getElementById('heroTitle').textContent      = slide.title;
    document.getElementById('heroSub').textContent        = slide.subtitle;
    document.getElementById('heroCta').textContent        = slide.cta + ' →';
    document.getElementById('heroCta').style.background   = slide.accent;
    document.querySelectorAll('.hero-dot').forEach((d, idx) => {
        d.classList.toggle('active', idx === i);
    });
}

setInterval(() => goSlide((current + 1) % slides.length), 3500);
</script>
@endsection