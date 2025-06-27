<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PulauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        DB::table('pulau')->insert([
            ['NamaPulau' => 'A', 'SpbuId' => 1],
            ['NamaPulau' => 'B', 'SpbuId' => 1],
            ['NamaPulau' => 'C', 'SpbuId' => 1],
            ['NamaPulau' => 'A', 'SpbuId' => 2],
            ['NamaPulau' => 'B', 'SpbuId' => 2],
            ['NamaPulau' => 'C', 'SpbuId' => 2],
        ]);
    }
}
