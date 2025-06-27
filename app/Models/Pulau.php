<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pulau extends Model
{
    protected $table = 'pulau';
    
    protected $fillable = [
        'NamaPulau',
        'SpbuId',
    ];

    public function spbu()
{
    return $this->belongsTo(Spbu::class, 'SpbuId', 'id');
}

}
