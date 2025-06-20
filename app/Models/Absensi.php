<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = [
        'KaryawanId', 'Tanggal', 'JamMasuk', 'JamIstirahatMulai', 'JamPindahNozle',
        'JamKembaliNozle', 'JamIstirahatKembali', 'JamPulang',
        'NozleId', 'ProdukId', 'Pulau', 'TotalizerAwal', 'TotalizerAkhir'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'KaryawanId');
    }

    public function nozle()
    {
        return $this->belongsTo(Nozle::class, 'NozleId');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ProdukId');
    }

    public function getTotalLiterAttribute()
    {
        return $this->TotalizerAkhir - $this->TotalizerAwal;
    }

    public function getTotalPenjualanAttribute()
    {
        return $this->TotalLiter * $this->produk->HargaPerLiter;
    }

    public function getInsentifAttribute()
    {
        return $this->TotalPenjualan * 0.01;
    }
}

