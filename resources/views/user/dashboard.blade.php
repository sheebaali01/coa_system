@extends('layouts.user')

@section('title', 'Dashboard')
@section('page-title', 'Welcome, ' . Auth::user()->name . '!')

@section('content')
<div class="bg-white rounded-xl border border-sage-100 shadow p-8">
    <h3 class="text-sage-800 font-semibold mb-4">Your Account Overview</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="p-5 bg-sage-50 border border-sage-100 rounded-lg shadow-sm hover:bg-sage-100 transition-all">
            <p class="text-sage-700 font-medium">Email</p>
            <p class="text-sage-900 font-bold mt-1">{{ Auth::user()->email }}</p>
        </div>

        <div class="p-5 bg-sage-50 border border-sage-100 rounded-lg shadow-sm hover:bg-sage-100 transition-all">
            <p class="text-sage-700 font-medium">Member Since</p>
            <p class="text-sage-900 font-bold mt-1">{{ Auth::user()->created_at->format('M d, Y') }}</p>
        </div>

        <div class="p-5 bg-sage-50 border border-sage-100 rounded-lg shadow-sm hover:bg-sage-100 transition-all">
            <p class="text-sage-700 font-medium">Role</p>
            <p class="text-sage-900 font-bold mt-1 capitalize">{{ Auth::user()->role }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h4 class="text-sage-800 font-semibold mb-3">Recent Activity</h4>
        <ul class="divide-y divide-sage-100">
            <li class="py-3 text-sage-700 flex justify-between">
                <span>Logged in</span>
                <span class="text-sage-400 text-sm">{{ now()->format('h:i A') }}</span>
            </li>
            <li class="py-3 text-sage-700 flex justify-between">
                <span>Viewed dashboard</span>
                <span class="text-sage-400 text-sm">{{ now()->format('h:i A') }}</span>
            </li>
        </ul>
    </div>
</div>
@endsection
