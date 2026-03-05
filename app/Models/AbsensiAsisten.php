<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiAsisten extends Model
{
    protected $table = 'absensi_asisten';

    protected $fillable = [
        'user_id',
        'matakuliah_id',
        'pertemuan',
        'tanggal',
        'jam_hadir',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }
}
