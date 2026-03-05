<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'matakuliah_id',
        'jam_mulai',
        'jam_selesai',
        'hari',
    ];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }
}
