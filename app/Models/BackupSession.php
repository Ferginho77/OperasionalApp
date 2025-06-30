<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupSession extends Model
{
    protected $table = 'backupsession';

    protected $fillable = [
        'AbsensiId',
        'BackupOperatorId',
        'JamMulai',
        'JamSelesai',
        'TotalizerAwal',
        'TotalizerAkhir',
    ];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'AbsesnsiId', 'id');
    }

    public function backupOperator()
    {
        return $this->belongsTo(Karyawan::class, 'BackupOperatorId', 'id');
    }
}

