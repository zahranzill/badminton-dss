<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PairController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\RallyController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PerformanceStatController;
use App\Http\Controllers\EvaluationRuleController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationHistoryController;
use App\Http\Controllers\ReportController;

// Halaman awal redirect ke dashboard (yang nanti akan lewat auth)
Route::redirect('/', '/dashboard');

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Protected Routes (Auth)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Player CRUD
    Route::resource('players', PlayerController::class);

    // Pair CRUD
    Route::resource('pairs', PairController::class);

    // Match CRUD
    Route::resource('matches', MatchController::class);

    // Rally Management nested under Matches
    Route::get('/matches/{match}/rallies', [RallyController::class, 'index'])->name('rallies.index');
    Route::get('/matches/{match}/rallies/create', [RallyController::class, 'create'])->name('rallies.create');
    Route::post('/matches/{match}/rallies', [RallyController::class, 'store'])->name('rallies.store');
    Route::get('/matches/{match}/rallies/{rally}/edit', [RallyController::class, 'edit'])->name('rallies.edit');
    Route::put('/matches/{match}/rallies/{rally}', [RallyController::class, 'update'])->name('rallies.update');
    Route::delete('/matches/{match}/rallies/{rally}', [RallyController::class, 'destroy'])->name('rallies.destroy');

    // Verification & Finalization
    Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
    Route::get('/verification/{match}', [VerificationController::class, 'show'])->name('verification.show');
    Route::post('/verification/{match}/finalize', [VerificationController::class, 'finalize'])->name('verification.finalize');

    // Performance Statistics
    Route::get('/statistics', [PerformanceStatController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/{match}', [PerformanceStatController::class, 'show'])->name('statistics.show');

    // Evaluation Rules CRUD
    Route::resource('evaluation-rules', EvaluationRuleController::class)->except(['show']);

    // Modul DSS & Evaluation
    Route::get('/evaluations/{match}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::post('/evaluations/{match}/run', [EvaluationController::class, 'run'])->name('evaluations.run');
    Route::put('/evaluations/{match}/notes', [EvaluationController::class, 'updateNotes'])->name('evaluations.notes');

    // Evaluation History
    Route::get('/evaluation-history', [EvaluationHistoryController::class, 'index'])->name('evaluation-history.index');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{match}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{match}/print', [ReportController::class, 'print'])->name('reports.print');
});
