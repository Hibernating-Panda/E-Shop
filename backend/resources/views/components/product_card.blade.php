{{--
    Component: product-card
    Variables:
      $product  – array with keys: id, title, price, discount, rating, sold, imageHue, emoji, inStock
      $compact  – bool (smaller image height), default false
--}}
@php $compact = $compact ?? false; @endphp

<a href="{{ route('product.show', $product['id']) }}"
   class="product-card">

    @if(!$product['inStock'])
        <div class="out-of-stock-overlay"><span>OUT OF STOCK</span></div>
    @endif

    @if($product['discount'])
        <div class="badge" style="position:absolute;top:6px;left:6px;z-index:2;background:#E8192C;color:#fff;font-size:10px;font-weight:800;padding:2px 6px;border-radius:3px;">
            {{ $product['discount'] }}
        </div>
    @endif

    <div class="card-img {{ $compact ? 'card-img-compact' : 'card-img-normal' }}"
         style="background:hsl({{ $product['imageHue'] }}, 35%, 90%);">
        {{ $product['emoji'] }}
    </div>

    <div class="card-body">
        @if($product['inStock'])
            <div class="in-stock">IN STOCK</div>
        @endif
        <div class="card-title">{{ $product['title'] }}</div>
        <div style="color:#757575;font-size:11px;margin-bottom:4px;">Low Price</div>
        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="card-price" style="font-size:{{ $compact ? '14px' : '16px' }}">
                ${{ number_format($product['price'], 2) }}
            </span>
            @if($product['discount'])
                <span class="badge">{{ $product['discount'] }}</span>
            @endif
            <span style="color:#EAB308;font-size:11px;">
                {{ str_repeat('★', round($product['rating'])) }}
                <span style="color:#ABABAB;">{{ $product['rating'] }}</span>
            </span>
        </div>
    </div>
</a>