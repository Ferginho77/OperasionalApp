<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nozle extends Model
{
    protected $table = 'nozle';

    protected $fillable = [
        'Nama',
        'Pulau',
    ];

}
