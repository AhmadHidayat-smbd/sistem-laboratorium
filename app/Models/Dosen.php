<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'dosen';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'rfid_uid',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            // Password hashing handled manually via Hash::make()
        ];
    }

    /**
     * Relasi: Dosen mengampu banyak Matakuliah
     */
    public function matakuliah()
    {
        return $this->hasMany(Matakuliah::class, 'dosen_id');
    }

    /**
     * Relasi: Dosen memiliki banyak absensi
     */
    public function absensi()
    {
        return $this->hasMany(AbsensiDosen::class, 'dosen_id');
    }
}
