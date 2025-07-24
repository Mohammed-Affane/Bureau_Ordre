<?php


use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EntiteController;
use App\Http\Controllers\Affectations\AffectationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Courriers\CourrierController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Courriers\TypeCourrierController;
use App\Http\Controllers\Exports\ExportCourrierController;
use App\Http\Controllers\Division\DivisionCourrierController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('courriers', CourrierController::class);
    
//-------affectation route 
//   Route::get('courriers.affecte', [CourrierController::class, 'affecte'])->name('courriers.affecte');

    Route::get('/courriers/{courrier}/destinataires', [CourrierController::class, 'showDestinataires'])
    ->name('courriers.destinataires');

    Route::get('courriers.arrive', [TypeCourrierController::class, 'courrierArrivee'])->name('courriers.arrive');
    Route::get('courriers.depart', [TypeCourrierController::class, 'courrierDepart'])->name('courriers.depart');
    Route::get('courriers.interne', [TypeCourrierController::class, 'courrierInterne'])->name('courriers.interne');
    Route::get('courriers.visa', [TypeCourrierController::class, 'courrierVisa'])->name('courriers.visa');
    Route::get('courriers.decision', [TypeCourrierController::class, 'courrierDecision'])->name('courriers.decision');
    Route::get('courriers.search', [TypeCourrierController::class, 'searchCourrier'])->name('courriers.search');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::get('/courriers/{courrier}/affectations', [AffectationController::class, 'create'])->name('affectations.create');
    Route::post('/courriers/{courrier}/affectations', [AffectationController::class, 'store'])->name('affectations.store');

    
    Route::prefix('export')->group(function () {
        
        Route::get('/courriers/{type}/pdf', [ExportCourrierController::class, 'exportPdf'])
            ->name('export.courriers.pdf');
        Route::get('/courriers/{type}/excel', [ExportCourrierController::class, 'exportExcel'])
            ->name('export.courriers.excel');

});
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard',[AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('entites', EntiteController::class);
    Route::resource('permissions', PermissionController::class);

    Route::get('couriers', fn () => view('dashboards.admin.courriers.index'))->name('courriers');
    Route::get('reports', fn () => view('admin.reports'))->name('reports');
    Route::get('settings', fn () => view('admin.settings'))->name('settings');
});

// BO Routes
Route::middleware(['auth', 'role:bo'])->prefix('bo')->name('bo.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.bo.index'))->name('dashboard');
    Route::get('history', fn () => view('bo.history'))->name('history');

});

// Cab Routes
Route::middleware(['auth', 'role:cab'])->prefix('cab')->name('cab.')->group(function () {

    Route::get('dashboard', fn () => view('dashboards.cab.index'))->name('dashboard');
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
    Route::get('dashboard', fn () => view('dashboards.sg.index'))->name('dashboard');
    Route::get('pending', fn () => view('sg.pending'))->name('pending');
    Route::get('divisions', fn () => view('sg.divisions'))->name('divisions');
    Route::get('tracking', fn () => view('sg.tracking'))->name('tracking');
});

// Chef Division Routes (Default)
Route::middleware(['auth', 'role:chef_division'])->prefix('division')->name('division.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.division.index'))->name('dashboard');
    Route::get('pending', fn () => view('division.pending'))->name('pending');
    Route::get('progress', fn () => view('division.progress'))->name('progress');
    Route::get('completed', fn () => view('division.completed'))->name('completed');
    Route::get('courriers', [DivisionCourrierController::class, 'index'])->name('index');
});

require __DIR__.'/auth.php';





























































