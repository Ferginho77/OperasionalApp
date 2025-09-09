<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'SpbuId', // Tambahkan SpbuId
        'NozzelId',
        'PulauId',
        'ProdukId',
        'TelerAwal',
        'TelerAkhir',
        'Jumlah',
        'JumlahRupiah',
    ];

    // Relasi ke tabel SPBU
    public function spbu()
    {
        return $this->belongsTo(Spbu::class, 'SpbuId');
    }

    // Relasi ke tabel Nozle
    public function nozle()
    {
        return $this->belongsTo(Nozle::class, 'NozzelId');
    }

    // Relasi ke tabel Pulau
    public function pulau()
    {
        return $this->belongsTo(Pulau::class, 'PulauId');
    }

    // Relasi ke tabel Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ProdukId');
    }
}