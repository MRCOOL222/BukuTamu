<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GuestsExport;

class RecapController extends Controller
{
    /**
     * Tampilkan data rekap dengan filter.
     */
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $guests = Guest::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', "%{$search}%")
                             ->orWhere('alamat', 'like', "%{$search}%")
                             ->orWhere('tujuan_pengunjung', 'like', "%{$search}%")
                             ->orWhere('instansi', 'like', "%{$search}%")
                             ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->when($startDate && !$endDate, function ($query) use ($startDate) {
                return $query->whereDate('tanggal', '>=', $startDate);
            })
            ->when(!$startDate && $endDate, function ($query) use ($endDate) {
                return $query->whereDate('tanggal', '<=', $endDate);
            })
            ->paginate(5);

        return view('recap', compact('guests'));
    }

    /**
     * Ekspor data rekap sesuai filter ke file Excel.
     */
    public function export(Request $request)
    {
        // Ambil parameter filter dari request
        $tujuan    = $request->input('tujuan');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // Buat instance export dengan filter
        $export = new GuestsExport($tujuan, $startDate, $endDate);

        return Excel::download($export, 'rekap_data_tamu.xlsx');
    }
}
