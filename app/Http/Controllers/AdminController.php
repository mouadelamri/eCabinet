<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDoctorRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\CreateSecretaryRequest;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function createDoctor(CreateDoctorRequest $request)
    {
        $doctorData = $request->validated();
        $doctorData['password'] = Hash::make($doctorData['password']);
        $doctorData['role'] = 'DOCTOR';

        $doctor = User::create($doctorData);

        return response()->json([
            'message' => 'adding doctor with succesful',
            'user' => $doctor,
        ], 200);
    }

    public function createSecretary(CreateSecretaryRequest $request)
    {
        $secretaryData = $request->validated();
        $secretaryData['password'] = Hash::make($secretaryData['password']);
        $secretaryData['role'] = 'SECRETARY';

        $secretary = User::create($secretaryData);

        return response()->json([
            'message' => 'adding secretary with succesful',
            'user' => $secretary,
        ], 200);
    }

    public function createPatient(CreatePatientRequest $request)
    {
        $patientData = $request->validated();
        $patientData['password'] = Hash::make($patientData['password']);

        if (array_key_exists('numero_secretaire_sociale', $patientData)) {
            $patientData['numero_securite_sociale'] = $patientData['numero_secretaire_sociale'];
            unset($patientData['numero_secretaire_sociale']);
        }

        $patientData['role'] = 'PATIENT';
        $patient = User::create($patientData);

        return response()->json([
            'message' => 'adding Patient with succesful',
            'user' => $patient,
        ], 200);
    }

    public function viewGlobalStats()
    {
        return response()->json([
            'doctors_count' => User::where('role', 'DOCTOR')->count(),
            'secretaries_count' => User::where('role', 'SECRETARY')->count(),
            'patients_count' => User::where('role', 'PATIENT')->count(),
            'appointments_count' => RendezVous::count(),
        ], 200);
    }
}

