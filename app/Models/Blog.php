<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use \Illuminate\Auth\Authenticatable; // Tambahkan trait ini
    protected $table = 'blog'; // Nama tabel
    protected $fillable = [
        'nim_pengurus',
        'judul',
        'deskripsi',
        'gambar',
    ];
    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'nim_pengurus', 'nim_pengurus');
    }
}
