<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'Fergie',
            'password' => Hash::make('321'),
            'role' => 'admin',
        ]);

         User::create([
            'username' => 'Fandi',
            'password' => Hash::make('1234'),
            'role' => 'owner',
        ]);
         User::create([
            'username' => 'SPBU Sayuran',
            'password' => Hash::make('3440246'),
            'role' => 'admin',
            'NomorSpbu' => '34.402.46',
        ]);
         User::create([
            'username' => 'SPBU Samarang',
            'password' => Hash::make('3444119'),
            'role' => 'admin',
            'NomorSpbu' => '34.441.19',
        ]);
         User::create([
            'username' => 'SPBU_Soreang',
            'password' => Hash::make('3440905'),
            'role' => 'admin',
            'NomorSpbu' => '34.40905',
        ]);
         User::create([
            'username' => 'SPBU_Wanaraja',
            'password' => Hash::make('3444112'),
            'role' => 'admin',
            'NomorSpbu' => '34.44112',
        ]);
         User::create([
            'username' => 'SPBU Tanggeung',
            'password' => Hash::make('3443228'),
            'role' => 'admin',
            'NomorSpbu' => '34.432.28',
        ]);
         User::create([
            'username' => 'Pertashop Langensari',
            'password' => Hash::make('3P40313'),
            'role' => 'admin',
            'NomorSpbu' => '3P.40313',
        ]);
         User::create([
            'username' => 'Pertashop Cibodas',
            'password' => Hash::make('3P40303'),
            'role' => 'admin',
            'NomorSpbu' => '3P.40303',
        ]);

    }
}
