<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Guest extends Model
{
    use HasFactory;

    protected $table = 'guests';

    // Tentukan atribut yang dapat diisi (fillable)
    protected $fillable = [
        'id', 'nama', 'tujuan', 'instansi', 'alamat', 'no_hp', 'foto'
    ];

    // Accessor untuk mendapatkan URL foto
    public function getFotoAttribute($value)
    {
        return $value ? Storage::url($value) : null; // Menghasilkan URL foto dari folder public/uploads/
    }
}
