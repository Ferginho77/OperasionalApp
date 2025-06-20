<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'NamaProduk',
        'HargaPerLiter',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'ProdukId');
    }
}
