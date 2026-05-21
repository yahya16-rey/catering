<?php

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('seeded customer name is Dinda Customer', function () {
    // Run seeders
    $this->seed(\Database\Seeders\DatabaseSeeder::class);

    // Verify customer user exists with name Dinda Customer
    $this->assertDatabaseHas('users', [
        'email' => 'customer@dinda.com',
        'name' => 'Dinda Customer',
        'role' => 'customer'
    ]);
});

test('creating menu with nominal values less than 1 fails validation', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post(route('products.store'), [
        'nama_menu' => 'Nasi Goreng Test',
        'kategori' => 'Personal',
        'deskripsi' => 'Nasi goreng lezat',
        'harga' => 0,       // Invalid (< 1)
        'stok' => 0,        // Invalid (< 1)
        'kalori' => 0,      // Invalid (< 1)
        'protein' => 0,     // Invalid (< 1)
        'is_available' => 1
    ]);

    $response->assertSessionHasErrors(['harga', 'stok', 'kalori', 'protein']);
});

test('creating menu with nominal values greater than or equal to 1 passes validation', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post(route('products.store'), [
        'nama_menu' => 'Nasi Goreng Test',
        'kategori' => 'Personal',
        'deskripsi' => 'Nasi goreng lezat',
        'harga' => 15000,
        'stok' => 10,
        'kalori' => 350,
        'protein' => 15,
        'is_available' => 1
    ]);

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHas('successMessage');

    $this->assertDatabaseHas('products', [
        'nama_menu' => 'Nasi Goreng Test',
        'harga' => 15000,
        'stok' => 10,
        'kalori' => 350,
        'protein' => 15
    ]);
});

test('adding item to cart with quantity less than 5 fails validation', function () {
    $product = Product::create([
        'nama_menu' => 'Nasi Ayam Test',
        'kategori' => 'Personal',
        'deskripsi' => 'Deskripsi test',
        'harga' => 15000,
        'stok' => 10,
        'kalori' => 350,
        'protein' => 15,
        'is_available' => true
    ]);
    $user = User::factory()->create(['role' => 'customer']);
    $this->actingAs($user);

    $response = $this->post(route('cart.add', $product->id), [
        'qty' => 4 // Less than 5
    ]);

    $response->assertSessionHas('errorMessage');
    expect(session()->get('cart'))->toBeNull();
});

test('adding item to cart with quantity 5 or more succeeds', function () {
    $product = Product::create([
        'nama_menu' => 'Nasi Ayam Test 2',
        'kategori' => 'Personal',
        'deskripsi' => 'Deskripsi test',
        'harga' => 15000,
        'stok' => 10,
        'kalori' => 350,
        'protein' => 15,
        'is_available' => true
    ]);
    $user = User::factory()->create(['role' => 'customer']);
    $this->actingAs($user);

    $response = $this->post(route('cart.add', $product->id), [
        'qty' => 5
    ]);

    $response->assertSessionHas('successMessage');
    expect(session()->get('cart'))->toHaveKey($product->id);
    expect(session()->get('cart')[$product->id]['qty'])->toBe(5);
});

