<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\RendezVousRequest;
use App\Models\User;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    /**
     * Patient Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $upcomingAppointments = RendezVous::where('patient_id', $user->id)
            ->whereIn('statut', ['PENDING', 'CONFIRMED'])
            ->orderBy('date_heure', 'asc')
            ->take(3)
            ->get();

        $recentAppointments = RendezVous::where('patient_id', $user->id)
            ->orderBy('date_heure', 'desc')
            ->take(5)
            ->get();

        $totalAppointments = RendezVous::where('patient_id', $user->id)->count();
        $pendingAppointments = RendezVous::where('patient_id', $user->id)->where('statut', 'PENDING')->count();
        $confirmedAppointments = RendezVous::where('patient_id', $user->id)->where('statut', 'CONFIRMED')->count();

        return view('patient.dashboard', compact(
            'user',
            'upcomingAppointments',
            'recentAppointments',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments'
        ));
    }

    /**
     * List all appointments for the authenticated patient
     */
    public function appointments(Request $request)
    {
        $user = Auth::user();
        $query = RendezVous::where('patient_id', $user->id);

        if ($request->filter == 'upcoming') {
            $query->whereIn('statut', ['PENDING', 'CONFIRMED'])->where('date_heure', '>=', now());
        } elseif ($request->filter == 'past') {
            $query->whereIn('statut', ['COMPLETED', 'CANCELLED'])->orWhere('date_heure', '<', now());
        }

        $appointments = $query->orderBy('date_heure', 'desc')->paginate(10);

        return view('patient.appointments.index', compact('user', 'appointments'));
    }

    /**
     * Show the appointment booking form
     */
    public function bookAppointment()
    {
        $user = Auth::user();
        $doctors = User::where('role', 'DOCTOR')->get();
        return view('patient.appointments.create', compact('user', 'doctors'));
    }

    /**
     * Store a new appointment request
     */
    public function requestAppointment(RendezVousRequest $request)
    {
        $user_id = Auth::user()->id;
        $RendezVousData = $request->validated();
        $RendezVousData['statut'] = 'PENDING';
        $RendezVousData['patient_id'] = $user_id;
        $rendezVous = RendezVous::create($RendezVousData);

        return redirect()->route('patient.appointments')->with('success', 'Votre demande de rendez-vous a été envoyée avec succès !');
    }

    public function medicalRecord()
    {
        $user = Auth::user();
        
        $appointmentsIds = RendezVous::where('patient_id', $user->id)
            ->where('statut', 'COMPLETED')
            ->pluck('id');
            
        $consultations = Consultation::whereIn('rendez_vous_id', $appointmentsIds)
            ->with(['rendezVous.medecin', 'ordonnance'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patient.dossier', compact('user', 'consultations'));
    }

    /**
     * Show the patient settings page
     */
    public function settings()
    {
        $user = Auth::user();
        return view('patient.settings', compact('user'));
    }

    /**
     * Update patient profile settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telephone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'telephone' => $request->telephone,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', 'min:8'],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('patient.settings')->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Register a new patient (API)
     */
    public function register(CreatePatientRequest $request)
    {
        $patientData = $request->validated();
        $patientData['password'] = Hash::make($patientData['password']);
        $patientData['role'] = 'PATIENT';

        $patient = User::create($patientData);

        return response()->json([
            'message' => 'Patient registered successfully',
            'user'    => $patient,
        ], 201);
    }

    public function viewMyProgress()
    {
        //
    }
}
