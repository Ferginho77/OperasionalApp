<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';

    protected $fillable = [
        'KaryawanId', 'WaktuMasuk', 'WaktuPulang', 'SpbuId'
    ];
}
