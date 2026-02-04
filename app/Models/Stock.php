<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'quantite',
        'matiere_premiere_id',
        'produit_id',
        'entrepot_id',
        'date',
        'commentaire',
    ]; 

    public function entrepot(){
        return $this->belongsTo(Entrepot::class);
    }
    public function matiere_premiere(){
        return $this->belongsTo(MatierePremiere::class);
    }
    public function produit(){
        return $this->belongsTo(Produit::class);
    }
    public function mouvement_stocks(){
        return $this->hasMany(MouvementStock::class);
    }

    public function getStatutStockAttribute()
    {
        $seuil = $this->matiere_premiere->seuil_min;

        if ($this->quantite <= $seuil) {
            return 'critique';
        }

        if ($this->quantite <= $seuil * 1.5) {
            return 'faible';
        }

        return 'ok';
    }


}
