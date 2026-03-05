<?php

namespace App\Models;

use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah';

    protected $fillable = [
        'kode',
        'nama',
        'tahun_ajaran',
        'is_active',
        'dosen_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ===== SCOPES =====

    /**
     * Scope: hanya matakuliah yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ===== RELASI =====

    // RELASI MANY TO MANY
    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_matakuliah', 'matakuliah_id', 'mahasiswa_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Relasi: Matakuliah diampu oleh satu Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
