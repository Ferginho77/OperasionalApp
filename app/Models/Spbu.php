<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spbu extends Model
{
    protected $table = 'spbu';

    protected $fillable = [
        'NamaSPBU',
        'NomorSPBU',
        'Alamat',
        'UserId',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'SpbuId');
    }
}
