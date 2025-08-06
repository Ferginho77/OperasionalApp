<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tangki extends Model
{
  protected $table = 'tangki';

    protected $fillable = [
        'Produk',
        'Ukuran',
        'Pulau',
        'Dispenser',
        'Nozle',
        'SpbuId'
    ];


     public function spbu()
{
    return $this->belongsTo(Spbu::class, 'SpbuId', 'id');
}
}
