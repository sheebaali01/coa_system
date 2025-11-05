<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Sku;
use Illuminate\Support\Facades\Session;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::all();
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

    public function _add()
    {  
        $validator = Validator::make($request->all(),[
            'sku_id' => 'required|exists:skus,id',
            'batch_number' => 'required|string|max:100|unique:batches',
            'total_vials' => 'required|integer|min:1',
            'manufacture_date' => 'required|date',
            'expiry_date' => 'required|date|after:manufacture_date',
            'status' => 'required|in:active,expired,recalled,pending',
            // 'lot_number' => 'nullable|string|max:255',
            // 'coa_document' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('coa_document')) {
            $validated['coa_document'] = $request->file('coa_document')
                ->store('coa_documents', 'public');
        }

        $batch = new Batch();
        $batch->sku_id = $request->sku_id;
        $batch->batch_number = $request->batch_number;
        $batch->total_vials = $request->total_vials;
        $batch->manufacture_date = $request->manufacture_date;
        $batch->expiry_date = $request->expiry_date;
        $batch->status = $request->status;
        // $batch->lot_number = $request->lot_number;
        // $batch->coa_document = $request->coa_document;
        $batch->save();
        Session::flash('success', 'Batch added successfully!');
        return redirect()->route('admin.batches.index');
    } 
    public function update(Request $request, $id)
    {

        if($request->do_post == 1){
          return $this->_update($request);
        }
        $batch = Batch::find($id);
        return view('admin.batch.update' , compact('batch'));
    }
    public function _update()
    {

        Session::flash('success', 'Batch updated successfully!');
        return redirect()->route('admin.batches.index');
    }


    public function view($id)
    {
        $batch = Batch::findOrFail($id);
        return view('admin.batch.view', compact('batch'));
    }
}
