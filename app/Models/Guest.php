<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Guest extends Model
{
    use HasFactory;

    protected $table = 'guests';

    protected $fillable = [
        'id', 'nama', 'tujuan', 'instansi', 'alamat', 'no_hp', 'foto', 'tanggal', 'jenis_kelamin'
    ];

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];

    public function getFotoAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}
