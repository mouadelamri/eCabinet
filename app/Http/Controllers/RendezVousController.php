<?php

namespace App\Http\Controllers;

use App\Http\Requests\RendezVousRequest;
use App\Mail\AppointmentConfirmed;
use App\Models\Notification;
use App\Models\RendezVous;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RendezVousController extends Controller
{

    public function confirmAppointment($id)
    {
        $rdv = RendezVous::where('id', $id)->firstOrFail();

<<<<<<< HEAD
        if ($rdv->statut === 'PENDING' ) {
            $rdv->update(['statut' => 'CONFIRMED']);

            // Log activity
            UserActivity::create([
                'user_id' => Auth::id(),
                'type' => 'APPOINTMENT_CONFIRMED',
                'description' => "Rendez-vous confirmé pour le patient " . ($rdv->patient->name ?? 'inconnu'),
            ]);

            // Create In-App Notification for Patient
            try {
                Notification::create([
                    'user_id' => $rdv->patient_id,
                    'type' => 'CONFIRMATION',
                    'message' => "Votre rendez-vous avec le Dr. " . $rdv->medecin_id->name . " pour le " . \Carbon\Carbon::parse($rdv->date_heure)->translatedFormat('d F') . " a été confirmé.",
                    'est_lu' => false,
                    'sent_at' => now(),
                ]);

                // Send Email Notification
                if ($rdv->patient && $rdv->patient->email) {
                    Mail::to($rdv->patient->email)->send(new AppointmentConfirmed($rdv));
                }
            } catch (\Exception $e) {
                \Log::error("Failed to notify patient of confirmation: " . $e->getMessage());
            }

            return back()->with('success', 'Rendez-vous confirmé avec succès.');
        }

        return back()->with('error', 'Impossible de confirmer ce rendez-vous.');
=======
        $rendezVous->update([
            'statut' => 'CONFIRMED'
        ]);
        try {
            Mail::to($rendezVous->patient->email)->send(new AppointmentConfirmed($rendezVous));
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            \Log::error('Failed to send appointment confirmation email: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Rendez-vous confirmé et email envoyé !');
>>>>>>> dc174713e3e71541fff0f47b8c95220754f9ef05
    }

    public function annuler($id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        if($rendezVous->statut == 'CONFIRMED' || $rendezVous->statut !== 'CANCELLED' ){
            return back()->with('error' , 'Impossible d\'annuler ce rendez-vous.');
        }else{
        $rendezVous->update([
            'statut' => 'CANCELLED'
        ]);
        UserActivity::create([
                'user_id' => Auth::id(),
                'type' => 'APPOINTMENT_ANNULEE',
                'description' => "Rendez-vous annuller pour le patient " . ($rendezVous->patient_id->name ?? 'inconnu'),
            ]);

            // Create In-App Notification for Patient
            try {
                Notification::create([
                    'user_id' => $rendezVous->patient_id,
                    'type' => 'ANNULATION',
                    'message' => "Votre rendez-vous avec le Dr. " . $rendezVous->medecin_id->name . " pour le " . \Carbon\Carbon::parse($rendezVous->date_heure)->translatedFormat('d F') . " a été annuller.",
                    'est_lu' => false,
                    'sent_at' => now(),
                ]);
                } catch (\Exception $e) {
                \Log::error("Failed to notify patient of annulation: " . $e->getMessage());
            }
        return back()->with('success' , 'rendez vous annuler');
        }
        

    }
}
?>

