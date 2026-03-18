<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDoctorRequest;
use App\Http\Requests\CreateSecretaryRequest;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function createDoctor(CreateDoctorRequest $request)
    {
        $doctor = User::create($request->validated());
        return response()->json([
            'message'=> 'adding doctor with succesful',
            'user'=> $doctor
        ] , 200);
    }

    public function createSecretary(CreateSecretaryRequest $request)
    {
        $secretary = User::create($request->validated());
        return response()->json([
            'message'=> 'adding secretary with succesful',
            'user'=> $secretary
        ] , 200);
    }

    public function createPatient(Request $request)
    {
<<<<<<< HEAD
        $patient = User::create($request->validated());
=======
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users,email,',
            'password'=>'required|string|min:8|confirmed',
            'role'=> ['string','required', Rule::in(['PATIENT']),],
            'date_naissance'=>'required|date',
            'adresse'=>'required|string',
            'telephone'=>'required|string|max:20',
            'numero_securite_sociale'=>'required|string',
            'groupe_sanguin'=>'required|string'
        ]);
        $patient = User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
            'date_naissance'=>$request->date_naissance,
            'adresse'=>$request->adresse,
            'telephone'=>$request->telephone,
            'numero_securite_sociale'=>$request->numero_securite_sociale,
            'groupe_sanguin'=>$request->groupe_sanguin,
        ]);
>>>>>>> aa3fafb4 (Add the UI interfaces for login/sign up/forget)
        return response()->json([
            'message'=> 'adding Patient with succesful',
            'user'=> $patient
        ] , 200);
    }

    public function viewGlobalStats()
    {
<<<<<<< HEAD
    //
=======
        return response()->json([
            'doctors_count' => User::where('role', 'DOCTOR')->count(),
            'secretaries_count' => User::where('role', 'SECRETARY')->count(),
            'patients_count' => User::where('role', 'PATIENT')->count(),
            'appointments_count' => \App\Models\RendezVous::count(),
        ], 200);
>>>>>>> aa3fafb4 (Add the UI interfaces for login/sign up/forget)
    }
}
