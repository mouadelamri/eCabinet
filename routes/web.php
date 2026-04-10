<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
    Route::get('/secretaries', [AdminController::class, 'secretaries'])->name('secretaries');
    Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    Route::post('/doctors', [AdminController::class, 'createDoctor'])->name('doctors.store');
    Route::post('/secretaries', [AdminController::class, 'createSecretary'])->name('secretaries.store');
    Route::post('/patients', [PatientController::class, 'register'])->name('patients.store');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
});

// Patient Portal Routes
Route::middleware(['auth'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/rendezvous', [PatientController::class, 'appointments'])->name('appointments');
    Route::get('/rendezvous/nouveau', [PatientController::class, 'bookAppointment'])->name('appointments.create');
    Route::post('/rendezvous', [PatientController::class, 'requestAppointment'])->name('appointments.store');
    Route::get('/dossier', [PatientController::class, 'medicalRecord'])->name('dossier');
    Route::get('/parametres', [PatientController::class, 'settings'])->name('settings');
    Route::patch('/parametres', [PatientController::class, 'updateSettings'])->name('settings.update');
});

require __DIR__.'/auth.php';
