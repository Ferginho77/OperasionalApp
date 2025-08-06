<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'KaryawanId',
        'Tanggal',
        'JamMasuk',
        'JamIstirahatMulai',
        'JamKembaliNozle',
        'JamIstirahatKembali',
        'JamPulang',
        'NozleId',
        'ProdukId',
        'Pulau',
        'TotalizerAwal',
        'TotalizerAkhir',
    ];

    // Relasi ke Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'KaryawanId', 'id');
    }

    // Relasi ke Nozle
    public function nozle()
    {
        return $this->belongsTo(Nozle::class, 'NozleId', 'id');
    }

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ProdukId', 'id');
    }

    // Relasi ke BackupSession
    public function backupSessions()
    {
        return $this->hasMany(BackupSession::class, 'AbsensiId', 'id');
    }

    // Akses Total Liter
    public function getTotalLiterAttribute()
    {
        if ($this->TotalizerAwal !== null && $this->TotalizerAkhir !== null) {
            return $this->TotalizerAkhir - $this->TotalizerAwal;
        }
        return 0;
    }

    // Akses Total Penjualan
    public function getTotalPenjualanAttribute()
    {
        if ($this->produk && $this->TotalLiter) {
            return $this->TotalLiter * $this->produk->HargaPerLiter;
        }
        return 0;
    }

    // Akses Insentif
    public function getInsentifAttribute()
    {
        return $this->TotalPenjualan * 0.01;
    }

    public function totalizerAkhirTerakhir()
    {
        return $this->hasOne(Absensi::class, 'KaryawanId', 'id')->latestOfMany();
    }
}
