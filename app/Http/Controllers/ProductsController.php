<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('nama_menu', 'like', '%' . $request->q . '%')
                    ->orWhere('kategori', 'like', '%' . $request->q . '%');
            })
            ->paginate(10);

        return view('dashboard.products.index', [
            'products' => $products,
            'q' => $request->q
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:1',
            'stok' => 'required|integer|min:1',
            'kalori' => 'required|integer|min:1',
            'protein' => 'required|integer|min:1',
            'is_available' => 'boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('errorMessage', 'Validasi gagal. Harap periksa inputan Anda.');
        }

        $data = $request->only(['nama_menu', 'kategori', 'deskripsi', 'harga', 'stok', 'kalori', 'protein']);
        $data['is_available'] = $request->has('is_available') ? true : false;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            // Store directly in public/images for simple previewing and reference
            $image->move(public_path('images'), $imageName);
            $data['gambar'] = $imageName;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('successMessage', 'Menu catering berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('dashboard.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:1',
            'stok' => 'required|integer|min:1',
            'kalori' => 'required|integer|min:1',
            'protein' => 'required|integer|min:1',
            'is_available' => 'boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('errorMessage', 'Validasi gagal. Harap periksa inputan Anda.');
        }

        $product = Product::findOrFail($id);
        $data = $request->only(['nama_menu', 'kategori', 'deskripsi', 'harga', 'stok', 'kalori', 'protein']);
        $data['is_available'] = $request->has('is_available') ? true : false;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->gambar && file_exists(public_path('images/' . $product->gambar))) {
                @unlink(public_path('images/' . $product->gambar));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $data['gambar'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('successMessage', 'Menu catering berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->gambar && file_exists(public_path('images/' . $product->gambar))) {
            @unlink(public_path('images/' . $product->gambar));
        }

        $product->delete();

        return redirect()->route('products.index')->with('successMessage', 'Menu catering berhasil dihapus.');
    }
}