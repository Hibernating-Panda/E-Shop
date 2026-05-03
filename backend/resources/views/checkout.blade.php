@extends('layouts.app')

@section('title', 'Checkout – L192')

@section('styles')
<style>
    .page-wrap { max-width: 1200px; margin: 0 auto; padding: 14px 20px; }
    .checkout-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

    /* Progress steps */
    .steps-bar { background: #fff; border-radius: 10px; border: 1px solid #E8E8E8; padding: 16px 24px; margin-bottom: 28px; display: flex; align-items: center; }
    .step { display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .step-circle { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px; font-weight: 800; transition: background 0.3s; }
    .step-circle.done    { background: #10B981; }
    .step-circle.active  { background: #E8192C; }
    .step-circle.pending { background: #E5E7EB; }
    .step-label { font-size: 11px; font-weight: 500; color: #757575; white-space: nowrap; }
    .step-label.active { font-weight: 700; color: #E8192C; }
    .step-label.done   { color: #10B981; }
    .step-line { flex: 1; height: 2px; margin: 0 8px 20px; transition: background 0.3s; }

    /* Form */
    .form-box { background: #fff; border-radius: 10px; border: 1px solid #E8E8E8; padding: 22px 24px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-field { display: flex; flex-direction: column; }
    .form-field.full { grid-column: 1 / -1; }
    .form-label { font-size: 12px; font-weight: 700; color: #757575; margin-bottom: 5px; }
    .form-input { height: 38px; border: 1px solid #E8E8E8; border-radius: 6px; padding: 0 12px; font-size: 13px; outline: none; box-sizing: border-box; width: 100%; }
    .form-input:focus { border-color: #E8192C; }

    /* Payment methods */
    .pay-methods { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .pay-method { flex: 1; min-width: 120px; padding: 10px 8px; border: 2px solid #E8E8E8; border-radius: 8px; cursor: pointer; text-align: center; }
    .pay-method input[type="radio"] { display: none; }
    .pay-method.selected { border-color: #E8192C; background: #FFF0F1; }
    .pay-method label { font-size: 12px; font-weight: 700; color: #1A1A1A; cursor: pointer; display: block; }
    .pay-method.selected label { color: #E8192C; }

    /* Nav buttons */
    .nav-btns { display: flex; justify-content: space-between; margin-top: 24px; }
    .btn-back { padding: 10px 24px; background: transparent; color: #1A1A1A; border: 1px solid #E8E8E8; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; }
    .btn-next { padding: 10px 28px; background: #E8192C; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 800; cursor: pointer; }
    .btn-next:hover { background: #C0001F; }

    /* Confirmation */
    .confirm-wrap { max-width: 600px; margin: 60px auto; padding: 0 20px; text-align: center; }
</style>
@endsection

@section('content')

@if(session('order_confirmed'))
    <div class="confirm-wrap">
        <div style="font-size:80px;margin-bottom:20px;">🎉</div>
        <h2 style="font-size:26px;font-weight:900;color:#10B981;margin-bottom:8px;">Order Confirmed!</h2>
        <p style="color:#757575;margin-bottom:4px;">Thank you for your purchase!</p>
        <div style="background:#F0FFF4;border-radius:10px;border:1px solid #A7F3D0;padding:16px;margin-bottom:24px;">
            <div style="font-size:13px;color:#10B981;font-weight:700;">Order #{{ session('order_id') }}</div>
            <div style="font-size:13px;color:#757575;margin-top:4px;">Estimated delivery: 3–5 business days</div>
        </div>
        <div style="display:flex;gap:12px;justify-content:center;">
            <a href="{{ route('orders.index') }}"><button class="btn-next">Track Order</button></a>
            <a href="{{ route('home') }}"><button class="btn-back">Continue Shopping</button></a>
        </div>
    </div>

@else

<div class="page-wrap">
    @include('components.breadcrumb', ['items' => [
        ['label' => 'Home',     'url' => route('home')],
        ['label' => 'Cart',     'url' => route('cart.index')],
        ['label' => 'Checkout', 'url' => null],
    ]])

    {{-- Steps --}}
    @php $steps = ['Shipping','Payment','Review','Confirmation']; $step = (int) request('step', 0); @endphp
    <div class="steps-bar">
        @foreach($steps as $i => $label)
            <div class="step">
                <div class="step-circle {{ $i < $step ? 'done' : ($i === $step ? 'active' : 'pending') }}">
                    {{ $i < $step ? '✓' : $i + 1 }}
                </div>
                <div class="step-label {{ $i === $step ? 'active' : ($i < $step ? 'done' : '') }}">{{ $label }}</div>
            </div>
            @if(!$loop->last)
                <div class="step-line" style="background:{{ $i < $step ? '#10B981' : '#E5E7EB' }}"></div>
            @endif
        @endforeach
    </div>

    <div class="checkout-layout">

        {{-- Step forms --}}
        <div class="form-box">

            {{-- Step 0: Shipping --}}
            @if($step === 0)
                <h3 style="margin:0 0 18px;font-size:16px;font-weight:800;">Shipping Address</h3>
                <form method="POST" action="{{ route('checkout.shipping') }}">
                    @csrf
                    <div class="form-grid">
                        @foreach([
                            ['firstName','First Name'],['lastName','Last Name'],
                            ['phone','Phone Number'],['email','Email Address'],
                            ['address','Street Address'],['city','City'],
                            ['province','Province'],['zip','ZIP Code'],
                        ] as [$field, $label])
                            <div class="form-field {{ in_array($field,['address','email']) ? 'full' : '' }}">
                                <label class="form-label">{{ $label }} *</label>
                                <input class="form-input" name="{{ $field }}"
                                       value="{{ old($field, $shipping[$field] ?? '') }}" required>
                            </div>
                        @endforeach
                    </div>
                    <div class="nav-btns">
                        <a href="{{ route('cart.index') }}"><button type="button" class="btn-back">← Back to Cart</button></a>
                        <button type="submit" class="btn-next">Continue →</button>
                    </div>
                </form>

            {{-- Step 1: Payment --}}
            @elseif($step === 1)
                <h3 style="margin:0 0 18px;font-size:16px;font-weight:800;">Payment Method</h3>
                <form method="POST" action="{{ route('checkout.payment') }}">
                    @csrf
                    <div class="pay-methods">
                        @foreach([['card','💳 Credit/Debit Card'],['aba','🏦 ABA Bank'],['wing','📱 Wing Money'],['cod','💰 Cash on Delivery']] as [$val,$label])
                            <div class="pay-method {{ old('pay_method','card') === $val ? 'selected' : '' }}"
                                 onclick="selectPay('{{ $val }}', this)">
                                <input type="radio" name="pay_method" value="{{ $val }}"
                                       id="pay_{{ $val }}" {{ old('pay_method','card') === $val ? 'checked' : '' }}>
                                <label for="pay_{{ $val }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div id="card-fields">
                        <div style="display:grid;gap:14px;">
                            <div class="form-field">
                                <label class="form-label">Cardholder Name</label>
                                <input class="form-input" name="card_name" placeholder="Sophea Chan">
                            </div>
                            <div class="form-field">
                                <label class="form-label">Card Number</label>
                                <input class="form-input" name="card_number" placeholder="•••• •••• •••• ••••" maxlength="19">
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                                <div class="form-field">
                                    <label class="form-label">Expiry (MM/YY)</label>
                                    <input class="form-input" name="card_expiry" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="form-field">
                                    <label class="form-label">CVV</label>
                                    <input class="form-input" name="card_cvv" placeholder="•••" maxlength="4" type="password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="alt-pay" style="display:none;background:#F9FAFB;border-radius:8px;padding:20px;text-align:center;">
                        <div id="altPayIcon" style="font-size:32px;margin-bottom:8px;"></div>
                        <div id="altPayLabel" style="font-size:14px;font-weight:700;"></div>
                        <div id="altPaySub" style="font-size:12px;color:#757575;margin-top:4px;"></div>
                    </div>

                    <div class="nav-btns">
                        <a href="{{ route('checkout.index', ['step' => 0]) }}"><button type="button" class="btn-back">← Previous</button></a>
                        <button type="submit" class="btn-next">Continue →</button>
                    </div>
                </form>

            {{-- Step 2: Review --}}
            @elseif($step === 2)
                <h3 style="margin:0 0 16px;font-size:16px;font-weight:800;">Review Your Order</h3>
                @foreach($cartItems as $item)
                    <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #E8E8E8;">
                        <div style="width:56px;height:56px;border-radius:6px;background:hsl({{ $item['imageHue'] }},35%,90%);display:flex;align-items:center;justify-content:center;font-size:26px;">{{ $item['emoji'] }}</div>
                        <div style="flex:1;">
                            <div style="font-size:13px;font-weight:600;">{{ $item['title'] }}</div>
                            <div style="font-size:11px;color:#757575;">Qty: {{ $item['qty'] }}</div>
                        </div>
                        <div style="font-weight:800;color:#E8192C;">${{ number_format($item['price'] * $item['qty'], 2) }}</div>
                    </div>
                @endforeach

                @if(session('shipping_address'))
                    <div style="margin-top:16px;background:#F9FAFB;border-radius:8px;padding:14px;">
                        <div style="font-size:13px;font-weight:700;margin-bottom:8px;">Shipping to:</div>
                        @php $addr = session('shipping_address'); @endphp
                        <div style="font-size:12px;color:#757575;">{{ $addr['firstName'] }} {{ $addr['lastName'] }} · {{ $addr['phone'] }}</div>
                        <div style="font-size:12px;color:#757575;">{{ $addr['address'] }}, {{ $addr['city'] }}, {{ $addr['province'] }} {{ $addr['zip'] }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('checkout.place-order') }}">
                    @csrf
                    <div class="nav-btns">
                        <a href="{{ route('checkout.index', ['step' => 1]) }}"><button type="button" class="btn-back">← Previous</button></a>
                        <button type="submit" class="btn-next">🔒 Place Order</button>
                    </div>
                </form>
            @endif

        </div>

        {{-- Summary sidebar --}}
        <div style="background:#fff;border-radius:10px;border:1px solid #E8E8E8;padding:18px;">
            <h3 style="margin:0 0 14px;font-size:14px;font-weight:800;border-bottom:1px solid #E8E8E8;padding-bottom:10px;">Order Summary</h3>

            @foreach(array_slice($cartItems, 0, 3) as $item)
                <div style="display:flex;gap:8px;margin-bottom:10px;">
                    <div style="width:42px;height:42px;border-radius:4px;background:hsl({{ $item['imageHue'] }},35%,90%);display:flex;align-items:center;justify-content:center;font-size:20px;">{{ $item['emoji'] }}</div>
                    <div style="flex:1;">
                        <div style="font-size:11px;color:#1A1A1A;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;max-width:160px;">{{ $item['title'] }}</div>
                        <div style="font-size:11px;color:#E8192C;font-weight:700;">${{ number_format($item['price'],2) }} × {{ $item['qty'] }}</div>
                    </div>
                </div>
            @endforeach

            @if(count($cartItems) > 3)
                <div style="font-size:11px;color:#757575;margin-bottom:10px;">+{{ count($cartItems) - 3 }} more items</div>
            @endif

            @php
                $subtotal = collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']);
                $shipping = $subtotal > 30 ? 0 : 2.5;
                $total    = $subtotal + $shipping;
            @endphp

            <div style="border-top:1px solid #E8E8E8;padding-top:10px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:12px;">
                    <span style="color:#757575;">Subtotal</span>
                    <span style="font-weight:600;">${{ number_format($subtotal,2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:12px;">
                    <span style="color:#757575;">Shipping</span>
                    <span style="font-weight:600;color:{{ $shipping === 0 ? '#10B981' : '#1A1A1A' }}">{{ $shipping === 0 ? 'FREE' : '$'.number_format($shipping,2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:8px;padding-top:8px;border-top:1px solid #E8E8E8;">
                    <span style="font-weight:800;font-size:14px;">Total</span>
                    <span style="font-weight:900;font-size:18px;color:#E8192C;">${{ number_format($total,2) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endif
@endsection

@section('scripts')
<script>
const payInfo = {
    cod:  { icon: '💰', label: 'Cash on Delivery',   sub: 'Pay when your order arrives.' },
    aba:  { icon: '🏦', label: 'Pay via ABA',         sub: 'You will be redirected to complete payment.' },
    wing: { icon: '📱', label: 'Pay via Wing Money',  sub: 'You will be redirected to complete payment.' },
};

function selectPay(val, el) {
    document.querySelectorAll('.pay-method').forEach(m => m.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input[type="radio"]').checked = true;
    const cardFields = document.getElementById('card-fields');
    const altPay     = document.getElementById('alt-pay');
    if (val === 'card') {
        cardFields.style.display = 'grid';
        altPay.style.display = 'none';
    } else {
        cardFields.style.display = 'none';
        altPay.style.display = 'block';
        const info = payInfo[val];
        document.getElementById('altPayIcon').textContent  = info.icon;
        document.getElementById('altPayLabel').textContent = info.label;
        document.getElementById('altPaySub').textContent   = info.sub;
    }
}
</script>
@endsection