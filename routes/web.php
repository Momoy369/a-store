<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\Admin\UserController;

// Profil Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
});

// Store Front
Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/product/{slug}', [StoreController::class, 'show'])->name('store.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Midtrans webhook
Route::post('/midtrans/webhook', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// Customer Auth
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:customer')->group(function () {
        Route::get('/dashboard', fn() => view('customer.dashboard'))->name('dashboard');
    });
});

// Admin Panel
// routes/web.php

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('discounts', DiscountController::class);
    Route::resource('users', UserController::class);

    Route::get('/admin/products/get-subcategories/{categoryId}', [ProductController::class, 'getSubcategories']);
    Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

    Route::get('products/{product}/variants/edit', [ProductController::class, 'editVariants'])->name('admin.products.variants.edit');
    Route::patch('products/{product}/variants/{variant}', [ProductController::class, 'updateVariant'])->name('admin.products.variants.update');
    Route::delete('products/{product}/variants/{variant}', [ProductController::class, 'destroyVariant'])->name('admin.products.variants.destroy');


    // Pastikan nama route ini sesuai dengan yang digunakan di controller dan tampilan
    Route::get('/low-stock', [ProductController::class, 'lowStock'])->name('low-stock');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/export-pdf', [OrderController::class, 'exportPdf'])->name('admin.orders.exportPdf');
});


// Auth scaffolding
require __DIR__ . '/auth.php';
