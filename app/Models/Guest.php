<?php

// App\Models\Guest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\WorkField;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'instansi', 'nama_instansi', 'alamat', 'tujuan_bidang',
        'tujuan_pengunjung', 'no_hp', 'foto', 'tanggal', 'jenis_kelamin', 'status'
    ];

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];

    public function getFotoAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }

    public function workField()
    {
        return $this->belongsTo(WorkField::class, 'tujuan_bidang');
    }
}

