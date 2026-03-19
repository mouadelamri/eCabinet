<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RendezVousController extends Controller
{
    public function createRendezVous(Request $request)
    {
        $request->validate([
                'date_heure'=>'|required|datetime',
                'statut' =>['required','string', Rule::in(['PENDING'])],
                'motif'=>'required|string' ,
                'patient_id'=>'required|exist:users,patient_id' ,
                'medecin_id' =>'required|exist:users,medecin_id'
        ]);
        $RendezVous = RendezVous::create([
                'date_heure'=>$request->date_heure,
                'statut' =>$request->statut,
                'motif'=>$request->motif,
                'patient_id'=>$request->patient_id,
                'medecin_id' =>$request->medecin_id
        ]);
        return response()->json([
            'message'=>'rendez vous created ',
            'rendez vous'=>$RendezVous
        ], 200);
    }
    public function confirmer()
    {

    }
    public function annuler()
    {

    }
}
