<?php

namespace App\Exports;

use App\Models\Vial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VialsExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithStyles
{
    protected $vials;
    protected $rowNumber = 2;

    public function __construct($vials = null)
    {
        $this->vials = $vials ?? Vial::with(['batch.sku'])->get();
    }

    public function collection()
    {
        return $this->vials;
    }

    public function headings(): array
    {
        return [
            'Vial Number',
            'Batch Number',
            'SKU Code',
            'Product Name',
            'Unique Code',
            'Scanned',
            'Scanned At',
            'QR Code',
        ];
    }

    public function map($vial): array
    {
        return [
            $vial->vial_number,
            $vial->batch->batch_number,
            $vial->batch->sku->sku_code,
            $vial->batch->sku->product_name ?? 'N/A',
            $vial->unique_code,
            $vial->is_scanned ? 'Yes' : 'No',
            $vial->first_scanned_at ? $vial->first_scanned_at->format('Y-m-d H:i:s') : 'N/A',
            '', // QR Code column (will be filled with images)
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2; // Start from row 2 (after header)

        foreach ($this->vials as $vial) {
            if ($vial->qr_code_image) {
                $qrPath = storage_path('app/public/' . $vial->qr_code_image);

                if (file_exists($qrPath)) {
                    $drawing = new Drawing();
                    $drawing->setName('QR Code');
                    $drawing->setDescription('QR Code for ' . $vial->vial_number);
                    $drawing->setPath($qrPath);
                    $drawing->setHeight(80);
                    $drawing->setCoordinates('H' . $row);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(10);
                    
                    $drawings[] = $drawing;
                }
            }
            $row++;
        }

        return $drawings;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 15,
            'D' => 25,
            'E' => 20,
            'F' => 10,
            'G' => 20,
            'H' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set row height for all data rows
        $lastRow = $this->vials->count() + 1;
        for ($i = 2; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(90);
        }

        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}