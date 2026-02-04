<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsommationMP extends Model
{
    protected $fillable = [
        'quantite', 'ordre_production_id','matiere_premiere_id'
    ]; 

    public function ordre_production(){
        return $this->belongsTo(OrdreProduction::class);
    }

    public function matiere_premiere(){
        return $this->belongsTo(MatierePremiere::class);
    }
}
