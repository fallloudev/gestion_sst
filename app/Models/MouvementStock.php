<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    protected $fillable = [
        'stock_id','type','quantite','reference','date'
    ]; 

    protected $casts = [
        'date' => 'datetime', // 🔥 LA SOLUTION
    ];
    
    public function stock(){
        return $this->belongsTo(Stock::class);
    }

}
