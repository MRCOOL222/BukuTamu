<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Data historis: jumlah tamu per bulan (untuk referensi, jika diperlukan)
        $guests = Guest::selectRaw('MONTH(tanggal) as month, YEAR(tanggal) as year, count(*) as total')
                        ->groupBy('month', 'year')
                        ->orderBy('year')
                        ->orderBy('month')
                        ->get()
                        ->map(function($guest) {
                            $guest->total = (int) $guest->total;
                            return $guest;
                        });

        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        // Data tamu bulan ini berdasarkan instansi
        $dinasCount = Guest::whereMonth('tanggal', $currentMonth)
                           ->whereYear('tanggal', $currentYear)
                           ->where('instansi', 'Dinas')
                           ->count();

        $nonKedinasanCount = Guest::whereMonth('tanggal', $currentMonth)
                                  ->whereYear('tanggal', $currentYear)
                                  ->where(function($query) {
                                      $query->whereNull('instansi')
                                            ->orWhere('instansi', '<>', 'Dinas');
                                  })
                                  ->count();

        // Total tamu bulan ini (dari data historis)
        $currentGuest = $guests->first(function($item) use ($currentMonth, $currentYear) {
            return $item->month == $currentMonth && $item->year == $currentYear;
        });
        $currentTotal = $currentGuest ? $currentGuest->total : 0;

        // Data tamu bulan ini berdasarkan bidang (work field)
        $tamuByWorkField = Guest::with('workField')
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->get()
            ->groupBy(function($guest) {
                return $guest->workField ? $guest->workField->name : 'Unknown';
            })
            ->map(function($group) {
                return $group->count();
            });
        $bidangSeries = $tamuByWorkField->map(function($count, $fieldName) {
            return [
                'name' => $fieldName,
                'data' => [$count]
            ];
        })->values();

        // Data tamu bulan ini berdasarkan jenis kelamin
        // Misalkan 'l' = Laki-Laki, 'p' = Perempuan
        $maleCount = Guest::whereMonth('tanggal', $currentMonth)
                          ->whereYear('tanggal', $currentYear)
                          ->where('jenis_kelamin', 'l')
                          ->count();
        $femaleCount = Guest::whereMonth('tanggal', $currentMonth)
                          ->whereYear('tanggal', $currentYear)
                          ->where('jenis_kelamin', 'p')
                          ->count();
        $genderSeries = [
            [
                'name' => 'Laki-Laki',
                'data' => [$maleCount]
            ],
            [
                'name' => 'Perempuan',
                'data' => [$femaleCount]
            ]
        ];

        return view('dashboard', compact(
            'guests', 
            'dinasCount', 
            'nonKedinasanCount', 
            'currentTotal',
            'bidangSeries',
            'genderSeries'
        ));
    }
}
