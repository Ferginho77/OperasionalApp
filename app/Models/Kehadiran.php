<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';

    protected $fillable = [
        'KaryawanId', 'WaktuMasuk', 'WaktuPulang', 'SpbuId'
    ];

      public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'KaryawanId');
    }

    public function spbu()
{
    return $this->belongsTo(Spbu::class, 'spbu_id'); // Adjust 'spbu_id' to the correct foreign key
}
}
