<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\DebugController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ComingSoonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/coming-soon/{feature?}', [ComingSoonController::class, 'index'])->name('coming-soon');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rotte Pubbliche per Clienti (Accessibili anche come Ospite)
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{id}', [ProductController::class, 'show'])->name('catalog.show');
Route::get('/customer/dashboard', [DashboardController::class, 'customer'])->name('customer.dashboard');

// Rotte per Clienti (Funzionalità protette - Solo per utenti loggati con ruolo customer)
Route::middleware(['auth', 'role:customer', 'password.changed'])->group(function () {
    // Funzionalità Cliente
    Route::prefix('customer')->group(function () {
        Route::post('/catalog/{id}/favorite', [ProductController::class, 'toggleFavorite'])->name('catalog.favorite');
        Route::get('/orders', [DashboardController::class, 'customerOrders'])->name('customer.orders');
        Route::get('/orders/{id}', [DashboardController::class, 'customerOrderShow'])->name('customer.orders.show');
        Route::get('/favorites', [DashboardController::class, 'customerFavorites'])->name('customer.favorites');
        Route::get('/cart', [DashboardController::class, 'customerCart'])->name('customer.cart');
        Route::post('/cart/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    });
});

// Autenticazione Specifica
Route::get('/login/customer', [LoginController::class, 'showCustomerLoginForm'])->name('login.customer');
Route::get('/login/staff', [LoginController::class, 'showStaffLoginForm'])->name('login.staff');

// Rotte standard Laravel (per compatibilità middleware)
Route::get('/login', fn() => redirect()->route('login.customer'))->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registrazione (Solo Clienti)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Gestione Account
Route::get('/account', [AccountController::class, 'index'])->name('account.index');

Route::middleware(['auth'])->prefix('account')->group(function () {
    Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.update-password');
});

// Debug Role Switcher (Rimuovere in produzione)
Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/debug/roles', [DebugController::class, 'showRoleSelector'])->name('debug.role-selector');
    Route::post('/debug/switch-role/{role}', [DebugController::class, 'switchRole'])->name('debug.switch-role');
});

// Gestione Dipendenti (Accessibile a Admin e Manager di reparto)
Route::middleware(['auth', 'role:admin,sales,logistics', 'password.changed'])->group(function () {
    Route::resource('employees', EmployeeController::class)->names([
        'index' => 'employees.index',
        'create' => 'employees.create',
        'store' => 'employees.store',
        'edit' => 'employees.edit',
        'update' => 'employees.update',
        'destroy' => 'employees.destroy',
    ]);
});

// Gestione Reparti
Route::middleware(['auth', 'role:admin', 'password.changed'])->prefix('admin')->group(function () {
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::post('/departments/{id}/responsabile', [DepartmentController::class, 'setResponsabile'])->name('departments.set-responsabile');
});

// Dashboard protette
Route::middleware(['auth', 'role:admin,sales', 'password.changed'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'role:sales,admin', 'password.changed'])->prefix('sales')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'sales'])->name('sales.dashboard');
    Route::get('/orders/pending', [OrderController::class, 'pending'])->name('orders.pending');
    Route::post('/orders/{id}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{id}/reject', [OrderController::class, 'reject'])->name('orders.reject');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
});




Route::middleware(['auth', 'role:admin,logistics', 'password.changed'])->prefix('logistics')->group(function () {
    Route::get('/', [App\Http\Controllers\LogisticsController::class, 'index'])->name('logistics.index');
    Route::get('/inventory', [App\Http\Controllers\LogisticsController::class, 'inventory'])->name('logistics.inventory');
    Route::get('/replenishment', [App\Http\Controllers\LogisticsController::class, 'replenishment'])->name('logistics.replenishment');
    Route::get('/update', [App\Http\Controllers\LogisticsController::class, 'updateForm'])->name('logistics.update');
    Route::post('/update-stock', [App\Http\Controllers\LogisticsController::class, 'updateStock'])->name('logistics.update-stock');
});


// Rotte aggiuntive protette
Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/admin/inventory', [App\Http\Controllers\LogisticsController::class, 'inventory'])->name('inventory.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/ship', [OrderController::class, 'ship'])->name('orders.ship');
    
    // Notifiche
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.readAll');
});