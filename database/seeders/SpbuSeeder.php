<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpbuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('spbu')->insert([
            [
                'NamaSPBU' => 'SPBU Pertamina LeuwiGajah',
                'NomorSPBU' => '34.123.456',
                'Alamat' => 'Jl. Raya Cimahi No. 123, Cimahi',
                'UserId' => 1,
            ],
            [
                'NamaSPBU' => 'SPBU Pertamina Soreang',
                'NomorSPBU' => '34.654.321',
                'Alamat' => 'Jl. Raya Soreang No. 123, Bandung',
                'UserId' => 2,
            ],
        ]);
    }
}
