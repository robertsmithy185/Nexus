<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasi'; // Nama tabel
    protected $fillable = ['nim_pengurus', 'judul', 'informasi'];

    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'nim_pengurus', 'nim_pengurus');
    }
}