<?php

use App\Http\Controllers\Admin\{ProductController as AdminProductController,CustomerController as AdminCustomerController,OrderController as AdminOrderController};
use App\Http\Controllers\Customer\{CartController, CheckoutController,OrderController as CustomerOrderController};
use App\Http\Controllers\{ProductController, ProfileController};
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $products = Product::where('active', true)
                        ->latest()
                        ->paginate(20);

    return view('welcome', compact('products'));
})->name('home');

// Public routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment-failed', [CheckoutController::class, 'paymentFailed'])->name('checkout.payment-failed');

    // Paypal Routes
    Route::get('/checkout/paypal/success', [CheckoutController::class, 'paypalSuccess'])->name('checkout.paypal.success');
    Route::get('/checkout/paypal/cancel', [CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');

    // Customer Order Routes
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');


    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function() {
        // Admin Product Management
        Route::resource('products', AdminProductController::class);

        // Admin Customer Management
        Route::resource('customers', AdminCustomerController::class);

        // Admin Order Management
        Route::resource('orders', AdminOrderController::class);
    });
});

require __DIR__.'/auth.php';
