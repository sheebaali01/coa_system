@extends('layouts.admin')

@section('page-title', 'Batch Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    
    <a href="{{ route('admin.skus.add') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Add New Batch
    </a>
</div>
<!-- Batch Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800">All Batches</h3>
    </div>
 
</div>
   

@endsection