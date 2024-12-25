<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable; // Tambahkan trait ini
    
    protected $table = 'pengurus';
    protected $primaryKey = 'nim_pengurus';
    protected $fillable = ['nim_pengurus', 'name', 'kode_ukm', 'role', 'password'];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function ukm()
    {
        return $this->belongsTo(Ukm::class, 'kode_ukm', 'kode');
    }
    public function informasi()
    {
        return $this->hasMany(Informasi::class, 'nim_pengurus', 'nim_pengurus');
    }
    
}
