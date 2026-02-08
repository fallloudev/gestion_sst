<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Commande extends Model
{
    protected $fillable = [
        'client_id','nom','date','statut','total','user_id'
    ];

    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
