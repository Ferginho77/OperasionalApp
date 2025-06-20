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
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
