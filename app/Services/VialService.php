<?php

namespace App\Services;

use App\Models\Vial;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            $code = $batch->batch_number . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $path = "qr_codes/{$batch->batch_number}/{$code}.svg";

            // Generate QR code
            $qr = QrCode::size(300)->generate($code);

            // Save to storage/app/public/qr_codes/{batch_number}/
            Storage::disk('public')->put($path, $qr);
            // $file->storeAs("coa_documents/{$request->batch_number}", $fileName, 'public');

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
