<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'commande_id',
        'numero',
        'tva',
        'total_ht',
        'total_ttc',
        'date',
        'statut'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function paiements(){
        return $this->hasOne(Paiement::class);
    }

    public function ecriture_comptable(){
        return $this->belongTo(EcritureComptable::class);
    }
}
