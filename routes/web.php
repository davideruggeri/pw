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

// Rotte per Clienti (Catalogo e Carrello - Solo per utenti loggati con ruolo customer)
Route::middleware(['auth', 'role:customer', 'password.changed'])->group(function () {
    // Catalogo
    Route::get('/catalog', [ProductController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{id}', [ProductController::class, 'show'])->name('catalog.show');
    Route::post('/catalog/{id}/favorite', [ProductController::class, 'toggleFavorite'])->name('catalog.favorite');

    // Dashboard e Funzionalità Cliente
    Route::prefix('customer')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('customer.dashboard');
        Route::get('/orders', [DashboardController::class, 'customerOrders'])->name('customer.orders');
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

Route::middleware(['auth', 'role:logistics', 'password.changed'])->prefix('logistics')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'logistics'])->name('logistics.dashboard');
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