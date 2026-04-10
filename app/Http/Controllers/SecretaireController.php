<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecretaireController extends Controller
{

    public function manageAppointments()
    {

    }
    //managePatients
    public function destroy($id)
    {
    $patient = User::findOrFail($id);

    $patient->delete();

    if (request()->expectsJson()) {
        return response()->json([
            'message' => 'Patient deleted successfully'
        ], 200);
    }

    return back()->with('success', 'Patient deleted successfully.');
    }
}
