<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesinAbsen extends Model
{
    protected $table = 'mesinabsen';

    protected $fillable = [
        'id_mesin',
        'ip_address',
        'port',
        'lokasi',
    ];
}
