<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CenterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Agent\ActController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\SettingsController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('accueil');

// Authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes admin protégées
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('centers', CenterController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
    Route::resource('users', UserController::class);
    Route::post('/regions', [CenterController::class, 'storeRegion'])->name('regions.store');
    Route::put('/regions/{region}', [CenterController::class, 'updateRegion'])->name('regions.update');
    Route::post('/departments', [CenterController::class, 'storeDepartment'])->name('departments.store');
    Route::put('/departments/{department}', [CenterController::class, 'updateDepartment'])->name('departments.update');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::post('/settings', [UserController::class, 'updateSettings'])->name('settings.update');

    // Routes pour les documents (actes)
    Route::get('/documents/births', [DocumentController::class, 'births'])->name('documents.births');
    Route::get('/documents/marriages', [DocumentController::class, 'marriages'])->name('documents.marriages');
    Route::get('/documents/deaths', [DocumentController::class, 'deaths'])->name('documents.deaths');
    Route::get('/documents/divorces', [DocumentController::class, 'divorces'])->name('documents.divorces');
    Route::get('/documents/{act}/pdf', [DocumentController::class, 'generatePdf'])->name('documents.pdf');

// Dans le groupe admin
Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');

    // Routes pour les rapports
    Route::get('/reports/statistics', [ReportController::class, 'statistics'])->name('reports.statistics');
    Route::get('/reports/exports', [ReportController::class, 'exports'])->name('reports.exports');
});

// Routes superviseur protégées
Route::prefix('supervisor')->name('supervisor.')->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':supervisor'])->group(function () {
    Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/centers/{center}', [SupervisorDashboardController::class, 'showCenter'])->name('centers.show');
    Route::resource('agents', \App\Http\Controllers\Supervisor\AgentController::class)->only(['index', 'store', 'update', 'destroy']);

    // Routes pour les documents (actes)
    Route::get('/documents/births', [\App\Http\Controllers\Supervisor\DocumentController::class, 'births'])->name('documents.births');
    Route::get('/documents/marriages', [\App\Http\Controllers\Supervisor\DocumentController::class, 'marriages'])->name('documents.marriages');
    Route::get('/documents/deaths', [\App\Http\Controllers\Supervisor\DocumentController::class, 'deaths'])->name('documents.deaths');
    Route::get('/documents/divorces', [\App\Http\Controllers\Supervisor\DocumentController::class, 'divorces'])->name('documents.divorces');
    Route::post('/documents/{act}/validate', [\App\Http\Controllers\Supervisor\DocumentController::class, 'validateAct'])->name('documents.validate');
    Route::post('/documents/{act}/reject', [\App\Http\Controllers\Supervisor\DocumentController::class, 'rejectAct'])->name('documents.reject');
    Route::get('/documents/{act}/pdf', [\App\Http\Controllers\Supervisor\DocumentController::class, 'generatePdf'])->name('documents.pdf');

    Route::get('/settings', [\App\Http\Controllers\Supervisor\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\Supervisor\SettingsController::class, 'update'])->name('settings.update');
});

// Routes agent protégées
Route::prefix('agent')->name('agent.')->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':agent'])->group(function () {
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/acts/births', [ActController::class, 'births'])->name('acts.births');
    Route::get('/acts/marriages', [ActController::class, 'marriages'])->name('acts.marriages');
    Route::get('/acts/deaths', [ActController::class, 'deaths'])->name('acts.deaths');
    Route::get('/acts/divorces', [ActController::class, 'divorces'])->name('acts.divorces');
    Route::post('/acts', [ActController::class, 'store'])->name('acts.store');
    Route::get('/acts/{act}/edit', [ActController::class, 'edit'])->name('acts.edit');
    Route::post('/acts/{act}', [ActController::class, 'update'])->name('acts.update');
    Route::delete('/acts/{act}', [ActController::class, 'destroy'])->name('acts.destroy');
    Route::get('/acts/{act}/pdf', [ActController::class, 'generatePdf'])->name('acts.pdf');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
