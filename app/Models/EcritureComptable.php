<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcritureComptable extends Model
{
    protected $fillable = [
        'type','montant','compte','reference','date'
    ]; 
    
    public function factures(){
        return $this->hasMany(Facture::class);
    }

    public function paiements(){
        return $this->hasMany(Paiement::class);
    }
}
