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
                            <button onclick="openDeleteModal({{ $sku->id }})" 
                                class="text-red-600 hover:text-red-900" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
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
<!-- confirm delete modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Confirm Delete</h3>
                </div>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <p class="text-gray-600 mb-4">Are you sure you want to delete this SKU?</p>
            
            <!-- <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-box text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-500">SKU Code</p>
                        <p class="font-mono font-semibold text-gray-800" id="modalSkuCode"></p>
                    </div>
                </div>
                <div class="flex items-start mt-3">
                    <i class="fas fa-tag text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-500">Product Name</p>
                        <p class="font-medium text-gray-800" id="modalProductName"></p>
                    </div>
                </div>
            </div> -->

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-red-600 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-800 mb-1">Warning</p>
                        <p class="text-sm text-red-700">This action cannot be undone. All associated batches and vials will also be deleted.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" 
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" action="" class="inline">
                    @csrf
                    <button type="submit" 
                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i class="fas fa-trash-alt mr-2"></i>Delete SKU
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    let currentSkuId = null;

    function openDeleteModal(skuId) {
        currentSkuId = skuId;
        

        
        // Update form action
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/skus/delete/${skuId}`;
        
        // Show modal with animation
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentSkuId = null;
        }, 300);
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });
</script>   

@endsection