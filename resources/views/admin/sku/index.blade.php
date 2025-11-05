@extends('layouts.admin')

@section('page-title', 'SKU Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-gray-600">Manage your product SKUs</p>
    </div>
    <a href="{{ route('admin.skus.add') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Add New SKU
    </a>
</div>
<!-- SKU Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800">All SKUs</h3>
    </div>

    <div class="overflow-x-auto">
        @if($skus->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Code</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batches</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($skus as $sku)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono font-semibold text-indigo-600">{{ $sku->sku_code }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $sku->product_name }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($sku->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                            {{ $sku->category ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $sku->batches_count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($sku->is_active)
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                            Active
                        </span>
                        @else
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                            Inactive
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.skus.view', $sku->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.skus.update', $sku->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.batches.index', ['sku' => $sku->id]) }}" class="text-green-600 hover:text-green-900" title="Add Batch">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">No SKUs found. Create your first SKU to get started!</p>
                        <a href="{{ route('admin.skus.create') }}" class="inline-block mt-4 text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-plus mr-2"></i>Create SKU
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @else
        <div class="px-6 py-12 text-center">
            <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No SKUs found. Create your first SKU to get started!</p>
        </div>
        @endif
    </div>
</div>
   

@endsection