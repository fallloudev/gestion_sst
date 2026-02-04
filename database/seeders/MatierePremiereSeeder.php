<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MatierePremiere;
use App\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatierePremiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        MatierePremiere::create([
            'nom' => 'Farine de blé',
            'type' => Constant::MATIERE_PREMIERE_TYPES['AGROALIMENTAIRE'],
            'unite' => 'kg',
            'seuil_min' => 50,
            'peremption_date' => Carbon::now()->addMonths(6),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        MatierePremiere::create([
            'nom' => 'Sucre',
            'type' => Constant::MATIERE_PREMIERE_TYPES['AGROALIMENTAIRE'],
            'unite' => 'kg',
            'seuil_min' => 30,
            'peremption_date' => Carbon::now()->addMonths(12),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        MatierePremiere::create([
            'nom' => 'Cacao en poudre',
            'type' => Constant::MATIERE_PREMIERE_TYPES['VEGETALE'],
            'unite' => 'kg',
            'seuil_min' => 20,
            'peremption_date' => Carbon::now()->addMonths(9),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
