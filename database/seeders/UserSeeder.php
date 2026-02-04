<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

use Illuminate\Support\Facades\Hash;
use App\Constant;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Root',
            'email' => 'root@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('libelle', Constant::ROLES['ROOT'])->first()->id,
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('libelle', Constant::ROLES['ADMIN'])->first()->id,
        ]);

        User::create([
            'name' => 'Production',
            'email' => 'production@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('libelle', Constant::ROLES['PRODUCTION'])->first()->id,
        ]);

        User::create([
            'name' => 'Commercial',
            'email' => 'commercial@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('libelle', Constant::ROLES['COMMERCIAL'])->first()->id,
        ]);
    }
}
