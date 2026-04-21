<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    protected $table = 'ordonnances';
    protected $fillable = [
        'contenu_medicaments' , 'chemin_pdf',
        'consultation_id'
    ];
    
    // Removing array cast to use raw textarea text
    protected $casts = [];

    //relations
    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'consultation_id');
    }
    //methods
    public function generatePDF()
    {

    }
    public function download()
    {

    }
}
