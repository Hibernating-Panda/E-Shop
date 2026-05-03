@extends('layouts.app')

@section('title', 'Shopping Cart – L192')

@section('styles')
<style>
    .page-wrap { max-width: 1200px; margin: 0 auto; padding: 14px 20px; }
    .cart-layout { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }

    /* Cart item */
    .cart-item { background: #fff; border-radius: 8px; border: 1px solid #E8E8E8; padding: 14px 16px; margin-bottom: 8px; display: flex; align-items: center; gap: 14px; }
    .item-img  { width: 80px; height: 80px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 36px; flex-shrink: 0; }
    .item-title{ font-size: 13px; font-weight: 600; color: #1A1A1A; margin-bottom: 4px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
    .item-store{ font-size: 11px; color: #757575; margin-bottom: 6px; }
    .qty-ctrl  { display: flex; align-items: center; border: 1px solid #E8E8E8; border-radius: 6px; overflow: hidden; flex-shrink: 0; }
    .qty-btn   { width: 32px; height: 32px; border: none; background: #F5F5F5; cursor: pointer; font-size: 14px; font-weight: 700; }
    .qty-num   { width: 40px; text-align: center; font-size: 13px; font-weight: 700; border-left: 1px solid #E8E8E8; border-right: 1px solid #E8E8E8; }

    /* Summary sidebar */
    .summary-box { background: #fff; border-radius: 10px; border: 1px solid #E8E8E8; padding: 20px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
    .payment-icons { display: flex; gap: 8px; justify-content: center; margin-top: 14px; }
    .pay-icon { width: 40px; height: 26px; background: #F5F5F5; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 14px; }

    /* Empty state */
    .empty-state { max-width: 1200px; margin: 0 auto; padding: 60px 20px; text-align: center; }
</style>
@endsection

@section('content')

@if(empty($cartItems))
    <div class="empty-state">
        <div style="font-size:72px;margin-bottom:16px;">🛒</div>
        <h2 style="font-size:22px;font-weight:800;color:#1A1A1A;margin:0 0 8px;">Your cart is empty</h2>
        <p style="color:#757575;margin-bottom:24px;">Start shopping to add items here</p>
        <a href="{{ route('home') }}">
            <button class="btn-primary" style="width:auto;padding:12px 32px;">Continue Shopping</button>
        </a>
    </div>
@else
    <div class="page-wrap">
        @include('components.breadcrumb', ['items' => [
            ['label' => 'Home',          'url' => route('home')],
            ['label' => 'Shopping Cart', 'url' => null],
        ]])

        <h1 style="font-size:22px;font-weight:900;margin:0 0 20px;color:#1A1A1A;">
            Shopping Cart <span style="font-size:14px;font-weight:500;color:#757575;">({{ count($cartItems) }} items)</span>
        </h1>

        <div class="cart-layout">

            {{-- Cart items --}}
            <div>
                {{-- Select all --}}
                <form action="{{ route('cart.remove-selected') }}" method="POST" id="cartForm">
                    @csrf
                    @method('DELETE')

                    <div style="background:#fff;border-radius:8px;border:1px solid #E8E8E8;padding:12px 16px;margin-bottom:10px;display:flex;align-items:center;gap:10px;">
                        <input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="width:16px;height:16px;cursor:pointer;">
                        <span style="font-size:13px;font-weight:700;">Select All ({{ count($cartItems) }} items)</span>
                        <button type="submit" style="margin-left:auto;background:none;border:none;color:#E8192C;font-size:12px;cursor:pointer;font-weight:600;">
                            Remove selected
                        </button>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="cart-item">
                            <input type="checkbox" name="item_ids[]" value="{{ $item['id'] }}"
                                   class="item-checkbox" style="width:16px;height:16px;cursor:pointer;flex-shrink:0;"
                                   onchange="updateSelectAll()">
                            <div class="item-img" style="background:hsl({{ $item['imageHue'] }}, 35%, 90%)">{{ $item['emoji'] }}</div>
                            <div style="flex:1;min-width:0;">
                                <div class="item-title">{{ $item['title'] }}</div>
                                <div class="item-store">Sold by: {{ $item['storeName'] }}</div>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <span style="color:#E8192C;font-weight:800;font-size:17px;">${{ number_format($item['price'], 2) }}</span>
                                    @if($item['discount'])
                                        <span style="background:#E8192C;color:#fff;font-size:10px;padding:1px 5px;border-radius:3px;font-weight:700;">{{ $item['discount'] }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Qty controls --}}
                            <div class="qty-ctrl">
                                <form action="{{ route('cart.update') }}" method="POST" style="display:contents;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                    <input type="hidden" name="action" value="dec">
                                    <button type="submit" class="qty-btn">−</button>
                                </form>
                                <div class="qty-num">{{ $item['qty'] }}</div>
                                <form action="{{ route('cart.update') }}" method="POST" style="display:contents;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                    <input type="hidden" name="action" value="inc">
                                    <button type="submit" class="qty-btn">+</button>
                                </form>
                            </div>

                            <div style="font-size:15px;font-weight:800;flex-shrink:0;min-width:64px;text-align:right;">
                                ${{ number_format($item['price'] * $item['qty'], 2) }}
                            </div>

                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#ABABAB;cursor:pointer;font-size:18px;padding:4px;">×</button>
                            </form>
                        </div>
                    @endforeach
                </form>
            </div>

            {{-- Order Summary --}}
            <div class="summary-box">
                <h3 style="margin:0 0 16px;font-size:15px;font-weight:800;border-bottom:1px solid #E8E8E8;padding-bottom:12px;">Order Summary</h3>

                @php
                    $subtotal = collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']);
                    $shipping = $subtotal > 30 ? 0 : 2.5;
                    $discount = session('coupon_applied') ? $subtotal * 0.1 : 0;
                    $total    = $subtotal - $discount + $shipping;
                @endphp

                <div style="margin-bottom:14px;">
                    <div class="summary-row">
                        <span style="color:#757575;">Subtotal</span>
                        <span style="font-weight:600;">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span style="color:#757575;">Shipping</span>
                        <span style="font-weight:600;color:{{ $shipping === 0 ? '#10B981' : '#1A1A1A' }}">
                            {{ $shipping === 0 ? 'FREE' : '$' . number_format($shipping, 2) }}
                        </span>
                    </div>
                    @if(session('coupon_applied'))
                        <div class="summary-row">
                            <span style="color:#757575;">Discount (10%)</span>
                            <span style="font-weight:600;color:#10B981;">-${{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    @if($shipping === 0)
                        <div style="font-size:11px;color:#10B981;margin-bottom:8px;">✓ Free shipping on orders over $30</div>
                    @endif
                </div>

                <div style="border-top:1px solid #E8E8E8;padding-top:12px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:baseline;">
                    <span style="font-weight:800;font-size:15px;">Total</span>
                    <span style="font-weight:900;font-size:22px;color:#E8192C;">${{ number_format($total, 2) }}</span>
                </div>

                {{-- Coupon --}}
                <div style="margin-bottom:16px;">
                    <div style="font-size:12px;font-weight:700;margin-bottom:6px;">Coupon Code</div>
                    <form action="{{ route('cart.coupon') }}" method="POST" style="display:flex;gap:6px;">
                        @csrf
                        <input name="coupon" placeholder="Enter code"
                               style="flex:1;height:34px;border:1px solid #E8E8E8;border-radius:6px;padding:0 10px;font-size:12px;outline:none;"
                               value="{{ session('coupon_code', '') }}">
                        <button type="submit" style="padding:0 12px;background:#E8192C;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">Apply</button>
                    </form>
                    @if(session('coupon_applied'))
                        <div style="font-size:11px;color:#10B981;margin-top:4px;">✓ Coupon SAVE10 applied!</div>
                    @endif
                    <div style="font-size:10px;color:#ABABAB;margin-top:3px;">Try: SAVE10</div>
                </div>

                <a href="{{ route('checkout.index') }}">
                    <button class="btn-primary" style="margin-bottom:10px;">Checkout ({{ count($cartItems) }} items)</button>
                </a>
                <a href="{{ route('category.index') }}">
                    <button class="btn-outline">Continue Shopping</button>
                </a>

                <div class="payment-icons">
                    @foreach(['💳','🏦','📱','💰'] as $icon)
                        <div class="pay-icon">{{ $icon }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
function toggleAll(el) {
    document.querySelectorAll('.item-checkbox').forEach(c => c.checked = el.checked);
}
function updateSelectAll() {
    const all = document.querySelectorAll('.item-checkbox');
    const checked = document.querySelectorAll('.item-checkbox:checked');
    document.getElementById('selectAll').checked = all.length === checked.length;
    document.getElementById('selectAll').indeterminate = checked.length > 0 && checked.length < all.length;
}
</script>
@endsection