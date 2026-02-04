<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdreProduction extends Model
{
    protected $fillable = [
        'quantite_prevue','quantite_produite','date','statut','produit_id','reference',
    ]; 

    public function produit(){
        return $this->belongsTo(Produit::class);
    }

    public function consommation_m_p_s()
    {
        return $this->hasMany(ConsommationMP::class);
    }


}
