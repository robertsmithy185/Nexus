<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable; // Tambahkan trait ini
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    protected $fillable = ['name', 'nim', 'prodi', 'password', 'role'];

    public function anggotaUkm()
    {
        return $this->hasMany(AnggotaUkm::class, 'nim', 'nim');
    }

}
