<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NozleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nozle')->insert([
            ['NamaNozle' => 'Nozle 1', 'Pulau' => 'A'],
            ['NamaNozle' => 'Nozle 2', 'Pulau' => 'A'],
            ['NamaNozle' => 'Nozle 3', 'Pulau' => 'B'],
            ['NamaNozle' => 'Nozle 4', 'Pulau' => 'B'],
            ['NamaNozle' => 'Nozle 5', 'Pulau' => 'C'],
        ]);
    }
}
