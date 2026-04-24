<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientRequest;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecretaireController extends Controller
{

    public function manageAppointments()
    {

    }
    public function dashboard(){
    return view('secretary.dashboard', [
        'patientsCount' => User::where('role','PATIENT')->count(),
        'rendezVousCount' => RendezVous::count(),
        'pendingCount' => RendezVous::where('statut','PENDING')->count(),
        'confirmedCount' => RendezVous::where('statut','CONFIRMED')->count(),
        'todayRdv' => RendezVous::whereDate('date_heure', today())->count(),
        'tomorrowRdv' => RendezVous::whereDate('date_heure', now()->addDay())->count(),
        'cancelledCount' => RendezVous::where('statut','CANCELLED')->count(),
        'todayAppointments' => RendezVous::with(['patient','medecin'])
            ->whereDate('date_heure', today())
            ->get(),
    ]);
    }
    public function settigns(){
        return view('secretary.settings');
    }
    public function settings()
    {
        $user = Auth::user();
        return view('secretary.settings', compact('user'));
    }
    public function patients()
    {
        $patients = User::where('role', 'PATIENT')->latest()->paginate(10);
        return view('secretary.patients', compact('patients'));
    }
    public function PendingrendezVous(){
        $rendezVous = RendezVous::where('statut', 'PENDING')->latest()->paginate(10);
        return view('secretary.PendingrendezVous' , compact('rendezVous'));
    }
    public function ConfirmedrendezVous(){
        $rendezVous = RendezVous::where('statut', 'CONFIRMED')->latest()->paginate(10);
        return view('secretary.ConfirmedrendezVous' , compact('rendezVous'));
    }
    public function CancelledrendezVous(){
        $rendezVous = RendezVous::where('statut' , 'CANCELLED')->latest()->paginate(10);
        return view('secretary.CancelledrendezVous' , compact('rendezVous'));
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

        /**
     * Update patient profile settings
     */
    public function updateSettings(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        $userData = [
            'name'           => $request->name,
            'email'          => $request->email,
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

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

}
