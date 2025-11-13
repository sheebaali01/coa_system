<?php

namespace App\Services;

use App\Models\Vial;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class VialService
{
    /**
     * Generate vials with QR codes for a given batch.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function generateVialsForBatch($batch)
    {
        $vials = [];

        for ($i = 1; $i <= $batch->total_vials; $i++) {
            $code = $batch->batch_number . '-'. $batch->id. '-' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $path = "qr_codes/{$batch->batch_number}/{$code}.png";
            $url = route('vial.instant-redirect', ['vial' => $code]);

            // Generate QR code
            $qr = QrCode::create($url)
                ->setSize(300)
                ->setMargin(10);
            
            $writer = new PngWriter();
            $result = $writer->write($qr);
            
            // Save to storage/app/public/qr_codes/{batch_number}/
            // Use getString() to get the binary PNG data
            Storage::disk('public')->put($path, $result->getString());

            // Prepare vial data
            $vials[] = [
                'batch_id' => $batch->id,
                'vial_number' => "VIAL #{$i}",
                'qr_code_image' => $path,
                'unique_code' => $code,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all vials in one go (fast)
        Vial::insert($vials);
    }
}