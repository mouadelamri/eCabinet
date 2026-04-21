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

