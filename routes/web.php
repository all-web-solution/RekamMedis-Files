<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;



// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Patient routes
Route::post('/patients/index', [PatientController::class, 'index'])->name('patients.index');
Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');
Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
// Visit routes

Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
Route::get('/visits/{id}', [VisitController::class, 'show'])->name('visits.show');
Route::put('/visits/{id}', [VisitController::class, 'update'])->name('visits.update');
Route::delete('/visits/{id}', [VisitController::class, 'destroy'])->name('visits.destroy');

Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show'); // Tambah route detail

Route::get('/api/visits/filter', [VisitController::class, 'filter'])->name('api.visits.filter');
