<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatierePremiere extends Model
{
    protected $fillable = [
        'nom','type','unite','seuil_min','peremption_date'
    ]; 


    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function consommation_m_p_s()
    {
        return $this->hasMany(ConsommationMP::class);
    }

    public function ligne_commande()
    {
        return $this->belongsTo(LigneCommande::class);
    }   

}
