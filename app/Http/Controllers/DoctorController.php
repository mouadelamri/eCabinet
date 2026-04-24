<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Ordonnance;
use App\Models\UserActivity;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification;
use App\Mail\AppointmentConfirmed;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DoctorAvailability;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user();
        $today = Carbon::today();

        // 1. Today's Appointments
        $todayAppointments = RendezVous::where('medecin_id', $doctor->id)
            ->whereDate('date_heure', $today)
            ->with('patient')
            ->orderBy('date_heure', 'asc')
            ->get();

       // 2. Stats
        $monthlyPatientsCount = RendezVous::where('medecin_id', $doctor->id)
            ->whereMonth('date_heure', Carbon::now()->month)
            ->distinct('patient_id')
            ->count('patient_id');
        // Assuming max capacity is 20 for rate calculation
        $maxCapacity = 20;
        $occupancyRate = min(100, round((count($todayAppointments) / $maxCapacity) * 100));

        // 3. Recent Activity
        $recentActivities = UserActivity::where('user_id', $doctor->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('doctor.dashboard', compact(
            'todayAppointments',
            'monthlyPatientsCount',
            'occupancyRate',
            'recentActivities'
        ));
    }


    public function schedule(Request $request)
    {
        $weekOffset = (int) $request->get('week', 0);
        $weekStart  = Carbon::now()->startOfWeek()->addWeeks($weekOffset);
        $weekEnd    = $weekStart->copy()->endOfWeek();

        $availabilities = DoctorAvailability::where('user_id', Auth::id())
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        for ($i = 0; $i <= 6; $i++) {
            if (!isset($availabilities[$i])) {
                $availabilities[$i] = new DoctorAvailability([
                    'day_of_week' => $i,
                    'is_working'  => false,
                    'start_time'  => '09:00',
                    'end_time'    => '17:00'
                ]);
            }
        }

        return view('doctor.schedule', compact('availabilities', 'weekStart', 'weekEnd', 'weekOffset'));
    }

    public function patients()
    {
        $doctorId = Auth::id();
        $query = Patient::whereHas('RendezVous', function($query) use ($doctorId) {
            $query->where('medecin_id', $doctorId);
        });

        $totalPatients = $query->count();
        $newPatientsThisMonth = (clone $query)->where('users.created_at', '>=', now()->startOfMonth())->count();
        $patientsToday = (clone $query)->whereHas('RendezVous', function($q) use ($doctorId) {
            $q->where('medecin_id', $doctorId)->whereDate('date_heure', today());
        })->count();

        $patients = $query->latest()->paginate(10);

        return view('doctor.patients', compact('patients', 'totalPatients', 'newPatientsThisMonth', 'patientsToday'));
    }

    public function patientRecord($id)
    {
        $doctorId = Auth::id();
        $patient = Patient::whereHas('RendezVous', function($query) use ($doctorId) {
            $query->where('medecin_id', $doctorId);
        })->findOrFail($id);

        $consultations = Consultation::whereHas('rendezVous', function($query) use ($id) {
            $query->where('patient_id', $id);
        })->latest()->get();

        return view('doctor.console', compact('patient', 'consultations'));
    }

    public function patientAnalyses($id)
    {
        $doctorId = Auth::id();
        $patient = Patient::whereHas('RendezVous', function($query) use ($doctorId) {
            $query->where('medecin_id', $doctorId);
        })->findOrFail($id);
        return view('doctor.analyses', compact('patient'));
    }

    public function patientReports($id)
    {
        $doctorId = Auth::id();
        $patient = Patient::whereHas('RendezVous', function($query) use ($doctorId) {
            $query->where('medecin_id', $doctorId);
        })->findOrFail($id);
        return view('doctor.reports', compact('patient'));
    }



    public function profile()
    {
        $user = Auth::user();
        return view('doctor.profile', compact('user'));
    }

    public function updateProfile(Request $request)
{
    $user = User::findOrFail(Auth::id());

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'specialiste' => 'nullable|string|max:255',
        'diplome' => 'nullable|string|max:255',
        'telephone_pro' => 'nullable|string|max:20',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'signature_base64' => 'nullable|string',
    ]);

    $data = $request->only([
        'name',
        'email',
        'specialiste',
        'diplome',
        'telephone_pro'
    ]);

    // PHOTO
    if ($request->hasFile('photo')) {

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $data['profile_photo_path'] =
            $request->file('photo')->store('profile-photos', 'public');
    }

    // SIGNATURE
    if ($request->filled('signature_base64')) {
        if ($user->signature_path) {
            Storage::disk('public')->delete($user->signature_path);
        }

        $image_parts = explode(";base64,", $request->signature_base64);
        $image_base64 = base64_decode($image_parts[1]);
        $filename = 'signatures/' . uniqid() . '.png';
        Storage::disk('public')->put($filename, $image_base64);

        $data['signature_path'] = $filename;
    } elseif ($request->hasFile('signature')) {
        if ($user->signature_path) {
            Storage::disk('public')->delete($user->signature_path);
        }

        $data['signature_path'] =
            $request->file('signature')->store('signatures', 'public');
    }
    if (!$user) abort(401);
    $user->update($data);

    return back()->with('success', 'Profil mis à jour avec succès.');
}

    public function settings()
    {
        $user = Auth::user();
        return view('doctor.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $request->validate([
            'appearance_mode' => 'required|in:light,dark',
        ]);

        $user->update([
            'appearance_mode' => $request->appearance_mode
        ]);

        return response()->json(['success' => true]);
    }

   public function exportPatient($id)
    {
        $doctorId = Auth::id();
        $patient = Patient::whereHas('RendezVous', function($query) use ($doctorId) {
            $query->where('medecin_id', $doctorId);
        })->findOrFail($id);

        $consultations = Consultation::whereHas('rendezVous', function($query) use ($id) {
            $query->where('patient_id', $id);
        })->latest()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=dossier_patient_{$patient->id}.csv",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($patient, $consultations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nom', 'Email', 'Téléphone', 'Date de Naissance']);
            fputcsv($file, [$patient->id, $patient->name, $patient->email, $patient->telephone, $patient->date_naissance]);

            fputcsv($file, []);
            fputcsv($file, ['Historique des Consultations']);
            fputcsv($file, ['Date', 'Motif', 'Observations', 'Diagnostic']);

            foreach ($consultations as $consultation) {

                $motif = $consultation->rendezVous ? $consultation->rendezVous->motif : 'N/A';

                fputcsv($file, [
                    $consultation->created_at->format('d/m/Y H:i'),
                    $motif,
                    $consultation->notes_privees ?? 'N/A',
                    $consultation->compte_rendu ?? 'N/A'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function completeConsultation(Request $request, $rendezvous_id)
    {
        $rdv = RendezVous::where('medecin_id', Auth::id())->findOrFail($rendezvous_id);
        $rdv->update(['statut' => 'COMPLETED']);

        // Log activity
        UserActivity::create([
            'user_id' => Auth::id(),
            'type' => 'CONSULTATION_COMPLETED',
            'description' => "Consultation terminée pour le patient " . ($rdv->patient->name ?? 'inconnu'),
        ]);

        return back()->with('success', 'La consultation a été marquée comme terminée.');
    }
    public function exportOrdonnance($id)
    {
        $ordonnance = \App\Models\Ordonnance::with('consultation.rendezVous.patient')->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('doctor.pdf.ordonnance', compact('ordonnance'));
        $nomPatient = $ordonnance->consultation->rendezVous->patient->name ?? 'Patient';

        return $pdf->stream('Ordonnance_'.$nomPatient.'.pdf');
    }
    public function createConsultation($id)
    {
        $doctorId = Auth::id();
        $patient = Patient::whereHas('RendezVous', function($query) use ($doctorId) {
            $query->where('medecin_id', $doctorId);
        })->findOrFail($id);
        return view('doctor.consultation_create', compact('patient'));
    }

    public function saveAvailability(Request $request)
    {
        $request->validate([
            'availabilities' => 'required|array',
            'availabilities.*.day_of_week' => 'required|integer|min:0|max:6',
            'availabilities.*.start_time' => 'nullable|date_format:H:i',
            'availabilities.*.end_time' => 'nullable|date_format:H:i',
        ]);

        foreach ($request->availabilities as $dayData) {
            DoctorAvailability::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'day_of_week' => $dayData['day_of_week'],
                ],
                [
                    'is_working' => isset($dayData['is_working']),
                    'start_time' => $dayData['start_time'] ?? '09:00',
                    'end_time'   => $dayData['end_time'] ?? '17:00',
                ]
            );
        }

        return back()->with('success', 'Disponibilités mises à jour avec succès.');
    }



    public function storeConsultation(Request $request, $id)
    {
        $doctor = Auth::user();
        // Ensure patient is linked to this doctor
        $patient = Patient::whereHas('RendezVous', function($query) use ($doctor) {
            $query->where('medecin_id', $doctor->id);
        })->findOrFail($id);

        $rendezVous = RendezVous::firstOrCreate(
            ['patient_id' => $id, 'medecin_id' => $doctor->id, 'date_heure' => \Carbon\Carbon::today()],
            [
                'statut' => 'COMPLETED',
                'motif'  => 'Consultation directe'
            ]
        );

       $consultation = Consultation::create([
            'rendez_vous_id'   => $rendezVous->id,
            'poids'            => filter_var($request->poids, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'temperature'      => filter_var($request->temperature, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'tension'          => $request->tension,
            'rythme_cardiaque' => filter_var($request->rythme_cardiaque, FILTER_SANITIZE_NUMBER_INT),
            'compte_rendu'     => $request->compte_rendu,
            'notes_privees'    => $request->notes_privees,
        ]);

        Ordonnance::create([
            'consultation_id' => $consultation->id,
            'contenu_medicaments' => $request->filled('medicaments') ? $request->medicaments : 'Aucun médicament prescrit.',
        ]);

        return redirect()->route('doctor.patients.show', $id)->with('success', 'Consultation et Ordonnance enregistrées avec succès !');
    }
}
