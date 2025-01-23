<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GuestExport;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $guests = Guest::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', "%$search%")
                            ->orWhere('alamat', 'like', "%$search%")
                            ->orWhere('tujuan', 'like', "%$search%")
                            ->orWhere('instansi', 'like', "%$search%")
                            ->orWhere('no_hp', 'like', "%$search%");
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


    public function export(Request $request)
    {
        // Retrieve filter parameters for export
        $tujuan = $request->input('tujuan');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Create the export instance with filters
        $export = new GuestExport($tujuan, $startDate, $endDate);

        return Excel::download($export, 'rekap_data_tamu.xlsx');
    }
}

