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
use App\Http\Controllers\CAB\CabCourrierController;
use App\Http\Controllers\SG\SgCourrierController;
use App\Http\Controllers\CabDashboardController;

//test notification
use App\Notifications\NewCourrierNotification;
use App\Models\Courrier;

    Route::get('/', function () {
        return view('welcome');
    });

Route::middleware(['auth'])->group(function () {
    Route::resource('courriers', CourrierController::class)->except(['create','store']);
    Route::get('courriers.create',[CourrierController::class,'create'])->name('courriers.create')->middleware('role:bo');
    Route::post('courriers.store',[CourrierController::class,'store'])->name('courriers.store')->middleware('role:bo'); 
    
//-------affectation route 

    Route::get('/courriers/{courrier}/destinataires', [CourrierController::class, 'showDestinataires'])
    ->name('courriers.destinataires');

    Route::get('/courriers/{courrier}/affecte', [CourrierController::class, 'showAffectations'])
    ->name('courriers.affecte');

    Route::get('courriers.arrive', [TypeCourrierController::class, 'courrierArrivee'])->name('courriers.arrive')->middleware('role:bo|cab|sg');
    Route::get('courriers.depart', [TypeCourrierController::class, 'courrierDepart'])->name('courriers.depart')->middleware('role:bo|cab|sg');
    Route::get('courriers.interne', [TypeCourrierController::class, 'courrierInterne'])->name('courriers.interne')->middleware('role:bo|cab|sg');
    Route::get('courriers.visa', [TypeCourrierController::class, 'courrierVisa'])->name('courriers.visa')->middleware('role:bo|cab|sg');
    Route::get('courriers.decision', [TypeCourrierController::class, 'courrierDecision'])->name('courriers.decision')->middleware('role:bo|cab|sg');
    Route::get('courriers.search', [TypeCourrierController::class, 'searchCourrier'])->name('courriers.search');

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
    Route::get('statistics', [AdminDashboardController::class, 'statistics'])->name('statistics');
});

// BO Routes
Route::middleware(['auth', 'role:bo'])->prefix('bo')->name('bo.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.bo.index'))->name('dashboard');
});


// Cab Routes
Route::middleware(['auth', 'role:cab'])->prefix('cab')->name('cab.')->group(function () {
    // Dashboard principal
    Route::get('dashboard', [CabDashboardController::class, 'index'])->name('dashboard');
    
    // API pour les données des graphiques
    Route::get('chart-data', [CabDashboardController::class, 'getChartDataApi'])->name('chart-data');
    
    // API pour les statistiques en temps réel
    Route::get('realtime-stats', [CabDashboardController::class, 'getRealtimeStats'])->name('realtime-stats');
    
    // Export des rapports
    Route::get('export', [CabDashboardController::class, 'exportReport'])->name('export');
    
    // Voir tous les courriers avec filtres
    Route::get('courriers', [CabDashboardController::class, 'allCourriers'])->name('courriers.all');
    
    // Routes existantes pour les courriers par type
    Route::get('courriers.interne', [CabCourrierController::class, 'cabCourrierInterne'])->name('courriers.interne');
    Route::get('courriers.arrive', [CabCourrierController::class, 'cabCourrierArrive'])->name('courriers.arrive');
    
    // Filtres et données supplémentaires (optionnelles)
    Route::get('filter-data', [CabDashboardController::class, 'getFilteredData'])->name('filter-data');
    Route::get('department-details/{department}', [CabDashboardController::class, 'getDepartmentDetails'])->name('department-details');

    // Traitements For cab sidebar
    Route::get('traitements.arrive', [SgCourrierController::class, 'recusTraitement'])->name('traitements.arrive');
});

// DAI Routes
Route::middleware(['auth', 'role:dai'])->prefix('dai')->name('dai.')->group(function () {
    Route::get('courriers.interne',[\App\Http\Controllers\DAI\DaiCourrierController::class,'daiCourrierInterne'])->name('courriers.interne');
    Route::get('courriers.arrive',[\App\Http\Controllers\DAI\DaiCourrierController::class,'daiCourrierArrive'])->name('courriers.arrive');
});

// SG Routes
Route::middleware(['auth', 'role:sg'])->prefix('sg')->name('sg.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\SgDashboardController::class, 'index'])->name('dashboard');
    Route::get('courriers.interne',[SgCourrierController::class,'sgCourrierInterne'])->name('courriers.interne');
    Route::get('courriers.arrive',[SgCourrierController::class,'sgCourrierArrive'])->name('courriers.arrive');
// Traitements
    Route::get('traitements.arrive', [SgCourrierController::class, 'recusTraitement'])->name('traitements.arrive');
//cloture courrier 
    Route::post('/courriers/{id}/cloturer', [SgCourrierController::class, 'cloturerCourrier'])->name('courriers.cloturer');

});

// Chef Division Routes (Default)
Route::middleware(['auth', 'role:chef_division'])->prefix('division')->name('division.')->group(function () {
    Route::get('dashboard', fn () => view('dashboards.division.index'))->name('dashboard');
    Route::get('courriers', [DivisionCourrierController::class, 'index'])->name('index');
    Route::get('courriers.interne',[DivisionCourrierController::class,'divisionCourrierInterne'])->name('courriers.interne');
    Route::get('courriers.arrive',[DivisionCourrierController::class,'divisionCourrierArrive'])->name('courriers.arrive');
    Route::get('/affectations/{affectation}/traiter', [DivisionCourrierController::class, 'showTraitement'])
         ->name('affectations.traitement.show');
    Route::post('/affectations/{affectation}/traiterstore', [DivisionCourrierController::class, 'storeTraitement'])
         ->name('affectations.traitement.store');
});

// Test Notification Routes

Route::get('/test-notification', function () {
    $user = auth()->user();
    
    // Get or create a test courrier
    $courrier = Courrier::first() ?? Courrier::create([
        'reference' => 'TEST-' . rand(1000, 9999),
        'user_id' => $user->id,
        // Add other required fields
    ]);
    
    // Send notification
    $user->notify(new NewCourrierNotification($courrier));
    
    return 'Notification sent! Check your dashboard.';
})->middleware('auth');


Route::get('/user/notifications', function () {
    return auth()->user()->notifications->map(function($n) {
        return [
            'id' => $n->id,
            'message' => $n->data['message'] ?? '',
            'courrier_id' => $n->data['courrier_id'] ?? '',
        ];
    });
})->middleware('auth');

require __DIR__.'/auth.php';