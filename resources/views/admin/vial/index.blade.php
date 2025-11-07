@extends('layouts.admin')

@section('page-title', 'Vial Management')

@section('content')
<div class="mb-6 flex items-end">
    <a href="{{ route('admin.vials.export.excel') }}" 
        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 flex items-center">
        <i class="fas fa-file-excel mr-2"></i>
        Export Excel
    </a>
    <a href="{{ route('admin.vials.export.pdf') }}" 
        class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 flex items-center">
        <i class="fas fa-file-pdf mr-2"></i>
        Export PDF
    </a>
</div>

<!-- Vial Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800">All Vials</h3>
    </div>

    <div class="overflow-x-auto">
        @if($vials->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Code</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Number</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vial Number</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($vials as $vial)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono font-semibold text-indigo-600">{{ $vial->batch->sku->sku_code }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                            {{ $vial->batch->batch_number }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $vial->vial_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick='openQrModal(@json($vial))'  
                            class="text-indigo-600 hover:text-indigo-900 transition-colors">
                            <i class="fas fa-qrcode text-xl"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">No vials found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @else
        <div class="px-6 py-12 text-center">
            <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No vials found.</p>
        </div>
        @endif
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-qrcode text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">QR Code</h3>
                </div>
                <button onclick="closeQrModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-500 mb-1">Vial Number</p>
                <p class="font-semibold text-gray-800" id="modalVialNumber"></p>
            </div>

            <!-- QR Code Image -->
            <div class="flex justify-center items-center bg-white border-2 border-gray-200 rounded-lg p-6 mb-4">
                <img id="qrCodeImage" src="" alt="QR Code" class="max-w-full h-auto" style="max-height: 300px;">
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <div class="flex justify-end space-x-3">
                <button onclick="closeQrModal()" 
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
                <button onclick="downloadQrCode()" 
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    <i class="fas fa-download mr-2"></i>Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
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
    let currentQrPath = null;
    let currentVialNumber = null;
    let vialUniqueCode = null;

    // QR Modal Functions
    function openQrModal(vial) {
        console.log(vial.qr_code_image);
        currentVialNumber = vial.vial_number;
        currentQrPath = vial.qr_code_image;
        vialUniqueCode = vial.unique_code;
        
        // Update modal content
        document.getElementById('modalVialNumber').textContent = currentVialNumber;
        // Assuming QR codes are stored in storage/qr_codes/ or similar
        document.getElementById('qrCodeImage').src = '/storage/' + currentQrPath;
        
        // Show modal with animation
        const modal = document.getElementById('qrModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
        }, 10);
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentQrPath = null;
            currentVialNumber = null;
        }, 300);
    }

    // function downloadQrCode() {
    //     if (!currentQrPath) return;
        
    //     const svgUrl = '/storage/' + currentQrPath;
        
    //     // Create an image element to load the SVG
    //     const img = new Image();
    //     img.crossOrigin = 'anonymous';
        
    //     img.onload = function() {
    //         // Create canvas
    //         const canvas = document.createElement('canvas');
    //         canvas.width = img.width || 512;
    //         canvas.height = img.height || 512;
            
    //         const ctx = canvas.getContext('2d');
            
    //         // Fill white background
    //         ctx.fillStyle = 'white';
    //         ctx.fillRect(0, 0, canvas.width, canvas.height);
            
    //         // Draw the image
    //         ctx.drawImage(img, 0, 0);
            
    //         // Convert to PNG and download
    //         canvas.toBlob(function(blob) {
    //             const url = URL.createObjectURL(blob);
    //             const link = document.createElement('a');
    //             link.href = url;
    //             link.download = `vial_${currentVialNumber}_qr_code.png`;
    //             document.body.appendChild(link);
    //             link.click();
    //             document.body.removeChild(link);
    //             URL.revokeObjectURL(url);
    //         }, 'image/png');
    //     };
        
    //     img.onerror = function() {
    //         alert('Failed to load QR code image. Please try again.');
    //     };
        
    //     img.src = svgUrl;
    // }
    function downloadQrCode() {
        if (!currentQrPath) return;
    
        const fileUrl = '/storage/' + currentQrPath;
        
        // Get file extension
        const extension = currentQrPath.split('.').pop().toLowerCase();
        
        // Create download link
        const link = document.createElement('a');
        link.href = fileUrl;
        link.download = `vial_${vialUniqueCode}_qr_code.${extension}`;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    // Delete Modal Functions
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

    // Close modals on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
            closeQrModal();
        }
    });

    // Close QR modal when clicking outside
    document.getElementById('qrModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeQrModal();
        }
    });

    // Close delete modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });
</script>

@endsection