<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\SecretaireController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('login' , [UserController::class, 'login']);
Route::post('logout' , [UserController::class, 'logout'])->middleware('auth:sanctum');


//les fonnctionalite de Admin
Route::post('addDoctor' , [AdminController::class , 'createDoctor'])->middleware('auth:sanctum',  'checkAdmin');
Route::post('addSecretary' , [AdminController::class , 'createSecretary'])->middleware('auth:sanctum',  'checkAdmin');
Route::post('addPatient' , [PatientController::class , 'register'])->middleware('auth:sanctum' , 'checkAdmin');
Route::get('stats' , [AdminController::class , 'viewGlobalStats'])->middleware('auth:sanctum',  'checkAdmin');

//les fonctionalite du patient
Route::post('register' , [PatientController::class , 'register']);

Route::post('/rendezvous', [PatientController::class, 'requestAppointment'])->middleware('auth:sanctum');
Route::patch('/rendezvous/{id}/confirm', [RendezVousController::class, 'confirmer'])->middleware('auth:sanctum' , 'CheckSecretaire');
Route::patch('/rendezvous/{id}/cancel', [RendezVousController::class, 'annuler'])->middleware('auth:sanctum' , 'CheckSecretaire');


//les fonctionnalite du secrtetary
Route::post('Secretary/addPatient' , [PatientController::class , 'register'])->middleware('auth:sanctum' , 'CheckSecretaire');
Route::delete('Secretary/destroyPatient/{id}' , [SecretaireController::class , 'destroy'])->middleware('auth:sanctum' , 'CheckSecretaire');

