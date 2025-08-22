<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nozle extends Model
{
    protected $table = 'nozle';

    protected $fillable = [
        'NamaNozle',
        'PulauId',
        'SpbuId',
    ];

     public function spbu()
    {
        return $this->belongsTo(SPBU::class, 'SpbuId', 'id');
    }
     public function pulau()
    {
        return $this->belongsTo(Pulau::class, 'PulauId', 'id');
    }

}
