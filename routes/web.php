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
Route::middleware(['auth', 'role:admin,sales,logistics,production', 'password.changed'])->group(function () {
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

Route::middleware(['auth', 'role:sales', 'password.changed'])->prefix('sales')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'sales'])->name('sales.dashboard');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
});


Route::middleware(['auth', 'role:admin,production', 'password.changed'])->prefix('production')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductionController::class, 'index'])->name('production.index');
    Route::get('/create', [App\Http\Controllers\ProductionController::class, 'create'])->name('production.create');
    Route::post('/store', [App\Http\Controllers\ProductionController::class, 'store'])->name('production.store');
    Route::get('/history', [App\Http\Controllers\ProductionController::class, 'history'])->name('production.history');
});

Route::middleware(['auth', 'role:admin,production', 'password.changed'])->prefix('maintenance')->group(function () {
    Route::get('/', [App\Http\Controllers\MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/create', [App\Http\Controllers\MaintenanceController::class, 'create'])->name('maintenance.create');
    Route::post('/store', [App\Http\Controllers\MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::get('/history', [App\Http\Controllers\MaintenanceController::class, 'history'])->name('maintenance.history');
});

Route::middleware(['auth', 'role:admin,production', 'password.changed'])->prefix('quality')->group(function () {
    Route::get('/', [App\Http\Controllers\QualityController::class, 'index'])->name('quality.index');
    Route::get('/create', [App\Http\Controllers\QualityController::class, 'create'])->name('quality.create');
    Route::post('/store', [App\Http\Controllers\QualityController::class, 'store'])->name('quality.store');
    Route::get('/history', [App\Http\Controllers\QualityController::class, 'history'])->name('quality.history');
});

Route::middleware(['auth', 'role:admin,logistics', 'password.changed'])->prefix('logistics')->group(function () {
    Route::get('/', [App\Http\Controllers\LogisticsController::class, 'index'])->name('logistics.index');
    Route::post('/update-stock', [App\Http\Controllers\LogisticsController::class, 'updateStock'])->name('logistics.update-stock');
});

Route::middleware(['auth', 'role:production', 'password.changed'])->prefix('operations')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'operations'])->name('production.dashboard');
});

// Rotte Placeholder per funzionalità in sviluppo
Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/inventory', fn() => redirect()->route('coming-soon', ['feature' => 'Magazzino']))->name('inventory.index');
    Route::get('/orders-list', fn() => redirect()->route('coming-soon', ['feature' => 'Elenco Ordini']))->name('orders.index');
    Route::get('/production-tracking', fn() => redirect()->route('coming-soon', ['feature' => 'Tracciamento Produzione']))->name('production.index');
    Route::get('/analytics/reports', fn() => redirect()->route('coming-soon', ['feature' => 'Report Analitici']))->name('reports.index');
});