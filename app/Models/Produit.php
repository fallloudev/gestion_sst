<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    
    protected $table = 'produits'; 
    protected $fillable = [
        'nom','type','prix_vente','unite','poids',
    ]; 

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function ordre_productions()
    {
        return $this->hasMany(OrdreProduction::class);
    }

}
