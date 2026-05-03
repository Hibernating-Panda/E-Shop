@extends('layouts.app')

@section('title', 'Shop – L192 Marketplace')

@section('styles')
<style>
    .page-wrap { max-width: 1200px; margin: 0 auto; padding: 14px 20px; }

    /* Subcategory tiles */
    .sub-cats { display: flex; gap: 10px; margin-bottom: 14px; overflow-x: auto; }
    .sub-cat { min-width: 100px; background: #fff; border-radius: 8px; border: 2px solid #E8E8E8; padding: 10px 8px; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.15s; display: block; }
    .sub-cat.active, .sub-cat:hover { border-color: #E8192C; }
    .sub-cat-emoji { font-size: 28px; margin-bottom: 4px; }
    .sub-cat-label { font-size: 11px; font-weight: 500; color: #1A1A1A; line-height: 1.3; }
    .sub-cat.active .sub-cat-label { font-weight: 700; color: #E8192C; }

    /* Filter bar */
    .filter-bar { background: #fff; border-radius: 8px; border: 1px solid #E8E8E8; padding: 10px 16px; margin-bottom: 12px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .filter-group { display: flex; align-items: center; gap: 6px; }
    .filter-label { font-size: 13px; color: #1A1A1A; font-weight: 600; }
    .filter-select { border: 1px solid #E8E8E8; border-radius: 4px; padding: 4px 8px; font-size: 12px; outline: none; cursor: pointer; }
    .price-chip { padding: 3px 10px; border-radius: 14px; border: 1px solid #E8E8E8; font-size: 11px; cursor: pointer; color: #757575; font-weight: 400; text-decoration: none; }
    .price-chip.active { border-color: #E8192C; background: #FFF0F1; color: #E8192C; font-weight: 700; }
    .view-btn { padding: 5px 8px; border-radius: 4px; border: 1px solid #E8E8E8; background: transparent; color: #757575; cursor: pointer; font-size: 14px; }
    .view-btn.active { border-color: #E8192C; background: #FFF0F1; color: #E8192C; }

    /* Sort bar */
    .sort-bar { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; flex-wrap: wrap; }
    .sort-chip { padding: 5px 12px; border-radius: 4px; border: 1px solid #E8E8E8; background: #fff; color: #757575; font-size: 12px; cursor: pointer; text-decoration: none; font-weight: 400; }
    .sort-chip.active { background: #E8192C; color: #fff; border-color: #E8192C; font-weight: 700; }

    /* Grid / List views */
    .product-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
    .product-list { display: flex; flex-direction: column; gap: 10px; }
    .list-card { background: #fff; border-radius: 8px; border: 1px solid #E8E8E8; display: flex; gap: 14px; padding: 12px; cursor: pointer; text-decoration: none; transition: border-color 0.15s; }
    .list-card:hover { border-color: #E8192C; }
    .list-img { width: 90px; height: 90px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 40px; flex-shrink: 0; }
    .load-more-wrap { text-align: center; margin-top: 28px; }
    .load-more-btn { background: transparent; color: #E8192C; border: 2px solid #E8192C; padding: 10px 40px; border-radius: 24px; font-size: 14px; font-weight: 700; cursor: pointer; }
    .no-results { text-align: center; padding: 60px 0; color: #757575; }

    /* Stock toggle */
    .toggle { width: 42px; height: 22px; border-radius: 11px; cursor: pointer; position: relative; transition: background 0.2s; border: none; }
    .toggle-thumb { position: absolute; top: 2px; width: 18px; height: 18px; border-radius: 9px; background: #fff; transition: left 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
</style>
@endsection

@section('content')
<div class="page-wrap">

    @include('components.breadcrumb', ['items' => [
        ['label' => 'Home',          'url' => route('home')],
        ['label' => 'Fashion',       'url' => '#'],
        ['label' => 'Shoes & Bags',  'url' => null],
    ]])

    {{-- Subcategory tiles --}}
    <div class="sub-cats">
        @foreach($subcategories as $sub)
            <a href="{{ route('category.index', array_merge(request()->query(), ['sub' => $sub['label']])) }}"
               class="sub-cat {{ request('sub') === $sub['label'] ? 'active' : '' }}">
                <div class="sub-cat-emoji">{{ $sub['emoji'] }}</div>
                <div class="sub-cat-label">{{ $sub['label'] }}</div>
            </a>
        @endforeach
    </div>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('category.index') }}" id="filterForm">
        {{-- preserve existing query params --}}
        @foreach(request()->except(['grade','inStock','price','sort','view','page']) as $k => $v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach

        <div class="filter-bar">
            <div class="filter-group">
                <span class="filter-label">Grade</span>
                <select name="grade" class="filter-select" onchange="this.form.submit()">
                    @foreach(['All','A+','A','B','C'] as $g)
                        <option value="{{ $g }}" {{ request('grade','All') === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <span class="filter-label">In stock</span>
                <button type="button"
                        class="toggle"
                        style="background:{{ request('inStock') ? '#E8192C' : '#D1D5DB' }}"
                        onclick="toggleStock(this)">
                    <div class="toggle-thumb" style="left:{{ request('inStock') ? '22px' : '2px' }}"></div>
                </button>
                <input type="hidden" name="inStock" id="inStockInput" value="{{ request('inStock', '0') }}">
            </div>

            <div class="filter-group" style="gap:6px;">
                <span style="font-size:12px;color:#757575;">Price:</span>
                @foreach($priceRanges as $i => $range)
                    <a href="{{ route('category.index', array_merge(request()->query(), ['price' => $i, 'page' => 1])) }}"
                       class="price-chip {{ request('price', 0) == $i ? 'active' : '' }}">
                        {{ $range['label'] }}
                    </a>
                @endforeach
            </div>

            <div style="flex:1;"></div>

            {{-- View mode --}}
            <div style="display:flex;gap:4px;">
                <a href="{{ route('category.index', array_merge(request()->query(), ['view' => 'grid'])) }}"
                   class="view-btn {{ request('view','grid') === 'grid' ? 'active' : '' }}">⊞</a>
                <a href="{{ route('category.index', array_merge(request()->query(), ['view' => 'list'])) }}"
                   class="view-btn {{ request('view') === 'list' ? 'active' : '' }}">☰</a>
            </div>

            <button type="submit" style="display:flex;align-items:center;gap:6px;padding:6px 14px;border-radius:6px;border:1px solid #E8192C;background:#FFF0F1;color:#E8192C;font-size:12px;font-weight:700;cursor:pointer;">
                🔧 Filter
            </button>
        </div>

        {{-- Sort bar --}}
        <div class="sort-bar">
            <span style="font-size:12px;color:#757575;">Sort by:</span>
            @foreach($sortOptions as $i => $label)
                <a href="{{ route('category.index', array_merge(request()->query(), ['sort' => $i, 'page' => 1])) }}"
                   class="sort-chip {{ request('sort', 0) == $i ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
            <span style="margin-left:auto;font-size:12px;color:#757575;">{{ $total }} results</span>
        </div>
    </form>

    {{-- Products --}}
    @if(request('view') === 'list')
        <div class="product-list">
            @forelse($products as $product)
                <a href="{{ route('product.show', $product['id']) }}" class="list-card">
                    <div class="list-img" style="background:hsl({{ $product['imageHue'] }}, 35%, 90%)">{{ $product['emoji'] }}</div>
                    <div style="flex:1;">
                        @if($product['inStock'])<div style="color:#10B981;font-size:10px;font-weight:800;margin-bottom:2px;">IN STOCK</div>@endif
                        <div style="font-size:14px;font-weight:600;color:#1A1A1A;margin-bottom:4px;">{{ $product['title'] }}</div>
                        <div style="font-size:12px;color:#757575;margin-bottom:6px;">{{ $product['location'] }} · {{ number_format($product['sold']) }} sold</div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="color:#E8192C;font-weight:800;font-size:18px;">${{ number_format($product['price'], 2) }}</span>
                            @if($product['discount'])<span style="background:#E8192C;color:#fff;font-size:10px;padding:2px 6px;border-radius:3px;font-weight:700;">{{ $product['discount'] }}</span>@endif
                            <span style="color:#EAB308;font-size:12px;">{{ str_repeat('★', round($product['rating'])) }} {{ $product['rating'] }}</span>
                        </div>
                    </div>
                    <div style="align-self:center;">
                        <form action="{{ route('cart.add') }}" method="POST" onclick="event.preventDefault(); addToCart({{ $product['id'] }})">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                            <button type="submit" style="background:#E8192C;color:#fff;border:none;padding:8px 16px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </a>
            @empty
                <div class="no-results">
                    <div style="font-size:48px;margin-bottom:12px;">🔍</div>
                    <div style="font-size:16px;font-weight:600;">No products found</div>
                    <div style="font-size:13px;margin-top:4px;">Try adjusting your filters</div>
                </div>
            @endforelse
        </div>
    @else
        <div class="product-grid">
            @forelse($products as $product)
                @include('components.product-card', ['product' => $product, 'compact' => false])
            @empty
                <div class="no-results" style="grid-column:1/-1;">
                    <div style="font-size:48px;margin-bottom:12px;">🔍</div>
                    <div style="font-size:16px;font-weight:600;">No products found</div>
                    <div style="font-size:13px;margin-top:4px;">Try adjusting your filters</div>
                </div>
            @endforelse
        </div>
    @endif

    {{-- Pagination --}}
    @if($products->hasMorePages())
        <div class="load-more-wrap">
            <a href="{{ $products->nextPageUrl() }}">
                <button class="load-more-btn">
                    Load More ({{ $products->total() - $products->currentPage() * $products->perPage() }} remaining)
                </button>
            </a>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
function toggleStock(btn) {
    const input = document.getElementById('inStockInput');
    const isOn  = input.value === '1';
    input.value             = isOn ? '0' : '1';
    btn.style.background    = isOn ? '#D1D5DB' : '#E8192C';
    btn.querySelector('.toggle-thumb').style.left = isOn ? '2px' : '22px';
    document.getElementById('filterForm').submit();
}

function addToCart(productId) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ product_id: productId, qty: 1 })
    }).then(r => r.json()).then(data => {
        const badge = document.querySelector('.cart-badge');
        if (badge) badge.textContent = data.cartCount;
    });
}
</script>
@endsection