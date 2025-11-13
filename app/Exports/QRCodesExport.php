<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Collection;

class QRCodesExport implements FromCollection, WithHeadings, WithDrawings, WithColumnWidths, WithStyles
{
    protected $data;
    protected $qrImages = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item, $index) {
            return [
                'id' => $item['id'] ?? $index + 1,
                'name' => $item['name'] ?? '',
                'qr_code' => '', // Placeholder for QR image
                'value' => $item['value'] ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'QR Code', 'Value'];
    }

    public function drawings()
    {
        $drawings = [];
        
        foreach ($this->data as $index => $item) {
            $value = $item['value'] ?? $item['id'] ?? '';
            
            // Create temp directory if not exists
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $path = $tempDir . '/qr_' . $index . '_' . time() . '.png';
            
            // Generate QR code using Endroid (no Imagick/GD required!)
            $qrCode = QrCode::create($value)
                ->setSize(300)
                ->setMargin(10);
            
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            // Save to file
            $result->saveToFile($path);
            
            $this->qrImages[] = $path;
            
            // Create drawing
            $drawing = new Drawing();
            $drawing->setName('QR Code');
            $drawing->setDescription('QR Code');
            $drawing->setPath($path);
            $drawing->setHeight(100);
            $drawing->setCoordinates('C' . ($index + 2));
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(5);
            
            $drawings[] = $drawing;
        }
        
        return $drawings;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 25,
            'C' => 20,
            'D' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set row height for QR codes
        foreach ($this->data as $index => $item) {
            $sheet->getRowDimension($index + 2)->setRowHeight(80);
        }
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function __destruct()
    {
        // Clean up temporary files
        foreach ($this->qrImages as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}