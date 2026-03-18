<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('addDoctor' , [AdminController::class , 'createDoctor']);
Route::post('addSecretary' , [AdminController::class , 'createSecretary']);
Route::post('addPatient' , [AdminController::class , 'createPatient']);

Route::post('login' , [UserController::class, 'login']);
Route::post('logout' , [UserController::class, 'logout'])->middleware('auth:sanctum');

