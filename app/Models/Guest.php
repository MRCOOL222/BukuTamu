<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan nama model
    protected $table = 'guests';

    // Tentukan atribut yang dapat diisi (fillable)
    protected $fillable = [
        'nama', 'tujuan', 'instansi', 'alamat', 'no_hp', 'foto'
    ];

    // Jika foto adalah nullable dan disimpan secara terpisah, Anda bisa mengonfigurasi pathnya di sini
    public function getFotoAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}

