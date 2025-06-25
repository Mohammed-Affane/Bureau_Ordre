<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::resource('users', UserController::class);
});

Route::middleware(['auth', 'role:bo'])->group(function () {
    Route::get('/bo/dashboard', [DashboardController::class, 'boDashboard'])->name('bo.dashboard');
    Route::resource('courriers', CourrierController::class);
});

// Similar route groups for cab, sg, and chef_division
Route::middleware(['auth', 'role:cab'])->group(function () {
    Route::get('/cab/dashboard', [DashboardController::class, 'cabDashboard'])->name('cab.dashboard');
    Route::resource('departements', DepartementController::class);
});

Route::middleware(['auth', 'role:sg'])->group(function () {
    Route::get('/sg/dashboard', [DashboardController::class, 'sgDashboard'])->name('sg.dashboard');
    Route::resource('services', ServiceController::class);
});

Route::middleware(['auth', 'role:chef_division'])->group(function () {
    Route::get('/chef_division/dashboard', [DashboardController::class, 'chefDivisionDashboard'])->name('chef_division.dashboard');
    Route::resource('sous_departements', SousDepartementController::class);
});


require __DIR__.'/auth.php';
