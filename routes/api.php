<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('login' , [UserController::class, 'login']);
Route::post('logout' , [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::post('addDoctor' , [AdminController::class , 'createDoctor'])->middleware('auth:sanctum',  'checkAdmin');
Route::post('addSecretary' , [AdminController::class , 'createSecretary'])->middleware('auth:sanctum',  'checkAdmin');
Route::post('addPatient' , [AdminController::class , 'createPatient'])->middleware('auth:sanctum',  'checkAdmin');
Route::get('stats' , [AdminController::class , 'viewGlobalStats'])->middleware('auth:sanctum',  'checkAdmin');


Route::post('register' , [PatientController::class , 'register']);

Route::post('/rendezvous', [PatientController::class, 'requestAppointment'])->middleware('auth:sanctum');
Route::patch('/rendezvous/{id}/confirm', [RendezVousController::class, 'confirmer'])->middleware('auth:sanctum' , 'CheckSecretaire');
Route::patch('/rendezvous/{id}/cancel', [RendezVousController::class, 'annuler'])->middleware('auth:sanctum' , 'CheckSecretaire');

