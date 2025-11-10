@extends('layouts.admin')

@section('page-title', 'Create New SKU')

@section('content')
<div class="">
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-8">
        <form method="POST" action="{{ route('admin.skus.add') }}">
            @csrf

            <!-- SKU Code -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-sage-700 mb-2">
                    SKU Code <span class="text-red-500">*</span>
                </label>
                <input type="text" name="sku_code" value="{{ old('sku_code') }}" 
                    class="w-full px-4 py-3 border border-sage-300 rounded-lg focus:ring-2 focus:ring-sage-500 focus:border-transparent"
                    placeholder="e.g., PEP-001">
                @error('sku_code')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-sage-500">Unique identifier for this product</p>
                @enderror
            </div>

            <!-- Product Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-sage-700 mb-2">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="product_name" value="{{ old('product_name') }}" 
                    class="w-full px-4 py-3 border border-sage-300 rounded-lg focus:ring-2 focus:ring-sage-500 focus:border-transparent"
                    placeholder="e.g., Peptide XYZ" >
                    @error('product_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-sage-700 mb-2">Description</label>
                <textarea name="description" rows="4" 
                    class="w-full px-4 py-3 border border-sage-300 rounded-lg focus:ring-2 focus:ring-sage-500 focus:border-transparent"
                    placeholder="Product description...">{{ old('description') }}</textarea>
            </div>

            <!-- Category & Unit Price -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-sage-700 mb-2">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}" 
                        class="w-full px-4 py-3 border border-sage-300 rounded-lg focus:ring-2 focus:ring-sage-500 focus:border-transparent"
                        placeholder="e.g., Peptides">
                </div>

                <div>
                    <label class="block text-sm font-medium text-sage-700 mb-2">Unit Price</label>
                    <input type="number" step="0.01" name="unit_price" value="{{ old('unit_price') }}" 
                        class="w-full px-4 py-3 border border-sage-300 rounded-lg focus:ring-2 focus:ring-sage-500 focus:border-transparent"
                        placeholder="0.00">
                </div>
            </div>

            <!-- Unit Measure -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-sage-700 mb-2">Unit Measure</label>
                <select name="unit_measure" class="w-full px-4 py-3 border border-sage-300 rounded-lg focus:ring-2 focus:ring-sage-500 focus:border-transparent">
                    <option value="">Select...</option>
                    <option value="mg">mg (Milligrams)</option>
                    <option value="ml">ml (Milliliters)</option>
                    <option value="g">g (Grams)</option>
                    <option value="l">l (Liters)</option>
                    <option value="units">Units</option>
                </select>
            </div>
            <input type="hidden" name="do_post" value="1">
            
            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.skus.index') }}" class="px-6 py-3 border border-sage-300 text-sage-700 rounded-lg hover:bg-sage-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-sage-600 text-white rounded-lg hover:bg-sage-700 transition-all flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Create SKU
                </button>
            </div>
        </form>
    </div>
</div>
@endsection