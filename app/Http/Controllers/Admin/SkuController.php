<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Sku;
class SkuController extends Controller
{
    public function index()
    {
        
        $skus = Sku::withCount('batches')->get();
        return view('admin.sku.index', compact('skus'));
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
}
