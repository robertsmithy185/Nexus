<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaUkm extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika sesuai dengan konvensi)
    protected $table = 'anggota_ukm';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nim',
        'kode_ukm',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'kode_ukm', 'kode_ukm');
    }
    public function ukm()
    {
        return $this->belongsToMany(UKM::class,  'nim', 'kode_ukm');
    }
}
