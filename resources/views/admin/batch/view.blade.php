@extends('layouts.admin')

@section('page-title', 'View Batch Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-semibold text-sage-800">Batch Details</h2>
            <a href="{{ route('admin.batches.index', ['sku' => $batch->sku_id]) }}" 
               class="px-4 py-2 text-sm bg-sage-100 hover:bg-sage-200 text-sage-700 rounded-lg">
                ← Back to List
            </a>
        </div>

        <!-- Batch Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-sage-500">Batch Number</p>
                <p class="text-lg font-medium text-sage-900">{{ $batch->batch_number }}</p>
            </div>
            <div>
                <p class="text-sm text-sage-500">SKU Code</p>
                <p class="text-lg font-medium text-sage-900">{{ $batch->sku->sku_code ?? '--' }}</p>
            </div>
            <div>
                <p class="text-sm text-sage-500">Total Vials</p>
                <p class="text-lg font-medium text-sage-900">{{ $batch->total_vials }}</p>
            </div>
            <div>
                <p class="text-sm text-sage-500">Status</p>
                <span class="
                    px-3 py-1 text-sm font-medium rounded-full
                    @if($batch->status == 'active') bg-green-100 text-green-700
                    @elseif($batch->status == 'expired') bg-red-100 text-red-700
                    @elseif($batch->status == 'recalled') bg-yellow-100 text-yellow-700
                    @else bg-sage-100 text-sage-700
                    @endif
                ">
                    {{ ucfirst($batch->status) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-sage-500">Manufacture Date</p>
                <p class="text-lg font-medium text-sage-900">
                    {{ $batch->manufacture_date->format('d-m-Y') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-sage-500">Expiry Date</p>
                <p class="text-lg font-medium text-sage-900">
                    {{ $batch->expiry_date->format('d-m-Y') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-sage-500">Lot Number</p>
                <p class="text-lg font-medium text-sage-900">
                    {{ $batch->lot_number ?? '--' }}
                </p>
            </div>
        </div>

        <!-- COA Document -->
        <div class="mt-8">
            <p class="text-sm text-sage-500 mb-2">COA Document</p>
                @if($batch->coa_document)
                    @php
                        $filePath = 'storage/coa_documents/' .$batch->batch_number . '/' . $batch->coa_document;
                        $extension = strtolower(pathinfo($batch->coa_document, PATHINFO_EXTENSION));
                    @endphp

                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        {{-- Image Preview --}}
                        <a href="{{ asset($filePath) }}" target="_blank">
                            <img src="{{ asset($filePath) }}" 
                                alt="Document Preview" 
                                class="w-12 h-12 object-cover rounded border border-sage-300 hover:shadow-md transition-shadow">
                        </a>
                    @elseif($extension === 'pdf')
                        {{-- PDF Icon --}}
                        <a href="{{ asset($filePath) }}" target="_blank" class="flex items-center space-x-2 text-red-600 hover:text-red-700">
                            <i class="fas fa-file-pdf text-2xl"></i>
                        </a>
                    @else
                        <a href="{{ asset($filePath) }}" target="_blank" class="text-indigo-600 hover:underline">
                            Download File
                        </a>
                    @endif
                @else
                    <span class="text-gray-400 italic">No document</span>
                @endif
        </div>

        <!-- Vials Table -->
        <div class="mt-10">
            <p class="text-sm text-sage-500 mb-2">Vials</p>
            @if($batch->vials->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-sage-200 rounded-lg divide-y divide-sage-200">
                        <thead class="bg-sage-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-sage-700">Vial Number</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-sage-700">Unique Code</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-sage-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-sage-500 uppercase tracking-wider">Scan At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-sage-100">
                            @foreach($batch->vials as $vial)
                                <tr>
                                    <td class="px-4 py-2 text-sage-800">{{ $vial->vial_number }}</td>
                                    <td class="px-4 py-2 text-sage-800">{{ $vial->unique_code ?? '--' }}</td>
                                    <td class="px-4 py-2">
                                       @if($vial->is_scanned)
                                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-sage-100 text-sage-700">
                                                <i class="fas fa-check-circle mr-1"></i>Scanned
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-sage-100 text-sage-700">
                                                <i class="fas fa-circle mr-1"></i>Not Scanned
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-sage-600">
                                        @if($vial->first_scan_at)
                                            {{ $vial->first_scan_at->format('d-m-Y, H:i:s') }}
                                        @else
                                            <span class="text-sage-400">—</span>
                                        @endif
                                    </td>                                 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sage-800">No vials available for this batch.</p>
            @endif
        </div>

        <!-- Buttons -->
        <div class="mt-10 flex justify-end space-x-4">
            <a href="{{ route('admin.batches.update', $batch->id) }}" 
               class="px-6 py-3 bg-sage-600 text-white rounded-lg hover:bg-sage-700 flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.skus.view', $batch->sku_id) }}" 
               class="px-6 py-3 bg-sage-100 text-sage-700 rounded-lg hover:bg-sage-200 flex items-center">
                <i class="fas fa-box mr-2"></i> View SKU
            </a>
        </div>

    </div>
</div>
@endsection
