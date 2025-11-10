@extends('layouts.admin')

@section('page-title', 'UpdateBatch')

@section('content')
<div class="">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.batches.update', $batch->id) }}" enctype="multipart/form-data">
            @csrf
            <!-- Batch Number -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Batch Number <span class="text-red-500">*</span>
                </label>
                <input type="text" name="batch_number" value="{{ old('batch_number' ,$batch->batch_number) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="e.g., BATCH-2025-001">
                @error('batch_number')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-gray-500">Unique identifier for this batch</p>
                @enderror
            </div>
            <!-- SKU Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    SKU <span class="text-red-500">*</span>
                </label>
                <select name="sku_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Select SKU</option>
                    @foreach($skus as $sku)
                        <option value="{{ $sku->id }}" {{ old('sku_id', $batch->sku_id) == $sku->id ? 'selected' : ''  }}>
                            {{ $sku->sku_code }} - {{ $sku->product_name }}
                        </option>
                    @endforeach
                </select>
                @error('sku_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Total Vials & Lot Number -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Total Vials <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total_vials" value="{{ old('total_vials' , $batch->total_vials) }}" min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="e.g., 100">
                    @error('total_vials')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lot Number</label>
                    <input type="text" name="lot_number" value="{{ old('lot_number' , $batch->lot_number) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="e.g., LOT-2025-A">
                    @error('lot_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Manufacture Date & Expiry Date -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Manufacture Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="manufacture_date" value="{{ old('manufacture_date' , $batch->manufacture_date->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('manufacture_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Expiry Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date' , $batch->expiry_date->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('expiry_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="pending" {{ old('status', $batch->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ old('status', $batch->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ old('status', $batch->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="recalled" {{ old('status', $batch->status) == 'recalled' ? 'selected' : '' }}>Recalled</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- COA Document Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">COA Document</label>
                <input type="file" name="coa_document" accept=".pdf,image/*" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('coa_document')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-gray-500">Upload PDF Certificate of Analysis (Optional)</p>
                @enderror

                 <!-- Current Document Preview -->
                @if($batch->coa_document)
                    <div class="mt-3">
                        <a href="{{ Storage::disk('public')->url("coa_documents/{$batch->batch_number}/{$batch->coa_document}") }}" 
                            target="_blank" class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800 transition duration-200">
                            <i class="fas fa-eye"></i>
                            <span>View Current Document</span>
                        </a>
                    </div>
                @endif
            </div>

            <input type="hidden" name="do_post" value="1">

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.batches.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Batch
                </button>
            </div>
        </form>
    </div>
</div>
@endsection