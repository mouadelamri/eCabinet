<?php

namespace App\Http\Controllers;

use App\Http\Requests\RendezVousRequest;
use App\Mail\AppointmentConfirmed;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RendezVousController extends Controller
{

    public function confirmer($id)
    {
        set_time_limit(120);
        $rendezVous = RendezVous::findOrFail($id);

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
    }

    public function annuler($id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        $rendezVous->update([
            'statut' => 'CANCELLED'
        ]);

        return back()->with('success' , 'rendez vous annuler');

    }
}

