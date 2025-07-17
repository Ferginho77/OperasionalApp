<?php

namespace App\Imports;

use App\Models\JadwalOperator;
use App\Models\Karyawan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log; // Anda bisa menghapus ini jika tidak digunakan untuk debugging aktif
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Sudah di-use, bagus

class JadwalImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Log untuk debugging (opsional, bisa dihapus setelah berhasil)
            Log::info($row); // Anda sudah punya ini, bagus untuk melihat data mentah

            // Cek jika kolom tidak lengkap
            if (!isset($row['nama']) || !isset($row['tanggal']) || !isset($row['shift']) || !isset($row['nomorspbu'])) {
                // Log untuk mengetahui baris mana yang diskip karena data tidak lengkap
                Log::warning('Baris dilewati karena data tidak lengkap:', $row->toArray());
                continue;
            }

            // Konversi tanggal Excel ke format Y-m-d
            $tanggal = null;
            try {
                $tanggal = Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d');
            } catch (\Exception $e) {
                // Log untuk mengetahui baris mana yang diskip karena error konversi tanggal
                Log::error('Gagal mengonversi tanggal untuk baris:', ['row' => $row->toArray(), 'error' => $e->getMessage()]);
                continue;
            }

            // Mencari karyawan
            // Pastikan 'nama' ada di $row dan bukan null
            $namaKaryawan = $row['nama'];
            if (empty($namaKaryawan)) {
                Log::warning('Baris dilewati karena nama karyawan kosong:', $row->toArray());
                continue;
            }

            $karyawan = Karyawan::whereRaw('LOWER(Nama) = ?', [strtolower($namaKaryawan)])->first();
            if (!$karyawan) {
                Log::warning('Karyawan tidak ditemukan untuk nama:', ['nama' => $namaKaryawan, 'row' => $row->toArray()]);
                continue;
            }

            // Simpan data ke database
            try {
                JadwalOperator::create([
                    'KaryawanId' => $karyawan->id,
                    'Tanggal' => $tanggal, // <-- PERBAIKAN DI SINI! Gunakan variabel $tanggal yang sudah dikonversi
                    'Shift' => strtolower($row['shift']),
                    'NomorSPBU' => $row['nomorspbu'],
                ]);
                // Log jika berhasil menyimpan (opsional)
                Log::info('JadwalOperator berhasil dibuat:', ['KaryawanId' => $karyawan->id, 'Tanggal' => $tanggal]);
            } catch (\Exception $e) {
                // Log jika gagal menyimpan ke database
                Log::error('Gagal membuat JadwalOperator untuk baris:', ['row' => $row->toArray(), 'error' => $e->getMessage()]);
            }
        }
    }
}