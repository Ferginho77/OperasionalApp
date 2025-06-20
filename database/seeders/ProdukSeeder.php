<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('produk')->insert([
            ['NamaProduk' => 'Pertalite', 'HargaPerLiter' => 10000.00],
            ['NamaProduk' => 'Pertamax', 'HargaPerLiter' => 12000.00],
            ['NamaProduk' => 'Solar', 'HargaPerLiter' => 9000.00],
            ['NamaProduk' => 'Dexlite', 'HargaPerLiter' => 13000.00],
            ['NamaProduk' => 'Pertamax Turbo', 'HargaPerLiter' => 15000.00],
        ]);
    }
}
