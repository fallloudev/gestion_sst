<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produit;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produit::create([
            'nom' => 'Produit 1',
            'type' => 'Type 1',
            'unite' => 'g',
            'poids' => '250',
            'prix_vente' => 1000,
        ]);
        Produit::create([
            'nom' => 'Produit 2',
            'type' => 'Type 2',
            'unite' => 'g',
            'poids' => '5000',
            'prix_vente' => 2002,
        ]);
    }
}
