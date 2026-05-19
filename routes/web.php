<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\AiRecommendationController;
use App\Http\Controllers\PaymentController;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

// Public Customer Routes
Route::get('/', [HomepageController::class, 'index'])->name('home');
Route::get('/menu', [HomepageController::class, 'products'])->name('menu.list');
Route::get('/menu/{id}', [HomepageController::class, 'show'])->name('menu.detail');
Route::get('/about', [HomepageController::class, 'about'])->name('about');

// Cart Routes
Route::get('/cart', [HomepageController::class, 'cart'])->name('cart.index');
Route::post('/cart/add/{id}', [HomepageController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [HomepageController::class, 'updateCart'])->name('cart.update');
Route::get('/cart/remove/{id}', [HomepageController::class, 'removeFromCart'])->name('cart.remove');

// Customer Auth Routes
Route::group(['prefix' => 'customer'], function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [CustomerAuthController::class, 'login'])->name('customer.login');
        Route::post('login', [CustomerAuthController::class, 'store_login'])->name('customer.store_login');
        Route::get('register', [CustomerAuthController::class, 'register'])->name('customer.register');
        Route::post('register', [CustomerAuthController::class, 'store_register'])->name('customer.store_register');
    });
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('customer.logout')->middleware('auth');
});

// Authenticated Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/checkout', [HomepageController::class, 'checkout'])->name('checkout');
    Route::post('/order/submit', [OrderController::class, 'submit'])->name('order.submit');
    
    // AI Recommendation
    Route::get('/recommend', [AiRecommendationController::class, 'index'])->name('recommend.index');
    Route::post('/recommend', [AiRecommendationController::class, 'recommend'])->name('recommend.submit');
});

// Midtrans Callbacks
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

// Admin Dashboard Routes
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductsController::class);
    Route::resource('orders', OrderController::class);
    
    Route::get('/orders/export', function (Request $request) {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));
        return Excel::download(new OrdersExport($month, $year), 'orders-' . $month . '-' . $year . '.xlsx');
    })->name('orders.export');
});

// Fallback login redirect for laravel auth middleware
Route::get('/login-redirect', function () {
    return redirect()->route('customer.login');
})->name('login');
