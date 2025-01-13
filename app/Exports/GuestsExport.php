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
    public function collection()
    {
        return Guest::select('tanggal', 'nama', 'alamat', 'tujuan', 'instansi', 'no_hp', 'foto')->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama', 'Alamat', 'Tujuan', 'Instansi', 'No HP', 'Foto'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $guests = $this->collection();
                $row = 2;
                
                // Style untuk header
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Set tinggi baris dan alignment untuk semua baris
                foreach ($guests as $index => $guest) {
                    $event->sheet->getRowDimension($row)->setRowHeight(100);
                    
                    // Set alignment untuk semua sel di baris ini
                    $event->sheet->getStyle('A'.$row.':G'.$row)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER
                        ]
                    ]);
                    
                    $row++;
                }

                // Set lebar kolom foto
                $event->sheet->getColumnDimension('G')->setWidth(30);
                
                $row = 2; // Reset row counter untuk gambar
                foreach ($guests as $guest) {
                    if (!empty($guest->foto)) {
                        $fotoPath = str_replace('/storage/', '', $guest->foto);
                        $fotoPath = str_replace('http://127.0.0.1:8000/storage/', '', $fotoPath);
                        
                        $fullPath = storage_path('app/public/' . $fotoPath);

                        if (file_exists($fullPath)) {
                            try {
                                $drawing = new Drawing();
                                $drawing->setName('Foto');
                                $drawing->setDescription('Foto');
                                $drawing->setPath($fullPath);
                                $drawing->setHeight(90);
                                $drawing->setWidth(90);
                                $drawing->setCoordinates('G' . $row);
                                
                                // Hitung offset untuk centering
                                $cellWidth = $event->sheet->getColumnDimension('G')->getWidth() * 7; // Approx pixel conversion
                                $cellHeight = $event->sheet->getRowDimension($row)->getRowHeight();
                                $offsetX = ($cellWidth - 90) / 2; // 90 adalah width gambar
                                $offsetY = ($cellHeight - 90) / 2; // 90 adalah height gambar
                                
                                $drawing->setOffsetX($offsetX);
                                $drawing->setOffsetY($offsetY);
                                
                                // Set rotation point to center
                                $drawing->setRotation(0);
                                $drawing->getShadow()->setVisible(false);
                                
                                $drawing->setWorksheet($event->sheet->getDelegate());
                                
                                // Kosongkan nilai sel
                                $event->sheet->setCellValue('G' . $row, '');
                            } catch (\Exception $e) {
                                \Log::error('Error adding image: ' . $e->getMessage());
                            }
                        }
                    }
                    $row++;
                }

                // Auto-size untuk kolom lain
                foreach (range('A', 'F') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
                
                // Border untuk semua sel
                $lastRow = $row - 1;
                $event->sheet->getStyle('A1:G'.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            }
        ];
    }
}