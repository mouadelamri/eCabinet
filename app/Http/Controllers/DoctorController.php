<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Ordonnance;
use App\Models\UserActivity;
use App\Models\Patient;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
        $monthlyPatientsCount = Consultation::where('doctor_id', $doctor->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->distinct('patient_id')
            ->count();

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

    public function schedule()
    {
        return view('doctor.schedule');
    }

    public function patients()
    {
        $patients = Patient::latest()->paginate(10);
        return view('doctor.patients', compact('patients'));
    }

    public function patientRecord($id)
    {
        $patient = Patient::findOrFail($id);
        $consultations = Consultation::where('patient_id', $id)->latest()->get();
        return view('doctor.console', compact('patient', 'consultations'));
    }

    public function patientAnalyses($id)
    {
        $patient = Patient::findOrFail($id);
        return view('doctor.analyses', compact('patient'));
    }

    public function patientReports($id)
    {
        $patient = Patient::findOrFail($id);
        return view('doctor.reports', compact('patient'));
    }

    public function inventory()
    {
        return view('doctor.inventory');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('doctor.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'specialiste' => 'nullable|string|max:255',
            'diplome' => 'nullable|string|max:255',
            'telephone_pro' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'specialiste', 'diplome', 'telephone_pro']);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

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
        $user = Auth::user();
        
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
        $patient = Patient::findOrFail($id);
        $consultations = Consultation::where('patient_id', $id)->latest()->get();

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
                fputcsv($file, [
                    $consultation->created_at->format('d/m/Y H:i'),
                    $consultation->motif,
                    $consultation->observations,
                    $consultation->diagnostic
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function completeConsultation(Request $request, $rendezvous_id)
    {
        // To be implemented
        return back()->with('success', 'Consultation complete.');
    }
}
