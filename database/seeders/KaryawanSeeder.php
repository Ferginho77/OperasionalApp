<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('karyawan')->insert([
            [
                'Nama' => 'Rina Mawarni',
                'Role' => 'Admin',
                'Nip' => '100001',
                'FacialId' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'Nama' => 'Fajar Hidayat',
                'Role' => 'Operator',
                'Nip' => '100002',
                'FacialId' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'Nama' => 'Deni Pratama',
                'Role' => 'Accounting',
                'Nip' => '100003',
                'FacialId' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'Nama' => 'Andi Nugraha',
                'Role' => 'OB',
                'Nip' => '100004',
                'FacialId' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'Nama' => 'Siti Lestari',
                'Role' => 'Pengawas',
                'Nip' => '100005',
                'FacialId' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}

