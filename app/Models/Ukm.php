<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukm extends Model
{
    use HasFactory;

    protected $table = 'ukm';
    protected $primaryKey = 'kode'; // Menentukan primary key
    public $incrementing = false; // Karena 'kode' bukan integer auto increment
    protected $keyType = 'string'; // Jika 'kode' adalah string

    protected $fillable = ['kode', 'name', 'deskripsi', 'gambar', 'link_pendaftaran'];
    
    public function anggotaUkm()
    {
        return $this->hasMany(AnggotaUkm::class, 'kode_ukm', 'kode');
    }
}
