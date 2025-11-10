<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Sku;
use App\Models\Vial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::with('sku');
        if ($request->has('skuId')) {
            $query->where('sku_id', $request->skuId);
        }
        $batches = $query->get();
        return view('admin.batch.index', compact('batches'));
    }

    public function add(Request $request)
    {
        if($request->do_post == 1){
          return $this->_add($request);
        }
        $skus = Sku::all();
        return view('admin.batch.add', compact('skus'));
    }

    public function _add(Request $request)
    {  
        $validator = Validator::make($request->all(),[
            'sku_id' => 'required|exists:skus,id',
            'batch_number' => 'required|string|max:100|unique:batches',
            'total_vials' => 'required|integer|min:1',
            'manufacture_date' => 'required|date',
            'expiry_date' => 'required|date|after:manufacture_date',
            'status' => 'required|in:active,expired,recalled,pending',
            'lot_number' => 'nullable|string|max:255',
            'coa_document' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $fileName = null;
            // Handle file upload
            if ($request->hasFile('coa_document')) {
                $file = $request->file('coa_document');
                $timestamp = now()->timestamp;

                // Generate unique + clean file name
                $originalExt = $file->getClientOriginalExtension();
                $fileName = uniqid() . "_batch_" . $request->batch_number . "_{$timestamp}." . $originalExt;

                // Store in storage/app/public/coa_documents/{batch_number}/
                $path = $file->storeAs("coa_documents/{$request->batch_number}", $fileName, 'public');
            }

            // Check current batch count for this SKU
            $currentBatchCount = Batch::where('sku_id', $request->sku_id)->count();

            $deletedBatch = null;
            // If already at or over limit (5), delete the oldest batch
            if ($currentBatchCount >= 5) {
                $deletedBatch = $this->deleteOldestBatchForSku($request->sku_id);
            }

            $batch = new Batch();
            $batch->sku_id = $request->sku_id;
            $batch->batch_number = $request->batch_number;
            $batch->total_vials = $request->total_vials;
            $batch->manufacture_date = $request->manufacture_date;
            $batch->expiry_date = $request->expiry_date;
            $batch->status = $request->status;
            $batch->lot_number = $request->lot_number;
            $batch->coa_document = $fileName;
            $batch->save();

            $message = 'Batch added successfully!';
            if ($deletedBatch) {
                $message .= " Oldest batch '{$deletedBatch->batch_number}' was automatically removed to maintain the 5-batch limit.";
            }

            Session::flash('success', $message);
            // Session::flash('success', 'Batch added successfully!');
            return redirect()->route('admin.batches.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Error adding batch: ' . $e->getMessage());
            return redirect()->back()->withInput();

        }
    } 
    public function update(Request $request, $id)
    {
        if($request->do_post == 1){
          return $this->_update($request, $id);
        }
        $batch = Batch::find($id);
        $skus = Sku::all();
        return view('admin.batch.update' , compact(['skus','batch']));
    }

    public function _update(Request $request, $id)
    {  
        $validator = Validator::make($request->all(),[
            'sku_id' => 'required|exists:skus,id',
            'batch_number' => 'required|string|max:100|unique:batches,batch_number,' . $id,
            'total_vials' => 'required|integer|min:1',
            'manufacture_date' => 'required|date',
            'expiry_date' => 'required|date|after:manufacture_date',
            'status' => 'required|in:active,expired,recalled,pending',
            'lot_number' => 'nullable|string|max:255',
            'coa_document' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $batch = Batch::with('vials')->findOrFail($id);
            
            $fileName = $batch->coa_document;
            
            // Handle file upload
            if ($request->hasFile('coa_document')) {
                // Delete old COA document if exists
                if ($batch->coa_document) {
                    $this->deleteCoaDocument($batch->batch_number, $batch->coa_document);
                }
                
                $file = $request->file('coa_document');
                $timestamp = now()->timestamp;
                $originalExt = $file->getClientOriginalExtension();
                $fileName = uniqid() . "_batch_" . $request->batch_number . "_{$timestamp}." . $originalExt;
                $path = $file->storeAs("coa_documents/{$request->batch_number}", $fileName, 'public');
            }

            $oldVialCount = $batch->total_vials;
            $newVialCount = $request->total_vials;
            
            // Handle vial count changes
            if ($newVialCount != $oldVialCount) {
                $this->handleVialCountChange($batch, $oldVialCount, $newVialCount);
            }

            // Update batch
            $batch->sku_id = $request->sku_id;
            $batch->batch_number = $request->batch_number;
            $batch->total_vials = $request->total_vials;
            $batch->manufacture_date = $request->manufacture_date;
            $batch->expiry_date = $request->expiry_date;
            $batch->status = $request->status;
            $batch->lot_number = $request->lot_number;
            $batch->coa_document = $fileName;
            $batch->save();

            Session::flash('success', 'Batch updated successfully!');
            return redirect()->route('admin.batches.index');
            
        } catch (\Exception $e) {
            Session::flash('error', 'Error updating batch: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    /**
 * Handle when vial count is increased or decreased
 */
private function handleVialCountChange(Batch $batch, $oldCount, $newCount)
{
    
    if ($newCount > $oldCount) {
        // Increase vial count - generate additional vials
        $this->increaseVialCount($batch, $oldCount, $newCount);
    } else {
        // Decrease vial count - remove excess vials
        $this->decreaseVialCount($batch, $oldCount, $newCount);
    }
}

/**
 * Generate additional vials when count increases
 */
private function increaseVialCount(Batch $batch, $oldCount, $newCount)
{
    $additionalVials = $newCount - $oldCount;
    \Log::info("Generating {$additionalVials} additional vials for batch {$batch->batch_number}");
    
    $vialService = app(\App\Services\VialService::class);
    
    for ($i = $oldCount + 1; $i <= $newCount; $i++) {
        $code = $batch->batch_number . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
        
        $path = "qr_codes/{$batch->batch_number}/{$code}.svg";
        $url = route('vial.instant-redirect', ['vial' => $code]);
        
        // Generate QR code
        $qr = QrCode::size(300)->generate($url);
        Storage::disk('public')->put($path, $qr);

        // Create vial record
        Vial::create([
            'batch_id' => $batch->id,
            'vial_number' => "VIAL #{$i}",
            'qr_code_image' => $path,
            'unique_code' => $code,
            'is_scanned' => false,
            'scan_count' => 0,
        ]);
    }
    
    // Session::flash('info', "Generated {$additionalVials} additional vials for this batch.");
}

    private function decreaseVialCount(Batch $batch, $oldCount, $newCount)
    {
        $vialsToRemove = $oldCount - $newCount;
        \Log::info("Removing {$vialsToRemove} vials from batch {$batch->batch_number}");
        
        // Get the highest numbered vials to remove (most recently created)
        $vialsToDelete = $batch->vials()
            ->orderBy('id', 'desc')
            ->limit($vialsToRemove)
            ->get();

        $removedCount = 0;
        
        foreach ($vialsToDelete as $vial) {
            \Log::info("Removing vial: {$vial->unique_code}");
            
            // Delete QR code file
            if ($vial->qr_code_image && Storage::disk('public')->exists($vial->qr_code_image)) {
                Storage::disk('public')->delete($vial->qr_code_image);
                \Log::info("Deleted QR code: {$vial->qr_code_image}");
            }
            
            // Delete vial record
            $vial->delete();
            $removedCount++;
        }
        
        if ($removedCount > 0) {
            Session::flash('warning', "Removed {$removedCount} vials from this batch. Any scanned vials were preserved.");
        }
    }

    public function view($id)
    {
        $batch = Batch::findOrFail($id);
        return view('admin.batch.view', compact('batch'));
    }
    private function deleteOldestBatchForSku($skuId)
    {
        $oldestBatch = Batch::where('sku_id', $skuId)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($oldestBatch) {
            // Store batch info for message
            $deletedBatchInfo = $oldestBatch->replicate();
            
            // Delete associated vials first (if you have this relationship)
            // if (method_exists($oldestBatch, 'vials')) {
            //     $oldestBatch->vials()->delete();
            // }  //old

            // Delete associated vials and their QR codes
            $this->deleteBatchVials($oldestBatch);
            // Delete COA document if exists
            if ($oldestBatch->coa_document) {
                $this->deleteCoaDocument($oldestBatch->batch_number, $oldestBatch->coa_document);
            }

            // Delete the batch record
            $oldestBatch->delete();

            return $deletedBatchInfo;
        }

        return null;
    }
private function deleteCoaDocument($batchNumber, $fileName)
{
    $filePath = "coa_documents/{$batchNumber}/{$fileName}";
    
    // Delete the file
    if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($filePath);
    }
    
    // Delete the directory if empty
    $directory = "coa_documents/{$batchNumber}";
    if (Storage::disk('public')->exists($directory)) {
        $filesInDirectory = Storage::disk('public')->files($directory);
        if (!empty($filesInDirectory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }
    }
}
private function deleteBatchVials(Batch $batch)
{
    if (!$batch->vials || $batch->vials->isEmpty()) {
        \Log::info("No vials found for batch: {$batch->batch_number}");
        return;
    }

    foreach ($batch->vials as $vial) {
        // Delete QR code file if exists
        if ($vial->qr_code_image) {
            $this->deleteVialQrCode($vial->qr_code_image);
        }
        
        // Delete the vial record
        $vial->delete();
    }
}
private function deleteVialQrCode($qrCodePath)
{
    try {       
        if (Storage::disk('public')->exists($qrCodePath)) {
            Storage::disk('public')->delete($qrCodePath);
        } 
        // Try to delete the directory 
        $directory = dirname($qrCodePath);
        if (Storage::disk('public')->exists($directory)) {
            $files = Storage::disk('public')->files($directory);
            if (count($files) > 0) {
                Storage::disk('public')->deleteDirectory($directory);
                \Log::info("Deleted empty directory: {$directory}");
            }
        }
    } catch (\Exception $e) {
        \Log::error("Error deleting QR code {$qrCodePath}: " . $e->getMessage());
    }
}
}
