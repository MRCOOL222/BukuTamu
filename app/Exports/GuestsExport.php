<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GuestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Guest::select('tanggal', 'nama', 'alamat', 'tujuan', 'instansi', 'no_hp')->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama', 'Alamat', 'Tujuan', 'Instansi', 'No HP'];
    }
}
