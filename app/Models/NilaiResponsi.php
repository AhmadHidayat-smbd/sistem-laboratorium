<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiResponsi extends Model
{
    use HasFactory;

    protected $table = 'nilai_responsi';
    protected $fillable = ['mahasiswa_id', 'matakuliah_id', 'nilai', 'catatan'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }
}
