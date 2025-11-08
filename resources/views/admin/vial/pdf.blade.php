<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vials Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #4F46E5;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #DEF7EC;
            color: #03543F;
        }
        .badge-gray {
            background-color: #F3F4F6;
            color: #374151;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Vials Report</h1>
        <p>Generated on {{ now()->format('F d, Y - h:i A') }}</p>
        <p>Total Vials: {{ $vials->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Vial Number</th>
                <th>Batch Number</th>
                <th>SKU Code</th>
                <th>Product Name</th>
                <th>Unique Code</th>
                <th>Status</th>
                <th>QR Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vials as $vial)
            <tr>
                <td>{{ $vial->vial_number }}</td>
                <td>{{ $vial->batch->batch_number }}</td>
                <td>{{ $vial->batch->sku->sku_code }}</td>
                <td>{{ $vial->batch->sku->product_name ?? 'N/A' }}</td>
                <td style="font-size: 9px;">{{ $vial->unique_code }}</td>
                <td>
                    @if($vial->is_scanned)
                        <span class="badge badge-success">Scanned</span>
                    @else
                        <span class="badge badge-gray">Not Scanned</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($vial->qr_code_image)
                        <img src="{{ public_path('storage/' . $vial->qr_code_image) }}" 
                             alt="QR Code" 
                             class="qr-code">
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ now()->year }} Your Company Name. All rights reserved.</p>
    </div>
</body>
</html>