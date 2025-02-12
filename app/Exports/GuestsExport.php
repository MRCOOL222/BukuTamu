<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GuestsExport implements FromCollection, WithHeadings, WithEvents
{
    protected $tujuan;
    protected $startDate;
    protected $endDate;
    protected $guests;

    /**
     * Terima parameter filter dari RecapController.
     *
     * @param string|null $tujuan
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct($tujuan, $startDate, $endDate)
    {
        $this->tujuan    = $tujuan;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    /**
     * Ambil data tamu sesuai filter dan transformasikan agar output sesuai dengan tampilan index.
     */
    public function collection()
    {
        // Pastikan untuk memuat relasi workField agar bisa diakses
        $query = Guest::query()->with('workField');

        // Gunakan kolom 'tujuan_pengunjung' untuk filter
        if (!empty($this->tujuan)) {
            $query->where('tujuan_pengunjung', 'like', "%{$this->tujuan}%");
        }

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        } elseif (!empty($this->startDate)) {
            $query->whereDate('tanggal', '>=', $this->startDate);
        } elseif (!empty($this->endDate)) {
            $query->whereDate('tanggal', '<=', $this->endDate);
        }

        // Ambil semua data yang memenuhi filter
        $guests = $query->get();

        // Transformasikan data agar sesuai dengan tampilan index.blade.php
        $this->guests = $guests->map(function ($guest) {
            // Kolom JK: gunakan nilai jenis_kelamin dengan huruf pertama kapital
            $jk = ucfirst($guest->jenis_kelamin);

            // Kolom Tujuan: gabungkan data dari workField dan tujuan_pengunjung
            $tujuan = "";
            if ($guest->workField && $guest->tujuan_pengunjung) {
                $tujuan = "(" . $guest->workField->name . ") " . $guest->tujuan_pengunjung;
            } elseif ($guest->workField) {
                $tujuan = "(" . $guest->workField->name . ")";
            } elseif ($guest->tujuan_pengunjung) {
                $tujuan = $guest->tujuan_pengunjung;
            }

            // Kolom Instansi: gabungkan data dari instansi dan nama_instansi
            $instansi = "";
            if ($guest->instansi && $guest->nama_instansi) {
                $instansi = "(" . $guest->instansi . ") " . $guest->nama_instansi;
            } elseif ($guest->instansi) {
                $instansi = "(" . $guest->instansi . ")";
            } elseif ($guest->nama_instansi) {
                $instansi = $guest->nama_instansi;
            }

            return [
                'tanggal'   => $guest->tanggal,
                'nama'      => $guest->nama,
                'alamat'    => $guest->alamat,
                'jk'        => $jk,
                'tujuan'    => $tujuan,
                'instansi'  => $instansi,
                'no_hp'     => $guest->no_hp,
                'foto'      => $guest->foto,
                'status'    => $guest->status,
            ];
        });

        return $this->guests;
    }

    /**
     * Header untuk file Excel.
     */
    public function headings(): array
    {
        return ['Tanggal', 'Nama', 'Alamat', 'JK', 'Tujuan', 'Instansi', 'No HP', 'Foto', 'Status'];
    }

    /**
     * Mengatur event setelah sheet di-generate (misal: styling, menambahkan gambar).
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $guests = $this->guests;
                $row = 2;
                $lastColumn = 'I'; // Karena kita memiliki 9 kolom: A sampai I

                // Style header (baris 1, kolom A sampai I)
                $event->sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Set tinggi baris dan alignment untuk setiap baris data
                foreach ($guests as $index => $guest) {
                    $event->sheet->getRowDimension($row)->setRowHeight(100);
                    $event->sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $row++;
                }

                // Atur lebar kolom untuk foto (misalnya kolom H)
                $event->sheet->getColumnDimension('H')->setWidth(30);

                // Tambahkan gambar ke kolom Foto (kolom H)
                $row = 2;
                foreach ($guests as $guest) {
                    if (!empty($guest['foto'])) {
                        // Sesuaikan path foto sesuai penyimpanan Anda
                        $fotoPath = str_replace(['/storage/', 'http://127.0.0.1:8000/storage/'], '', $guest['foto']);
                        $fullPath = storage_path('app/public/' . $fotoPath);

                        if (file_exists($fullPath)) {
                            try {
                                $drawing = new Drawing();
                                $drawing->setName('Foto');
                                $drawing->setDescription('Foto');
                                $drawing->setPath($fullPath);
                                $drawing->setHeight(90);
                                $drawing->setWidth(90);
                                $drawing->setCoordinates('H' . $row);

                                // Hitung offset untuk centering gambar dalam sel
                                $cellWidth = $event->sheet->getColumnDimension('H')->getWidth() * 7;
                                $cellHeight = $event->sheet->getRowDimension($row)->getRowHeight();
                                $offsetX = ($cellWidth - 90) / 2;
                                $offsetY = ($cellHeight - 90) / 2;
                                $drawing->setOffsetX($offsetX);
                                $drawing->setOffsetY($offsetY);
                                $drawing->setRotation(0);
                                $drawing->getShadow()->setVisible(false);
                                $drawing->setWorksheet($event->sheet->getDelegate());

                                // Kosongkan nilai sel agar tidak tertulis path foto
                                $event->sheet->setCellValue('H' . $row, '');
                            } catch (\Exception $e) {
                                \Log::error('Error adding image: ' . $e->getMessage());
                            }
                        }
                    }
                    $row++;
                }

                // Auto-size kolom A sampai I (kecuali kolom H sudah di-set lebarnya)
                foreach (range('A', 'I') as $column) {
                    if ($column != 'H') {
                        $event->sheet->getColumnDimension($column)->setAutoSize(true);
                    }
                }

                // Tambahkan border untuk semua sel
                $lastRow = $row - 1;
                $event->sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}
