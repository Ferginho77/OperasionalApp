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
                'NomorSPBU' => '24.07.085',
                'Alamat' => 'Jl. Mahar Martanegara No.131, Utama, Kec. Cimahi Sel., Kota Cimahi, Jawa Barat 40533',
                'UserId' => 1,
            ],
            [
                'NamaSPBU' => 'SPBU Pertamina Soreang',
                'NomorSPBU' => '34.654.321',
                'Alamat' => 'Jl. Raya Soreang - Banjaran Samping Puskesmas Soreang No.275, Soreang, Kec. Soreang, Kabupaten Bandung, Jawa Barat 40911',
                'UserId' => 5,
            ],
            [
                'NamaSPBU' => 'SPBU Pertamina Sayuran',
                'NomorSPBU' => '34.402.46',
                'Alamat' => 'Jl. Raya Sayuran Gg.bp entoy, Cangkuang Kulon, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40239',
                'UserId' => 3,
            ],
            [
                'NamaSPBU' => 'SPBU Pertamina Samarang',
                'NomorSPBU' => '34.441.19',
                'Alamat' => 'Jalan Raya Samarang-Garut No. 207 Tarogong Kaler, Mekarwangi, Garut, Kabupaten Garut, Jawa Barat 44151',
                'UserId' => 4,
            ],
            [
                'NamaSPBU' => 'SPBU Pertamina Tanggeung',
                'NomorSPBU' => '34.432.28',
                'Alamat' => 'Tanggeung, Kec. Tanggeung, Kabupaten Cianjur, Jawa Barat 43267',
                'UserId' => 7,
            ],
            [
                'NamaSPBU' => 'Pertashop Langensari',
                'NomorSPBU' => '3P.40313',
                'Alamat' => 'Jl. Dago Giri, Langensari, Kec. Lembang, Kabupaten Bandung Barat, Jawa Barat',
                'UserId' => 8,
            ],
            [
                'NamaSPBU' => 'Pertashop Cibodas',
                'NomorSPBU' => '3P.40303',
                'Alamat' => 'Cibodas, Kec. Lembang, Kabupaten Bandung Barat, Jawa Barat',
                'UserId' => 9,
            ],
            [
                'NamaSPBU' => 'SPBU Pertamina Wanaraja',
                'NomorSPBU' => '34.44112',
                'Alamat' => 'Jl. Raya Wanaraja, Sindangpalay, Kec. Karangpawitan, Kabupaten Garut, Jawa Barat 44182',
                'UserId' => 6,
            ],


        ]);
    }
}
