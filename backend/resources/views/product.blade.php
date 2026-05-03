@extends('layouts.app')

@section('title', $product['title'] . ' – L192')

@section('styles')
<style>
    .page-wrap { max-width: 1200px; margin: 0 auto; padding: 14px 20px; }
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; margin-bottom: 32px; }

    /* Images */
    .main-img { width: 100%; height: 400px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 100px; margin-bottom: 12px; border: 1px solid #E8E8E8; }
    .thumb-row { display: flex; gap: 10px; }
    .thumb { width: 72px; height: 72px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 32px; cursor: pointer; border: 2px solid #E8E8E8; transition: border 0.15s; }
    .thumb.active { border-color: #E8192C; }

    /* Info */
    .in-stock-label { color: #10B981; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 6px; }
    .product-title  { font-size: 20px; font-weight: 800; color: #1A1A1A; margin: 0 0 8px; line-height: 1.3; }
    .rating-row     { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; padding-bottom: 14px; border-bottom: 1px solid #E8E8E8; }
    .price-row      { display: flex; align-items: baseline; gap: 10px; margin-bottom: 16px; }
    .price-main     { color: #E8192C; font-size: 30px; font-weight: 900; }
    .price-original { color: #ABABAB; font-size: 16px; text-decoration: line-through; }
    .price-badge    { background: #E8192C; color: #fff; font-size: 12px; font-weight: 800; padding: 3px 8px; border-radius: 4px; }
    .savings        { font-size: 12px; color: #10B981; margin-top: 3px; }

    /* Options */
    .option-label { font-size: 13px; font-weight: 700; margin-bottom: 8px; }
    .color-swatch { width: 28px; height: 28px; border-radius: 50%; cursor: pointer; outline: 1px solid #E8E8E8; border: 3px solid transparent; transition: border 0.15s; }
    .color-swatch.active { border-color: #E8192C; }
    .size-chip { padding: 6px 14px; border-radius: 4px; border: 1.5px solid #E8E8E8; background: #fff; color: #1A1A1A; font-size: 12px; cursor: pointer; font-weight: 400; transition: all 0.15s; }
    .size-chip.active { border-color: #E8192C; background: #FFF0F1; color: #E8192C; font-weight: 700; }
    .qty-ctrl { display: flex; align-items: center; border: 1px solid #E8E8E8; border-radius: 6px; overflow: hidden; }
    .qty-btn  { width: 36px; height: 36px; border: none; background: #F5F5F5; cursor: pointer; font-size: 16px; font-weight: 700; }
    .qty-num  { width: 44px; text-align: center; font-size: 14px; font-weight: 700; border-left: 1px solid #E8E8E8; border-right: 1px solid #E8E8E8; }

    /* Actions */
    .action-row { display: flex; gap: 12px; margin-bottom: 20px; }
    .btn-add-cart { flex: 1; padding: 13px 0; background: #E8192C; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; transition: background 0.2s; }
    .btn-add-cart.added { background: #10B981; }
    .btn-buy-now  { flex: 1; padding: 13px 0; background: transparent; color: #E8192C; border: 2px solid #E8192C; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; }

    /* Store card */
    .store-card-mini { background: #FAFAFA; border-radius: 8px; border: 1px solid #E8E8E8; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; }

    /* Shipping badges */
    .shipping-row { display: flex; gap: 16px; margin-top: 14px; }
    .shipping-item { flex: 1; text-align: center; }

    /* Tabs */
    .tabs-nav { display: flex; border-bottom: 1px solid #E8E8E8; }
    .tab-btn  { padding: 14px 24px; font-size: 13px; font-weight: 700; cursor: pointer; color: #757575; border-bottom: 2px solid transparent; text-transform: capitalize; transition: all 0.15s; background: none; border-top: none; border-left: none; border-right: none; }
    .tab-btn.active { color: #E8192C; border-bottom-color: #E8192C; }
    .tab-content { padding: 20px 24px; }

    /* Reviews */
    .review-item { border-top: 1px solid #E8E8E8; padding-top: 14px; margin-top: 14px; }
    .reviewer-avatar { width: 34px; height: 34px; border-radius: 50%; background: #E8F4FD; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #185FA5; }

    /* Related */
    .related-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
</style>
@endsection

@section('content')
<div class="page-wrap">

    @include('components.breadcrumb', ['items' => [
        ['label' => 'Home',                    'url' => route('home')],
        ['label' => $product['category'],      'url' => route('category.index', ['sub' => $product['category']])],
        ['label' => substr($product['title'],0,30).'...', 'url' => null],
    ]])

    <div class="detail-grid">

        {{-- Images --}}
        <div>
            <div class="main-img" id="mainImg"
                 style="background:hsl({{ $product['images'][0]['hue'] }}, 38%, 88%)">
                <span id="mainEmoji">{{ $product['images'][0]['emoji'] }}</span>
            </div>
            <div class="thumb-row">
                @foreach($product['images'] as $i => $img)
                    <div class="thumb {{ $i === 0 ? 'active' : '' }}"
                         style="background:hsl({{ $img['hue'] }}, 38%, 88%)"
                         onclick="selectImg({{ $i }}, '{{ $img['hue'] }}', '{{ $img['emoji'] }}', this)">
                        {{ $img['emoji'] }}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Product Info --}}
        <div>
            @if($product['inStock'])
                <div class="in-stock-label">✓ IN STOCK</div>
            @endif
            <h1 class="product-title">{{ $product['title'] }}</h1>

            <div class="rating-row">
                <span style="color:#EAB308;font-size:14px;">{{ str_repeat('★', round($product['rating'])) }}</span>
                <span style="font-size:13px;color:#757575;">{{ $product['rating'] }} rating · {{ number_format($product['sold']) }} sold</span>
                <span style="font-size:12px;color:#757575;">📍 {{ $product['location'] }}</span>
            </div>

            <div class="price-row">
                <span class="price-main">${{ number_format($product['price'], 2) }}</span>
                @if($product['originalPrice'])
                    <span class="price-original">${{ $product['originalPrice'] }}</span>
                    <span class="price-badge">-{{ $product['discount'] }}</span>
                @endif
            </div>
            @if($product['originalPrice'])
                <div class="savings">You save ${{ number_format($product['originalPrice'] - $product['price'], 2) }}</div>
            @endif

            {{-- Color --}}
            <div style="margin-bottom:16px;">
                <div class="option-label">Color: <span id="colorName" style="font-weight:400;color:#757575;">{{ $colors[0]['name'] }}</span></div>
                <div style="display:flex;gap:8px;">
                    @foreach($colors as $i => $color)
                        <div class="color-swatch {{ $i === 0 ? 'active' : '' }}"
                             style="background:{{ $color['hex'] }}"
                             title="{{ $color['name'] }}"
                             onclick="selectColor(this, '{{ $color['name'] }}')"></div>
                    @endforeach
                </div>
            </div>

            {{-- Size --}}
            <div style="margin-bottom:20px;">
                <div class="option-label">Size (EU): <span id="selectedSize" style="font-weight:400;color:#757575;">Select size</span></div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach(['36','37','38','39','40','41','42','43','44'] as $size)
                        <button type="button" class="size-chip"
                                onclick="selectSize(this, '{{ $size }}')">{{ $size }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Quantity --}}
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                <span style="font-size:13px;font-weight:700;">Quantity:</span>
                <div class="qty-ctrl">
                    <button class="qty-btn" onclick="changeQty(-1)">−</button>
                    <div class="qty-num" id="qty">1</div>
                    <button class="qty-btn" onclick="changeQty(1)">+</button>
                </div>
                <span style="font-size:12px;color:#757575;">
                    Total: <strong style="color:#E8192C;" id="totalPrice">${{ number_format($product['price'], 2) }}</strong>
                </span>
            </div>

            {{-- Action buttons --}}
            <div class="action-row">
                <form action="{{ route('cart.add') }}" method="POST" id="addCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                    <input type="hidden" name="qty" id="qtyInput" value="1">
                    <button type="submit" class="btn-add-cart" id="addCartBtn">🛒 Add to Cart</button>
                </form>
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                    <input type="hidden" name="qty" value="1">
                    <input type="hidden" name="redirect" value="cart">
                    <button type="submit" class="btn-buy-now">Buy Now</button>
                </form>
            </div>

            {{-- Store info --}}
            <div class="store-card-mini">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:#E8F4FD;display:flex;align-items:center;justify-content:center;font-size:20px;">🏪</div>
                    <div>
                        <div style="font-size:13px;font-weight:700;">{{ $product['storeName'] }}</div>
                        <div style="font-size:11px;color:#757575;">Official Store · Verified ✓</div>
                    </div>
                </div>
                <button style="padding:6px 16px;background:transparent;color:#E8192C;border:1px solid #E8192C;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">
                    Visit Store
                </button>
            </div>

            {{-- Shipping badges --}}
            <div class="shipping-row">
                @foreach([['🚚','Free Shipping','2–5 business days'],['🔄','Free Returns','30-day return policy'],['🛡️','Buyer Protection','100% guaranteed']] as [$icon,$title,$sub])
                    <div class="shipping-item">
                        <div style="font-size:18px;margin-bottom:2px;">{{ $icon }}</div>
                        <div style="font-size:11px;font-weight:700;">{{ $title }}</div>
                        <div style="font-size:10px;color:#757575;">{{ $sub }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="background:#fff;border-radius:10px;border:1px solid #E8E8E8;margin-bottom:32px;">
        <div class="tabs-nav">
            @foreach(['description','specifications','reviews'] as $tab)
                <button class="tab-btn {{ $loop->first ? 'active' : '' }}"
                        onclick="switchTab('{{ $tab }}', this)">{{ ucfirst($tab) }}</button>
            @endforeach
        </div>

        <div class="tab-content" id="tab-description">
            <p style="font-size:14px;color:#1A1A1A;line-height:1.7;margin:0;">{{ $product['description'] }} Made with attention to comfort and durability, suitable for everyday use. Available in multiple colors and sizes.</p>
        </div>

        <div class="tab-content" id="tab-specifications" style="display:none;">
            <table style="width:100%;border-collapse:collapse;">
                <tbody>
                    @foreach($product['specs'] as $i => $spec)
                        <tr style="background:{{ $i % 2 === 0 ? '#FAFAFA' : '#fff' }}">
                            <td style="padding:10px 16px;font-size:13px;color:#757575;font-weight:600;width:30%;">{{ $spec['label'] }}</td>
                            <td style="padding:10px 16px;font-size:13px;color:#1A1A1A;">{{ $spec['value'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="tab-content" id="tab-reviews" style="display:none;">
            <div style="display:flex;gap:32px;margin-bottom:20px;">
                <div style="text-align:center;">
                    <div style="font-size:52px;font-weight:900;color:#E8192C;">{{ $product['rating'] }}</div>
                    <div style="color:#EAB308;font-size:20px;">{{ str_repeat('★', round($product['rating'])) }}</div>
                    <div style="font-size:12px;color:#757575;">Based on {{ floor($product['sold'] * 0.3) }} reviews</div>
                </div>
                <div style="flex:1;">
                    @foreach([5=>62, 4=>24, 3=>8, 2=>4, 1=>2] as $star => $pct)
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;">
                            <span style="font-size:12px;color:#757575;width:20px;">{{ $star }}★</span>
                            <div style="flex:1;height:6px;border-radius:3px;background:#F0F0F0;overflow:hidden;">
                                <div style="width:{{ $pct }}%;height:100%;background:#EAB308;border-radius:3px;"></div>
                            </div>
                            <span style="font-size:11px;color:#757575;width:24px;">{{ $pct }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>

            @foreach($reviews as $review)
                <div class="review-item">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                        <div class="reviewer-avatar">{{ substr($review['name'], 0, 2) }}</div>
                        <div>
                            <div style="font-size:13px;font-weight:700;">{{ $review['name'] }}</div>
                            <div style="color:#EAB308;font-size:11px;">
                                {{ str_repeat('★', $review['rating']) }}
                                <span style="color:#ABABAB;">{{ $review['date'] }}</span>
                            </div>
                        </div>
                    </div>
                    <p style="margin:0;font-size:13px;color:#1A1A1A;line-height:1.5;">{{ $review['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Related Products --}}
    <div>
        <div style="font-size:17px;font-weight:800;color:#1A1A1A;border-left:4px solid #E8192C;padding-left:10px;margin-bottom:16px;">
            You May Also Like
        </div>
        <div class="related-grid">
            @foreach($related as $p)
                @include('components.product-card', ['product' => $p, 'compact' => false])
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
const productPrice = {{ $product['price'] }};
let qty = 1;

function changeQty(delta) {
    qty = Math.max(1, qty + delta);
    document.getElementById('qty').textContent = qty;
    document.getElementById('qtyInput').value  = qty;
    document.getElementById('totalPrice').textContent = '$' + (productPrice * qty).toFixed(2);
}

function selectImg(i, hue, emoji, el) {
    document.getElementById('mainImg').style.background = `hsl(${hue}, 38%, 88%)`;
    document.getElementById('mainEmoji').textContent = emoji;
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

function selectColor(el, name) {
    document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('colorName').textContent = name;
}

function selectSize(el, size) {
    document.querySelectorAll('.size-chip').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('selectedSize').textContent = size;
}

function switchTab(tab, btn) {
    ['description','specifications','reviews'].forEach(t => {
        document.getElementById('tab-' + t).style.display = t === tab ? 'block' : 'none';
    });
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// Add to cart with visual feedback
document.getElementById('addCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('addCartBtn');
    const form = this;
    fetch(form.action, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ product_id: {{ $product['id'] }}, qty })
    }).then(r => r.json()).then(data => {
        btn.textContent = '✓ Added to Cart!';
        btn.classList.add('added');
        const badge = document.querySelector('.cart-badge');
        if (badge) { badge.textContent = data.cartCount; }
        else {
            const cartLink = document.querySelector('a[href*="cart"] .icon').parentElement;
            const b = document.createElement('span');
            b.className = 'cart-badge'; b.textContent = data.cartCount;
            cartLink.appendChild(b);
        }
        setTimeout(() => { btn.textContent = '🛒 Add to Cart'; btn.classList.remove('added'); }, 2000);
    });
});
</script>
@endsection