<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'facture_id','montant','mode_paiement','date'
    ]; 
    
    public function facture(){
        return $this->belongsTo(Facture::class);
    }
    public function ecriture_comptable(){
        return $this->belongTo(EcritureComptable::class);
    }

}
