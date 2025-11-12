@extends('layouts.admin')

@section('page-title', 'Vial Management')

@section('content')
<div class="mb-6 flex justify-end gap-1">
    <div class="flex items-center space-x-3">
        <!-- <a href="{{ route('admin.vials.export.excel') }}" 
            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 flex items-center">
            <i class="fas fa-file-excel mr-2"></i>
            Export Excel
        </a> -->
        <a href="{{ route('admin.vials.export.pdf', $batchId) }}" 
            class="bg-sage-600 text-white px-6 py-3 rounded-lg hover:bg-sage-700 flex items-center">
            <i class="fas fa-file-pdf mr-2"></i>
            Export PDF
        </a>
    </div>
    
    <!-- Reset All QR Codes Button -->
    <button onclick="openResetAllModal()" 
        class="bg-sage-600 text-white px-6 py-3 rounded-lg hover:bg-sage-700 flex items-center">
        <i class="fas fa-sync-alt mr-2"></i>
        Reset All QR Codes
    </button>
</div>

<!-- Vial Table -->
<div class="bg-white rounded-xl shadow-sm border border-sage-100 overflow-hidden">
    <div class="p-6 border-b border-sage-100">
        <h3 class="text-lg font-semibold text-sage-800">All Vials</h3>
    </div>

    <div class="overflow-x-auto">
        @if($vials->count() > 0)
        <table class="w-full">
            <thead class="bg-sage-50 border-b border-sage-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-sage-500 uppercase tracking-wider">SKU Code</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-sage-500 uppercase tracking-wider">Batch Number</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-sage-500 uppercase tracking-wider">Vial Number</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-sage-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-sage-500 uppercase tracking-wider">Scan At</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-sage-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sage-100">
                @forelse($vials as $vial)
                <tr class="hover:bg-sage-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono font-semibold text-sage-600">{{ $vial->batch->sku->sku_code }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-sage-100 text-sage-700">
                            {{ $vial->batch->batch_number }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-sage-900">
                        {{ $vial->vial_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                            {{ $vial->first_scan_at->format('M d, Y H:i') }}
                        @else
                            <span class="text-sage-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-3">
                            <button onclick='openQrModal(@json($vial))'  
                                class="text-sage-600 hover:text-sage-900 transition-colors"
                                title="View QR Code">
                                <i class="fas fa-qrcode text-xl"></i>
                            </button>
                            
                            @if($vial->is_scanned)
                            <button onclick="openResetModal({{ $vial->id }})" 
                                class="text-sage-600 hover:text-sage-900 transition-colors"
                                title="Reset QR Code">
                                <i class="fas fa-sync-alt text-lg"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i class="fas fa-box-open text-sage-300 text-5xl mb-4"></i>
                        <p class="text-sage-500">No vials found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @else
        <div class="px-6 py-12 text-center">
            <i class="fas fa-box-open text-sage-300 text-5xl mb-4"></i>
            <p class="text-sage-500">No vials found.</p>
        </div>
        @endif
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="hidden fixed inset-0 bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <!-- Modal Header -->
        <div class="p-6 border-b border-sage-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-qrcode text-sage-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-sage-800">QR Code</h3>
                </div>
                <button onclick="closeQrModal()" class="text-sage-400 hover:text-sage-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="bg-sage-50 rounded-lg p-4 mb-4">
                <p class="text-sm text-sage-500 mb-1">Vial Number</p>
                <p class="font-semibold text-sage-800" id="modalVialNumber"></p>
            </div>

            <!-- QR Code Image -->
            <div class="flex justify-center items-center bg-white border-2 border-sage-200 rounded-lg p-6 mb-4">
                <img id="qrCodeImage" src="" alt="QR Code" class="max-w-full h-auto" style="max-height: 300px;">
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-sage-100 bg-sage-50 rounded-b-2xl">
            <div class="flex justify-end space-x-3">
                <button onclick="closeQrModal()" 
                    class="px-6 py-2.5 border border-sage-300 text-sage-700 rounded-lg hover:bg-sage-100 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
                <button onclick="downloadQrCode()" 
                    class="px-6 py-2.5 bg-sage-600 text-white rounded-lg hover:bg-sage-700 transition-colors font-medium">
                    <i class="fas fa-download mr-2"></i>Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Single QR Code Modal -->
<div id="resetModal" class="hidden fixed inset-0  bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <!-- Modal Header -->
        <div class="p-6 border-b border-sage-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-sage-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-sync-alt text-sage-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-sage-800">Reset QR Code</h3>
                </div>
                <button onclick="closeResetModal()" class="text-sage-400 hover:text-sage-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <p class="text-sage-600 mb-4">Are you sure you want to reset the QR code for this vial?</p>
            
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-sage-100 bg-sage-50 rounded-b-2xl">
            <div class="flex justify-end space-x-3">
                <button onclick="closeResetModal()" 
                    class="px-6 py-2.5 border border-sage-300 text-sage-700 rounded-lg hover:bg-sage-100 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button onclick="confirmResetQr()" 
                    class="px-6 py-2.5 bg-sage-600 text-white rounded-lg hover:bg-sage-700 transition-colors font-medium">
                    <i class="fas fa-sync-alt mr-2"></i>Reset QR Code
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reset All QR Codes Modal -->
<div id="resetAllModal" class="hidden fixed inset-0  bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <!-- Modal Header -->
        <div class="p-6 border-b border-sage-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-sage-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-sage-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Reset All QR Codes</h3>
                </div>
                <button onclick="closeResetAllModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <p class="text-sage-600 mb-4">Are you sure you want to reset ALL QR codes?</p>

            <div class="bg-sage-50 border border-sage-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-sage-600 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm font-semibold text-sage-800 mb-1">Warning</p>
                        <p class="text-sm text-sage-700">This will reset the scan status and first scan timestamp for ALL vials. This action cannot be undone.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-sage-100 bg-sage-50 rounded-b-2xl">
            <div class="flex justify-end space-x-3">
                <button onclick="closeResetAllModal()" 
                    class="px-6 py-2.5 border border-sage-300 text-sage-700 rounded-lg hover:bg-sage-100 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button onclick="confirmResetAllQr()" 
                    class="px-6 py-2.5 bg-sage-600 text-white rounded-lg hover:bg-sage-700 transition-colors font-medium">
                    <i class="fas fa-sync-alt mr-2"></i>Reset All
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentQrPath = null;
    let currentVialNumber = null;
    let vialUniqueCode = null;
    let currentVialId = null;
    let batchId = {{ $batchId }};

    // QR Modal Functions
    function openQrModal(vial) {
        console.log(vial.qr_code_image);
        currentVialNumber = vial.vial_number;
        currentQrPath = vial.qr_code_image;
        vialUniqueCode = vial.unique_code;
        
        document.getElementById('modalVialNumber').textContent = currentVialNumber;
        document.getElementById('qrCodeImage').src = '/storage/' + currentQrPath;
        
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

    function downloadQrCode() {
        if (!currentQrPath) return;
    
        const fileUrl = '/storage/' + currentQrPath;
        const extension = currentQrPath.split('.').pop().toLowerCase();
        
        const link = document.createElement('a');
        link.href = fileUrl;
        link.download = `vial_${vialUniqueCode}_qr_code.${extension}`;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Reset Single QR Modal Functions
    function openResetModal(vialId) {
        currentVialId = vialId;
        // document.getElementById('resetVialNumber').textContent = vialNumber;
        
        const modal = document.getElementById('resetModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
        }, 10);
    }

    function closeResetModal() {
        const modal = document.getElementById('resetModal');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentVialId = null;
        }, 300);
    }

    function confirmResetQr() {
        if (!currentVialId) return;
        
        fetch(`/admin/vials/reset/${currentVialId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeResetModal();
                location.reload();
            } else {
                alert('Failed to reset QR code: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while resetting the QR code.');
        });
    }

    // Reset All QR Modal Functions
    function openResetAllModal() {
        const modal = document.getElementById('resetAllModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
        }, 10);
    }

    function closeResetAllModal() {
        const modal = document.getElementById('resetAllModal');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function confirmResetAllQr() {
        fetch('/admin/vials/resetAll/'+batchId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeResetAllModal();
                // alert(`Successfully reset ${data.count} QR code(s)!`);
                location.reload();
            } else {
                alert('Failed to reset QR codes: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while resetting QR codes.');
        });
    }

    // Close modals on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeQrModal();
            closeResetModal();
            closeResetAllModal();
        }
    });

    // Close modals when clicking outside
    document.getElementById('qrModal').addEventListener('click', function(event) {
        if (event.target === this) closeQrModal();
    });

    document.getElementById('resetModal').addEventListener('click', function(event) {
        if (event.target === this) closeResetModal();
    });

    document.getElementById('resetAllModal').addEventListener('click', function(event) {
        if (event.target === this) closeResetAllModal();
    });
</script>

@endsection