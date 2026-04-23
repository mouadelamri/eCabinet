<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDoctorRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\CreateSecretaryRequest;
use App\Models\Consultation;
use App\Models\RendezVous;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\SystemSetting;
use App\Models\AlertProtocol;
use App\Models\ClinicLicense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ─── Web View Methods ────────────────────────────────────────────────

    public function index()
    {
        $doctorsCount     = User::where('role', 'DOCTOR')->count();
        $patientsCount    = User::where('role', 'PATIENT')->count();
        $secretariesCount = User::where('role', 'SECRETARY')->count();

        $todayRdv = RendezVous::whereDate('date_heure', today())->count();

        $monthlyConsultations = Consultation::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Last 7 days activity for Chart.js
        $last7Days = collect(range(6, 0))->map(function ($daysBack) {
            $date = now()->subDays($daysBack);
            return [
                'label' => $date->format('D'),
                'count' => RendezVous::whereDate('created_at', $date->toDateString())->count(),
                'registrations' => User::whereDate('created_at', $date->toDateString())->count(),
                'logins' => UserActivity::where('type', 'login')->whereDate('created_at', $date->toDateString())->count(),
            ];
        });

        // Filter out ADMIN users from the latest users list
        $latestUsers = User::where('role', '!=', 'ADMIN')->latest()->take(10)->get();

        return view('admin.overview', compact(
            'doctorsCount', 'patientsCount', 'secretariesCount',
            'todayRdv', 'monthlyConsultations', 'last7Days', 'latestUsers'
        ));
    }

    public function doctors()
    {
        $doctors = User::where('role', 'DOCTOR')->latest()->paginate(10);
        return view('admin.doctors', compact('doctors'));
    }

    public function secretaries()
    {
        $secretaries = User::where('role', 'SECRETARY')->latest()->paginate(10);
        return view('admin.secretaries', compact('secretaries'));
    }

    public function patients()
    {
        $patients = User::where('role', 'PATIENT')->latest()->paginate(10);
        return view('admin.patients', compact('patients'));
    }

    public function settings()
    {
        $auditLogs = UserActivity::with('user')->latest()->take(10)->get();
        // Load Settings
        $lockdownStatus = SystemSetting::where('key', 'privacy_lockdown')->value('value') === 'true';
        $cloudStorage = SystemSetting::where('key', 'storage_cloud_usage')->value('value') ?? 0;
        $serverUptime = SystemSetting::where('key', 'server_uptime')->value('value') ?? 0;
        $dataEfficiency = SystemSetting::where('key', 'data_efficiency')->value('value') ?? 0;

        $alertProtocols = AlertProtocol::all();
        $licenses = ClinicLicense::all();

        return view('admin.settings', compact('auditLogs', 'lockdownStatus', 'cloudStorage', 'serverUptime', 'dataEfficiency', 'alertProtocols', 'licenses'));
    }

    public function toggleLockdown()
    {
        $setting = SystemSetting::firstOrCreate(['key' => 'privacy_lockdown']);
        $newValue = $setting->value === 'true' ? 'false' : 'true';
        $setting->update(['value' => $newValue]);

        return back()->with('success', 'Privacy Lockdown status updated.');
    }

    public function toggleAlertProtocol(AlertProtocol $protocol)
    {
        $protocol->update(['is_active' => !$protocol->is_active]);
        return back()->with('success', 'Alert protocol status updated.');
    }

    // ─── Delete User ─────────────────────────────────────────────────────

    public function deleteUser(User $user)
    {
        // Prevent deleting ADMIN users or deleting oneself
        if ($user->role === 'ADMIN' || $user->id === auth()->id()) {
            return back()->with('error', 'Action non autorisée : les administrateurs ne peuvent pas être supprimés via cette interface.');
        }
        $test = RendezVous::where('patient_id', $user->id)->orWhere('medecin_id', $user->id)->exists();
        if ($test) {
            return back()->with('error', 'Impossible de supprimer cet utilisateur : il a des rendez-vous associés.');
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    // ─── API / Form POST Methods ──────────────────────────────────────────

    public function createDoctor(CreateDoctorRequest $request)
    {
        $data             = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role']     = 'DOCTOR';
        $doctor           = User::create($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Doctor created successfully', 'user' => $doctor], 201);
        }
        return back()->with('success', 'Doctor created successfully.');
    }

    public function createSecretary(CreateSecretaryRequest $request)
    {
        $data             = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role']     = 'SECRETARY';
        $secretary        = User::create($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Secretary created successfully', 'user' => $secretary], 201);
        }
        return back()->with('success', 'Secretary created successfully.');
    }

    public function viewGlobalStats()
    {
        return response()->json([
            'doctors_count'      => User::where('role', 'DOCTOR')->count(),
            'secretaries_count'  => User::where('role', 'SECRETARY')->count(),
            'patients_count'     => User::where('role', 'PATIENT')->count(),
            'appointments_count' => RendezVous::count(),
        ]);
    }
}
