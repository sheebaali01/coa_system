<?php
namespace App\Http\Controllers;

use App\Exports\QRCodesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function exportQRCodes()
    {
        // Your QR code data
        $qrData = [
            ['id' => 1, 'name' => 'User 1', 'value' => 'https://example.com/user/1'],
            ['id' => 2, 'name' => 'User 2', 'value' => 'https://example.com/user/2'],
            ['id' => 3, 'name' => 'User 3', 'value' => 'https://example.com/user/3'],
            // Add more data as needed
        ];

        return Excel::download(
            new QRCodesExport($qrData), 
            'qr-codes-' . date('Y-m-d') . '.xlsx'
        );
    }

    // Alternative: Send via email
    public function emailQRCodes(Request $request)
    {
        $qrData = [
            ['id' => 1, 'name' => 'User 1', 'value' => 'https://example.com/user/1'],
            // ... more data
        ];

        Excel::store(
            new QRCodesExport($qrData), 
            'qr-codes.xlsx', 
            'public'
        );

        // Send email with attachment
        $filePath = storage_path('app/public/qr-codes.xlsx');
        
        Mail::to($request->email)->send(new QRCodesEmail($filePath));

        return response()->json(['message' => 'Email sent successfully']);
    }
}
