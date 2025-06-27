<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'Nama',
        'Nip',
        'Role',
        'NomorSPBU',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'KaryawanId', 'id');
    }
    
    public function totalizerAkhirTerakhir()
    {
        return $this->hasOne(Absensi::class, 'KaryawanId', 'id')->latestOfMany();
    }

    public function spbu()
    {
        return $this->belongsTo(Spbu::class, 'NomorSPBU', 'NomorSPBU');
    }
}
