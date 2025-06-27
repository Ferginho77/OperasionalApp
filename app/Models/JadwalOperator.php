<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalOperator extends Model
{
    protected $table = 'JadwalOperator'; // atau 'jadwal_operator' jika sudah diganti

    protected $fillable = [
        'KaryawanId',
        'Tanggal',
        'Shift',
        'NomorSPBU',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'KaryawanId');
    }
}

