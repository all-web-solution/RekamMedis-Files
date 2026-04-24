<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');

    Route::get('/patients/{patient}/visits/export', [PatientController::class, 'exportVisits'])
        ->name('patients.visits.export');

    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');

    Route::get('/visits', [VisitController::class, 'index'])->name('visits');
    Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
    Route::get('/visits/export/pdf', [VisitController::class, 'exportPdf'])->name('visits.export.pdf');
    Route::get('/visits/filter', [VisitController::class, 'filter'])->name('visits.filter');
    Route::get('/visits/{id}', [VisitController::class, 'show'])->name('visits.show');
});
