<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'libelle' => Constant::ROLES['ROOT'],
        ]);
        Role::create([
            'libelle' => Constant::ROLES['ADMIN'],
        ]);
        Role::create([
            'libelle' => Constant::ROLES['PRODUCTION'],
        ]);
        Role::create([
            'libelle' => Constant::ROLES['COMMERCIAL'],
        ]);
    }
}
