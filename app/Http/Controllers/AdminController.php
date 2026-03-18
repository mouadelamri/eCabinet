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
        $patient = User::create($request->validated());
        return response()->json([
            'message'=> 'adding Patient with succesful',
            'user'=> $patient
        ] , 200);
    }

    public function viewGlobalStats()
    {
    //
    }
}
