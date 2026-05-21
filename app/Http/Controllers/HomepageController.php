<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomepageController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)->take(3)->get();
        return view('web.homepage', [
            'products' => $products,
            'title' => 'Dinda Catering'
        ]);
    }

    public function products(Request $request)
    {
        $query = Product::where('is_available', true);

        // Search
        if ($request->filled('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('kategori', $request->category);
        }

        $products = $query->paginate(9);

        return view('web.products', [
            'products' => $products,
            'title' => 'Menu List'
        ]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('web.product', [
            'product' => $product,
            'title' => $product->nama_menu
        ]);
    }

    public function about()
    {
        return view('web.about', [
            'title' => 'Tentang Kami'
        ]);
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('web.cart', [
            'cart' => $cart,
            'title' => 'Keranjang Belanja'
        ]);
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        $qty = (int) $request->input('qty', 5);

        if ($qty < 5) {
            return redirect()->back()->with('errorMessage', 'Pemesanan minimal 5 porsi untuk hidangan katering.');
        }

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'name' => $product->nama_menu,
                'qty' => $qty,
                'price' => $product->harga,
                'image' => $product->gambar,
                'kategori' => $product->kategori,
                'kalori' => $product->kalori,
                'protein' => $product->protein,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('successMessage', 'Menu berhasil ditambahkan ke keranjang!');
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->qty) {
            $cart = session()->get('cart');
            $cart[$request->id]['qty'] = $request->qty;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('successMessage', 'Menu dihapus dari keranjang.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.list')->with('errorMessage', 'Keranjang belanja Anda kosong.');
        }

        return view('web.checkout', [
            'cart' => $cart,
            'title' => 'Checkout'
        ]);
    }
}
