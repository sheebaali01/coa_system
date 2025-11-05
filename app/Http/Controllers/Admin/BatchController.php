<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return view('admin.batch.add');
    }

    public function _add()
    {  
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
