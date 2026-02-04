<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nom','type','telephone','adresse'
    ]; 


    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
    
}
