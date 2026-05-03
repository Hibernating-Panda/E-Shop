<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'home'])->name('home');

Route::get('/category', [ProductController::class, 'category'])->name('category.index');

Route::get('/cart', [ProductController::class, 'cart'])->name('cart.index');

Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout.index');

Route::get('/product/{id}', function($id) {
    return "Product detail page for product $id";
})->name('product.show');

Route::get('/profile', function() {
    return "Profile page";
})->name('profile');

Route::get('/orders', function() {
    return "Orders page";
})->name('orders.index');

// Cart management routes
Route::post('/cart/add', function() {
    return response()->json(['cartCount' => 3]);
})->name('cart.add');

Route::patch('/cart/update', function() {
    return back();
})->name('cart.update');

Route::delete('/cart/{id}', function($id) {
    return back();
})->name('cart.remove');

Route::delete('/cart/remove-selected', function() {
    return back();
})->name('cart.remove-selected');

Route::post('/cart/coupon', function() {
    return back();
})->name('cart.coupon');

// Checkout routes
Route::post('/checkout/shipping', function() {
    return redirect()->route('checkout.index', ['step' => 1]);
})->name('checkout.shipping');

Route::post('/checkout/payment', function() {
    return redirect()->route('checkout.index', ['step' => 2]);
})->name('checkout.payment');

Route::post('/checkout/place-order', function() {
    return redirect()->route('checkout.index', ['step' => 3]);
})->name('checkout.place-order');

