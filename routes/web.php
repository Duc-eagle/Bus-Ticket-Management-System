<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\BusStationController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', [RouteController::class, 'homepage'])->name('home');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/bcrypt', function () {
    return bcrypt('12345678');
});

// API Routes
Route::get('/api/trips/{trip}/seats', [TripController::class, 'getSeats'])->name('api.trips.seats');


// ==========================================
// CUSTOMER ROUTES
// ==========================================
Route::prefix('customer')->group(function () {
    
    // Public Customer Routes
    // Maps exactly to the previous Route::get('/customer', ...)->name('home');
    Route::get('/', [RouteController::class, 'homepage'])->name('home');
    
    // Grouping the previous Route::get('/customer/search', ...)->name('customer.search');
    Route::get('/search', [RouteController::class, 'search'])->name('customer.search');
    
    // Grouping the previous Route::get('/customer/routes/{route}/trips', ...)->name('customer.routes.trips');
    Route::get('/routes/{route}/trips', [RouteController::class, 'customerTrips'])->name('customer.routes.trips');
    
    // Auth Routes
    Route::get('/login', [UserController::class, 'customerLogin'])->name('customer.login');
    Route::post('/login', [UserController::class, 'customerLoginProcess'])->name('customer.login.process');
    Route::post('/logout', [UserController::class, 'customerLogout'])->name('customer.logout');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Authenticated Customer Routes
    Route::middleware('auth')->group(function () {
        Route::get('/account', [UserController::class, 'customerAccount'])->name('customer.account');
        Route::get('/change-password', [UserController::class, 'changePasswordView'])->name('customer.change-password');
        Route::post('/change-password', [UserController::class, 'changePasswordProcess'])->name('customer.change-password.store');
        
        Route::get('/dashboard', function () {
            return view('customer.dashboard');
        })->name('customer.dashboard');

        Route::get('/checkout', function () {
            return redirect()->route('home')->with('error', 'Vui lòng chọn chuyến đi và ghế trước khi truy cập trang thanh toán.');
        })->name('checkout.fallback');
        Route::post('/checkout', [CheckoutController::class, 'index'])->name('customer.checkout');
        Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('customer.checkout.process');
        Route::get('/checkout/retry/{ticket_name}', [CheckoutController::class, 'retryPayment'])->name('checkout.retry');
        Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('customer.booking_success');
        
        Route::get('/history', [UserController::class, 'history'])->name('customer.history');
        Route::get('/tickets/{id}', [UserController::class, 'show'])->name('customer.ticket.show');
        Route::post('/tickets/{id}/cancel', [UserController::class, 'cancel'])->name('customer.ticket.cancel');
        
        Route::get('/payment/zalopay-return', [CheckoutController::class, 'zaloPayReturn'])->name('payment.zalopay_return');
    });
});


// ==========================================
// ADMIN ROUTES
// ==========================================

// Admin Auth Routes (Moved to /admin prefix)
Route::prefix('admin')->group(function () {
    Route::get('/login', [UserController::class, 'adminsLogin'])->name('admins.login');
    Route::post('/login', [UserController::class, 'adminsLoginProcess'])->name('admins.loginProcess');
    Route::post('/logout', [UserController::class, 'adminsLogout'])->name('admins.logout');
});

// Admin Authenticated Routes
Route::middleware('authAdmin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Profile & Password Change
    Route::get('/profile', [\App\Http\Controllers\AdminProfileController::class, 'index'])->name('admin.profile');
    Route::post('/profile/password', [\App\Http\Controllers\AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');

    Route::middleware('role:admin')->group(function () {
        // Resources
        Route::resource('provinces', ProvinceController::class);
        
        // Kebab-case URL for Bus Stations (/admin/bus-stations) but preserving names (bus_stations.index)
        Route::resource('bus-stations', BusStationController::class)
            ->names('bus_stations')
            ->parameters(['bus-stations' => 'bus_station']);

        Route::resource('routes', RouteController::class)->except(['show']);
        Route::resource('buses', BusController::class);
        Route::get('buses/{id}/seats', [SeatController::class, 'showByBus'])->name('admin.seats.by_bus');
        Route::resource('seats', SeatController::class);
        Route::resource('trips', TripController::class)->except(['show']);
        
        Route::resource('payment-methods', PaymentMethodController::class)
            ->names('paymentMethods')
            ->parameters(['payment-methods' => 'paymentMethod']);

        Route::get('users/check-phone', [UserController::class, 'checkPhone'])->name('users.checkPhone');
        Route::get('users/customers', [UserController::class, 'customers'])->name('admin.users.customers');
        Route::get('users/staff', [UserController::class, 'staff'])->name('admin.users.staff');
        Route::resource('users', UserController::class);
    });

    Route::get('tickets/{ticket_name}/detail', [TicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('tickets/cancel-seat/{id}', [TicketController::class, 'cancelSeat'])->name('admin.tickets.cancel_seat');
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
});
