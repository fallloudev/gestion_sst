<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entrepot;

class EntrepotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Entrepot::create([
            'nom' => 'Entrepot 1',
            'localisation' => 'Dakar',
        ]);
        Entrepot::create([
            'nom' => 'Entrepot 2',
            'localisation' => 'Dakar',
        ]);
    }
}
