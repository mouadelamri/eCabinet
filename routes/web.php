<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\SecretaireController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    $user = auth()->user();
    if ($user->role === 'ADMIN') return redirect()->route('admin.dashboard');
    if ($user->role === 'DOCTOR') return redirect()->route('doctor.dashboard');
    if ($user->role === 'SECRETARY') return redirect()->route('secretary.dashboard');
    return redirect()->route('patient.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'checkAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
    Route::get('/secretaries', [AdminController::class, 'secretaries'])->name('secretaries');
    Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    Route::post('/doctors', [AdminController::class, 'createDoctor'])->name('doctors.store');
    Route::post('/secretaries', [AdminController::class, 'createSecretary'])->name('secretaries.store');
    Route::post('/patients', [PatientController::class, 'register'])->name('patients.store');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');

    Route::post('/settings/lockdown', [AdminController::class, 'toggleLockdown'])->name('settings.lockdown');
    Route::post('/settings/alert-protocol/{protocol}/toggle', [AdminController::class, 'toggleAlertProtocol'])->name('settings.alert.toggle');
});

// Patient Portal Routes
Route::middleware(['auth'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::patch('/markAllAsRead' , [NotificationController::class ,'markAllAsRead' ])->name('markAllAsRead');
    Route::get('/rendezvous', [PatientController::class, 'appointments'])->name('appointments');
    Route::get('/rendezvous/nouveau', [PatientController::class, 'bookAppointment'])->name('appointments.create');
    Route::post('/rendezvous', [PatientController::class, 'requestAppointment'])->name('appointments.store');
    Route::post('/rendezvous/{id}/cancel', [RendezVousController::class, 'annuler'])->name('appointments.cancel');
    Route::delete('/rendezvous/{id}', [RendezVousController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/dossier', [PatientController::class, 'medicalRecord'])->name('dossier');
    Route::get('/parametres', [PatientController::class, 'settings'])->name('settings');
    Route::patch('/parametres', [PatientController::class, 'updateSettings'])->name('settings.update');
    Route::get('/doctors/{id}/availability', [PatientController::class, 'doctorAvailability'])->name('doctor.availability');
    Route::get('/ordonnance/{id}/download', [PatientController::class, 'downloadOrdonnance'])->name('ordonnance.download');
});
//secretary portal routes
Route::middleware(['auth'])->prefix('secretary')->name('secretary.')->group(function () {
    Route::get('/dashboard' , [SecretaireController::class , 'dashboard'])->name('dashboard');
    Route::get('/parametres' , [SecretaireController::class , 'settigns'])->name('parametres');
    Route::patch('/parametres', [SecretaireController::class, 'updateSettings'])->name('parametres');
    Route::get('/patients' , [SecretaireController::class , 'patients'])->name('patients');
    Route::get('/PendingrendezVous' , [SecretaireController::class , 'PendingrendezVous'])->name('PendingrendezVous');
    Route::get('/ConfirmedrendezVous' , [SecretaireController::class , 'ConfirmedrendezVous'])->name('ConfirmedrendezVous');
    Route::get('/rendezVousAnnulee' , [SecretaireController::class , 'CancelledrendezVous'])->name('CancelledrendezVous');
    Route::patch('/rendezvous/{rv}/confirm', [RendezVousController::class, 'confirmAppointment'])->name('confirm');
    Route::patch('/rendezvous/{id}/cancel', [RendezVousController::class, 'annuler'])->name('cancel');
    Route::delete('/rendezvous/{id}', [RendezVousController::class, 'destroy'])->name('destroy');


});


// Doctor Portal Routes
use App\Http\Controllers\DoctorController;

Route::middleware(['auth', 'CheckDoctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/schedule', [DoctorController::class, 'schedule'])->name('schedule');
    Route::post('/availability', [DoctorController::class, 'saveAvailability'])->name('availability.save');
    Route::get('/patients', [DoctorController::class, 'patients'])->name('patients.index');
    Route::get('/patients/{id}', [DoctorController::class, 'patientRecord'])->name('patients.show');
    Route::get('/patients/{id}/analyses', [DoctorController::class, 'patientAnalyses'])->name('patients.analyses');
    Route::get('/patients/{id}/reports', [DoctorController::class, 'patientReports'])->name('patients.reports');
    Route::get('/profile', [DoctorController::class, 'profile'])->name('profile');
    Route::post('/profile', [DoctorController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', [DoctorController::class, 'settings'])->name('settings');
    Route::post('/settings', [DoctorController::class, 'updateSettings'])->name('settings.update');
    Route::get('/patients/{id}/export', [DoctorController::class, 'exportPatient'])->name('patients.export');
    Route::post('/rendezvous/{id}/confirm', [RendezVousController::class, 'confirmAppointment'])->name('rendezvous.confirm');
    Route::get('/patients/{id}/consultation/create', [DoctorController::class, 'createConsultation'])->name('consultation.create');
    Route::post('/patients/{id}/consultation', [DoctorController::class, 'storeConsultation'])->name('consultation.store');
    Route::post('/consultation/{rendezvous_id}', [DoctorController::class, 'completeConsultation'])->name('consultation.complete');
    Route::get('/ordonnance/{id}/export', [DoctorController::class, 'exportOrdonnance'])->name('ordonnance.export');
});

require __DIR__.'/auth.php';
