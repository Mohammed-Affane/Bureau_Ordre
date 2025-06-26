<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', UserController::class);
});

/* Route::middleware(['auth', 'role:bo'])->group(function () {
    Route::get('/bo/dashboard', [DashboardController::class, 'index'])->name('bo.dashboard');
    Route::resource('courriers', CourrierController::class);
});

// Similar route groups for cab, sg, and chef_division
Route::middleware(['auth', 'role:cab'])->group(function () {
    Route::get('/cab/dashboard', [DashboardController::class, 'index'])->name('cab.dashboard');
    Route::resource('departements', DepartementController::class);
});

Route::middleware(['auth', 'role:sg'])->group(function () {
    Route::get('/sg/dashboard', [DashboardController::class, 'index'])->name('sg.dashboard');
    Route::resource('services', ServiceController::class);
});

Route::middleware(['auth', 'role:chef_division'])->group(function () {
    Route::get('/chef_division/dashboard', [DashboardController::class, 'index'])->name('chef_division.dashboard');
    Route::resource('sous_departements', SousDepartementController::class);
}); */

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', fn () => view('admin.users'))->name('users');
    Route::get('couriers', fn () => view('admin.couriers'))->name('couriers');
    Route::get('reports', fn () => view('admin.reports'))->name('reports');
    Route::get('settings', fn () => view('admin.settings'))->name('settings');
});

// BO Routes
Route::middleware(['auth', 'role:bo'])->prefix('bo')->name('bo.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.bo'))->name('dashboard');
    Route::get('couriers/create', fn () => view('bo.couriers.create'))->name('couriers.create');
    Route::get('couriers', fn () => view('bo.couriers.index'))->name('couriers.index');
    Route::get('history', fn () => view('bo.history'))->name('history');
});

// Cab Routes
Route::middleware(['auth', 'role:cab'])->prefix('cab')->name('cab.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.cab'))->name('dashboard');
    Route::get('pending', fn () => view('cab.pending'))->name('pending');
    Route::get('assignments', fn () => view('cab.assignments'))->name('assignments');
    Route::get('history', fn () => view('cab.history'))->name('history');
});

// DAI Routes
Route::middleware(['auth', 'role:dai'])->prefix('dai')->name('dai.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.dai'))->name('dashboard');
    Route::get('pending', fn () => view('dai.pending'))->name('pending');
    Route::get('closed', fn () => view('dai.closed'))->name('closed');
});

// SG Routes
Route::middleware(['auth', 'role:sg'])->prefix('sg')->name('sg.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.sg'))->name('dashboard');
    Route::get('pending', fn () => view('sg.pending'))->name('pending');
    Route::get('divisions', fn () => view('sg.divisions'))->name('divisions');
    Route::get('tracking', fn () => view('sg.tracking'))->name('tracking');
});

// Chef Division Routes (Default)
Route::middleware(['auth', 'role:chef_division'])->prefix('division')->name('division.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.division'))->name('dashboard');
    Route::get('pending', fn () => view('division.pending'))->name('pending');
    Route::get('progress', fn () => view('division.progress'))->name('progress');
    Route::get('completed', fn () => view('division.completed'))->name('completed');
});
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

require __DIR__.'/auth.php';
