@extends('layouts.admin')

@section('page-title', 'View SKU Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-semibold text-gray-800">SKU Details</h2>
            <a href="{{ route('admin.skus.index') }}" 
               class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                ← Back to List
            </a>
        </div>

        <!-- SKU Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">SKU Code</p>
                <p class="text-lg font-medium text-gray-900">{{ $sku->sku_code ?? '--' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Product Name</p>
                <p class="text-lg font-medium text-gray-900">{{ $sku->product_name ?? '--' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Category</p>
                <p class="text-lg font-medium text-gray-900">{{ $sku->category ?? '--' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Unit Price</p>
                <p class="text-lg font-medium text-gray-900">
                    {{ $sku->unit_price ? number_format($sku->unit_price, 2) : '--' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Unit Measure</p>
                <p class="text-lg font-medium text-gray-900">{{ $sku->unit_measure ?? '--' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Total Batches</p>
                <p class="text-lg font-medium text-gray-900">{{ $sku->batches_count ?? 0 }}</p>
            </div>

        </div>

        <!-- Description -->
        <div class="mt-8">
            <p class="text-sm text-gray-500 mb-2">Description</p>
            <p class="text-gray-800 leading-relaxed">
                {{ $sku->description ?: 'No description available.' }}
            </p>
        </div>

        <!-- Buttons -->
        <div class="mt-10 flex justify-end space-x-4">
            <a href="{{ route('admin.skus.update', $sku->id) }}" 
               class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.batches.index', ['sku' => $sku->id]) }}" 
               class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                <i class="fas fa-layer-group mr-2"></i> View Batches
            </a>
        </div>

    </div>
</div>
@endsection
