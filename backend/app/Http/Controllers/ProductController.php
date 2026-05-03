<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{
    public function home()
    {
        $heroSlides = [
            [
                'badge' => 'NEW ARRIVAL',
                'title' => 'iPhone 15 Pro Max',
                'subtitle' => 'Up to 70% off retail price',
                'cta' => 'Shop Now',
                'bg' => 'linear-gradient(135deg,#1a0533,#6C3EE8,#a855f7)',
                'accent' => '#6C3EE8'
            ],
            [
                'badge' => 'FLASH SALE',
                'title' => 'Samsung Galaxy S25',
                'subtitle' => 'Limited time offer',
                'cta' => 'Grab Deal',
                'bg' => 'linear-gradient(135deg,#E8192C,#FF6B35)',
                'accent' => '#E8192C'
            ]
        ];

        $sidebarCategories = [
            ['icon' => '📱', 'label' => 'Electronics'],
            ['icon' => '👗', 'label' => 'Fashion'],
            ['icon' => '💄', 'label' => 'Beauty'],
            ['icon' => '🏠', 'label' => 'Home'],
            ['icon' => '⚽', 'label' => 'Sports']
        ];

        $quickCats = [
            ['icon' => '📱', 'label' => 'Phones'],
            ['icon' => '💻', 'label' => 'Laptops'],
            ['icon' => '👕', 'label' => 'Clothing'],
            ['icon' => '👟', 'label' => 'Shoes'],
            ['icon' => '⌚', 'label' => 'Watches'],
            ['icon' => '🎮', 'label' => 'Gaming']
        ];

        $brands = [
            ['name' => 'Apple', 'bg' => '#000'],
            ['name' => 'Samsung', 'bg' => '#1428A0'],
            ['name' => 'Nike', 'bg' => '#FF6B35'],
            ['name' => 'Adidas', 'bg' => '#000'],
            ['name' => 'Sony', 'bg' => '#000'],
            ['name' => 'LG', 'bg' => '#A50034']
        ];

        $featuredStores = [
            ['name' => 'TechHub', 'logo' => '📱', 'rating' => 4.8, 'items' => 1250, 'verified' => true],
            ['name' => 'FashionZone', 'logo' => '👗', 'rating' => 4.6, 'items' => 890, 'verified' => true],
            ['name' => 'GamingWorld', 'logo' => '🎮', 'rating' => 4.9, 'items' => 567, 'verified' => false],
            ['name' => 'HomePlus', 'logo' => '🏠', 'rating' => 4.5, 'items' => 2340, 'verified' => true]
        ];

        $partnerStores = [
            ['name' => 'Apple Store', 'logo' => '🍎', 'discount' => 'Up to 20% off', 'color' => '#F5F5F7'],
            ['name' => 'Samsung', 'logo' => '📱', 'discount' => 'Special offers', 'color' => '#1428A0'],
            ['name' => 'Nike', 'logo' => '✔', 'discount' => 'New collection', 'color' => '#FF6B35']
        ];

        // Sample products
        $createProduct = function($id, $title, $price, $emoji = '📱') {
            return [
                'id' => $id,
                'title' => $title,
                'price' => $price,
                'discount' => rand(0, 30) > 20 ? '-' . rand(10, 30) . '%' : null,
                'rating' => rand(35, 50) / 10,
                'sold' => rand(10, 500),
                'imageHue' => rand(0, 360),
                'emoji' => $emoji,
                'inStock' => rand(0, 10) > 2,
                'location' => 'Phnom Penh',
                'storeName' => 'TechStore'
            ];
        };

        $recommended = [];
        $trending = [];
        $moreProducts = [];

        for ($i = 1; $i <= 8; $i++) {
            $recommended[] = $createProduct($i, "Product $i", rand(50, 300), ['📱', '💻', '👕', '👟'][$i % 4]);
        }
        for ($i = 9; $i <= 15; $i++) {
            $trending[] = $createProduct($i, "Product $i", rand(100, 500), ['📱', '💻', '👕', '👟'][$i % 4]);
        }
        for ($i = 16; $i <= 20; $i++) {
            $moreProducts[] = $createProduct($i, "Product $i", rand(80, 400), ['📱', '💻', '👕', '👟'][$i % 4]);
        }

        return view('home', compact(
            'heroSlides', 'sidebarCategories', 'quickCats', 'brands',
            'featuredStores', 'partnerStores', 'recommended', 'trending', 'moreProducts'
        ));
    }

    public function category(Request $request)
    {
        $subcategories = [
            ['emoji' => '📱', 'label' => 'Smartphones'],
            ['emoji' => '💻', 'label' => 'Laptops'],
            ['emoji' => '⌚', 'label' => 'Watches'],
            ['emoji' => '🎧', 'label' => 'Headphones'],
            ['emoji' => '📷', 'label' => 'Cameras']
        ];

        $priceRanges = [
            ['label' => 'Under $50', 'min' => 0, 'max' => 50],
            ['label' => '$50-$100', 'min' => 50, 'max' => 100],
            ['label' => '$100-$300', 'min' => 100, 'max' => 300],
            ['label' => 'Over $300', 'min' => 300, 'max' => 9999]
        ];

        $sortOptions = ['Latest', 'Price Low to High', 'Price High to Low', 'Best Selling'];

        $createProduct = function($id) {
            return [
                'id' => $id,
                'title' => "Product $id",
                'price' => rand(20, 500),
                'discount' => rand(0, 30) > 20 ? '-' . rand(10, 30) . '%' : null,
                'rating' => rand(35, 50) / 10,
                'sold' => rand(10, 500),
                'imageHue' => rand(0, 360),
                'emoji' => ['📱', '💻', '👕', '👟'][$id % 4],
                'inStock' => rand(0, 10) > 2,
                'location' => 'Phnom Penh'
            ];
        };

        $products = [];
        for ($i = 1; $i <= 20; $i++) {
            $products[] = $createProduct($i);
        }

        return view('category', compact(
            'subcategories', 'priceRanges', 'sortOptions', 'products'
        ));
    }

    public function cart()
    {
        $cartItems = [];
        for ($i = 1; $i <= 3; $i++) {
            $cartItems[] = [
                'id' => $i,
                'title' => "Product $i",
                'price' => rand(20, 100),
                'qty' => rand(1, 3),
                'imageHue' => rand(0, 360),
                'emoji' => ['📱', '💻', '👕'][$i % 3],
                'storeName' => 'TechStore',
                'discount' => rand(0, 30) > 20 ? '-' . rand(10, 20) . '%' : null
            ];
        }

        return view('cart', compact('cartItems'));
    }

    public function checkout(Request $request)
    {
        $step = (int) $request->get('step', 0);
        
        $cartItems = [];
        for ($i = 1; $i <= 3; $i++) {
            $cartItems[] = [
                'id' => $i,
                'title' => "Product $i",
                'price' => rand(20, 100),
                'qty' => rand(1, 3),
                'imageHue' => rand(0, 360),
                'emoji' => ['📱', '💻', '👕'][$i % 3],
                'discount' => rand(0, 30) > 20 ? '-' . rand(10, 20) . '%' : null
            ];
        }

        $shipping = session('shipping_address', []);

        return view('checkout', compact('cartItems', 'step', 'shipping'));
    }
}