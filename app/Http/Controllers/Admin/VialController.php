<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Vial;
use App\Exports\VialsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class VialController extends Controller
{
    public function index(Request $request,$batchId)
    {
        $vials = Vial::with('batch.sku')
        ->where('batch_id', $batchId)
        ->get();
        return view('admin.vial.index', compact(['vials','batchId']));
    }

    public function add(Request $request)
    {
        if($request->do_post == 1){
          return $this->_add($request);
        }
        return view('admin.sku.add');
    }
    public function _add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_code' => 'required|string|max:50|unique:skus,sku_code',
            'product_name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)   
                ->withInput();             
        }

        $sku = new Sku();
        $sku->sku_code = $request->sku_code;
        $sku->product_name = $request->product_name;
        $sku->description = $request->description;
        $sku->category = $request->category;
        $sku->unit_price = $request->unit_price;
        $sku->unit_measure = $request->unit_measure;
        // $sku->manufacturer = $request->manufacturer;
        // $sku->product_image = $request->product_image;
        // $sku->is_active = $request->is_active;
        // $sku->metadata = $request->metadata;
        $sku->save();
        Session::flash('success', 'SKU added successfully!');
        return redirect()->route('admin.skus.index');
    }
    public function update(Request $request, $id)
    {
        if($request->do_post == 1){
            return $this->_update($request);
        }
        $sku = Sku::find($id);
        return view('admin.sku.update' , compact('sku'));
    }
    public function _update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_code' => 'required|string|max:50|unique:skus,sku_code,' . $request->id,
            'product_name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)   
                ->withInput();             
        }
        $sku = Sku::find($request->id);
        $sku->sku_code = $request->sku_code;
        $sku->product_name = $request->product_name;
        $sku->description = $request->description;
        $sku->category = $request->category;
        $sku->unit_price = $request->unit_price;
        $sku->unit_measure = $request->unit_measure;
        $sku->save();

        Session::flash('success', 'SKU updated successfully!');
        return redirect()->route('admin.skus.index');
    }
    public function view($id)
    {
        $sku = Sku::withCount('batches')->findOrFail($id);
        return view('admin.sku.view', compact('sku'));
    }

    public function delete($id)
    {
        $sku = Sku::find($id);
        $sku->delete();
        Session::flash('success', 'SKU deleted successfully!');
        return redirect()->route('admin.skus.index');
    }

    public function exportExcel()
    {
        $fileName = 'vials_' . now()->format('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new VialsExport(), $fileName);
    }
    public function exportPdf()
    {
        $vials = Vial::with(['batch.sku'])->get();
        
        $pdf = Pdf::loadView('admin.vial.pdf', compact('vials'))
                  ->setPaper('a4', 'landscape');
        
        $fileName = 'vials_' . now()->format('Y-m-d_His') . '.pdf';
        
        return $pdf->download($fileName);
    }

    public function scanVial($code, Request $request)
    {
        $vial = Vial::where('unique_code', $code)->first();

        if (!$vial) {
            return view('vials.scan_result', [
                'status' => 'error',
                'message' => 'Vial not found!',
                'vial' => null,
            ]);
        }

        $result = $vial->scan(
            $request->ip(),
            $request->userAgent(),
            ['referrer' => $request->headers->get('referer')],
            auth()->id()
        );       
    }

    public function resetAllQrCodes($batchId)
    {
        try {
            DB::beginTransaction();
            // Reset all vials
            Vial::where('is_scanned', 1)->where('batch_id',$batchId)->update([
                'is_scanned' => 0,
                'first_scan_at' => null
            ]);
            
            DB::commit();
                      
           Session::flash('success', 'Reset successful!');
            return response()->json([
                'success' => true,
                'redirect' => true
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Session::flash('error', 'Something went wrong!');
            return response()->json([
                'success' => true,
                'redirect' => true
            ]);
        }
    }

    public function resetQrCode($vialId)
    {
        try {       
            Vial::where('batch_id',$vialId)->update([
                'is_scanned' => 0,
                'first_scan_at' => null
            ]);
                      
            Session::flash('success', 'Reset successful!');
            return response()->json([
                'success' => true,
                'redirect' => true
            ]);
            
        } catch (\Exception $e) {
            
            Session::flash('error', 'Something went wrong!');
            return response()->json([
                'success' => true,
                'redirect' => true
            ]);
        }
    }

    public function instantRedirect($vialId)
    {
        // 1. Find the vial
        $vial = Vial::with('batch')->where('unique_code', $vialId)->first();

        if (!$vial) {
            // If vial not found, redirect to error page
            return redirect()->away('https://instantpeptides.com/error?message=Invalid+vial+code');
        }

        // 2. Record the scan immediately
        $vial->increment('scan_count');
        
        if (!$vial->is_scanned) {
            $vial->update([
                'is_scanned' => true,
                'scanned_at' => now(),
            ]);
        }

        // 3. Build the external URL
        $externalUrl = 'https://instantpeptides.com/batch-lookup?' . http_build_query([
            'vial' => $vial->vial_id,
            'batch' => $vial->batch->batch_id,
        ]);

        // 4. INSTANT REDIRECT - no HTML, no page shown
        return redirect()->away($externalUrl, 302);
    }
}
