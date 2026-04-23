<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;



// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Patients
Route::get('/patients', [PatientController::class, 'index'])->name('patients');
Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');
Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');

// Visits
Route::get('/visits', [VisitController::class, 'index'])->name('visits');
Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
Route::get('/visits/{id}', [VisitController::class, 'show'])->name('visits.show');
Route::get('/visits/filter', [VisitController::class, 'filter'])->name('visits.filter');
