@extends('layouts.admin')

@section('page-title', 'Create New Batch')

@section('content')
<div class="">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.batches.add') }}" enctype="multipart/form-data">
            @csrf
            <!-- Batch Number -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Batch Number <span class="text-red-500">*</span>
                </label>
                <input type="text" name="batch_number" value="{{ old('batch_number') }}"
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
                        <option value="{{ $sku->id }}" {{ old('sku_id') == $sku->id ? 'selected' : '' }}>
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
                    <input type="number" name="total_vials" value="{{ old('total_vials') }}" min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="e.g., 100">
                    @error('total_vials')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lot Number</label>
                    <input type="text" name="lot_number" value="{{ old('lot_number') }}"
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
                    <input type="date" name="manufacture_date" value="{{ old('manufacture_date') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('manufacture_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Expiry Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
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
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="recalled" {{ old('status') == 'recalled' ? 'selected' : '' }}>Recalled</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- COA Document Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">COA Document</label>
                <input type="file" name="coa_document" accept=".pdf"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('coa_document')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-gray-500">Upload PDF Certificate of Analysis (Optional)</p>
                @enderror
            </div>

            <!-- Lab Results -->
            <!-- <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lab Results</label>
                <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Purity (%)</label>
                            <input type="number" step="0.01" name="lab_results[purity]" value="{{ old('lab_results.purity') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., 99.5">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Concentration (mg/ml)</label>
                            <input type="number" step="0.01" name="lab_results[concentration]" value="{{ old('lab_results.concentration') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., 10.0">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">pH Level</label>
                            <input type="number" step="0.1" name="lab_results[ph_level]" value="{{ old('lab_results.ph_level') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., 7.0">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Endotoxin (EU/mg)</label>
                            <input type="number" step="0.01" name="lab_results[endotoxin]" value="{{ old('lab_results.endotoxin') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., 0.5">
                        </div>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Lab test results (Optional)</p>
            </div> -->

            <input type="hidden" name="do_post" value="1">

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.batches.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Create Batch
                </button>
            </div>
        </form>
    </div>
</div>
@endsection