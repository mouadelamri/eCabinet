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
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentRequested;
use App\Models\Notification;

class PatientController extends Controller
{
    /**
     * Patient Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $upcomingAppointments = RendezVous::where('patient_id', $user->id)
             ->where('date_heure', '>=', now())
            ->whereIn('statut', ['PENDING', 'CONFIRMED'])
            ->orderBy('date_heure', 'asc')
            ->take(3)
            ->get();

        $recentAppointments = RendezVous::where('patient_id', $user->id)
            ->orderBy('date_heure', 'desc')
            ->take(5)
            ->get();

        $recentNotifications = Notification::where('user_id', $user->id)
            ->latest()
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
            'confirmedAppointments',
            'recentNotifications'
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
     * Return JSON of working days for a given doctor (for front-end date blocking)
     */
    public function doctorAvailability($id)
    {
        $doctor = User::where('id', $id)->where('role', 'DOCTOR')->firstOrFail();
        $availabilities = \App\Models\DoctorAvailability::where('user_id', $id)->get();

        $workingDays = $availabilities->where('is_working', true)->pluck('day_of_week')->values();
        $schedule = $availabilities->mapWithKeys(fn($a) => [
            $a->day_of_week => [
                'is_working' => (bool) $a->is_working,
                'start'      => $a->start_time ? substr($a->start_time, 0, 5) : '09:00',
                'end'        => $a->end_time   ? substr($a->end_time, 0, 5)   : '17:00',
            ]
        ]);

        return response()->json([
            'working_days' => $workingDays,
            'schedule'     => $schedule,
        ]);
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

        // Server-side: check doctor availability on the chosen day
        $date = \Carbon\Carbon::parse($RendezVousData['date_heure']);
        $dayOfWeek = $date->dayOfWeek; // 0=Sun, 6=Sat
        $avail = \App\Models\DoctorAvailability::where('user_id', $RendezVousData['medecin_id'])
            ->where('day_of_week', $dayOfWeek)
            ->where('is_working', true)
            ->first();

        if (!$avail) {
            return back()->withInput()->withErrors([
                'date_heure' => 'Ce médecin n\'est pas disponible le ' . $date->translatedFormat('l') . '. Veuillez choisir un autre jour.',
            ]);
        }

        $RendezVousData['statut'] = 'PENDING';
        $RendezVousData['patient_id'] = $user_id;
        $appointment = RendezVous::create($RendezVousData);

        // Send Notification Email
        try {
            Mail::to(Auth::user()->email)->queue(new AppointmentRequested($appointment));
        } catch (\Exception $e) {
            // Silently fail or log for now to not block the user
            \Log::error("Failed to send appointment request email: " . $e->getMessage());
        }

        return redirect()->route('patient.appointments')->with('success', 'Votre demande de rendez-vous a été envoyée avec succès !');
    }

    /**
     * Cancel an existing appointment
     */
    public function cancelAppointment($id)
    {
        $rdv = RendezVous::where('patient_id', Auth::id())->findOrFail($id);
        if (in_array($rdv->statut, ['PENDING', 'CONFIRMED'])) {
            $rdv->update(['statut' => 'CANCELLED']);
            return back()->with('success', 'Rendez-vous annulé avec succès.');
        }
        return back()->with('error', 'Impossible d\'annuler ce rendez-vous.');
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
     * Export prescription as PDF (for patient)
     */
    public function downloadOrdonnance($id)
    {
        // Find ordonnance but ensure it belongs to the authenticated patient
        $ordonnance = \App\Models\Ordonnance::whereHas('consultation.rendezVous', function($query) {
            $query->where('patient_id', Auth::id());
        })->with('consultation.rendezVous.medecin')->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('doctor.pdf.ordonnance', compact('ordonnance'));

        $date = $ordonnance->created_at->format('d-m-Y');
        return $pdf->stream("Ma_Prescription_{$date}.pdf");
    }

    /**
     * Update patient profile settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telephone'     => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'groupe_sanguin'=> ['nullable', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'date_naissance'=> ['nullable', 'date'],
            'numero_securite_sociale' => ['nullable', 'string', 'max:50'],
            'antecedents_medicaux' => ['nullable', 'string'],
            'allergies'     => ['nullable', 'string'],
        ]);

        $userData = [
            'name'           => $request->name,
            'email'          => $request->email,
            'telephone'      => $request->telephone,
            'groupe_sanguin' => $request->groupe_sanguin,
            'date_naissance' => $request->date_naissance,
            'numero_securite_sociale' => $request->numero_securite_sociale,
            'antecedents_medicaux' => $request->antecedents_medicaux,
            'allergies'      => $request->allergies,
        ];

        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo_path'] = $path;
        }

        $user->update($userData);

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
        $user_role = Auth::user()->role ;
        if($user_role == "ADMIN"){
        return back()->with('success' , 'patient ajouter avec success !');
        }else if($user_role == "SECRETARY"){
        return back()->with('success' , 'patient ajouter avec success');
        }else{
            return view('login');
        }
    }

 //

}
