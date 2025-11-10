@extends('layouts.admin')

@section('title', 'Dashboard - Renew Peptides')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, Sheeba! Here\'s what\'s happening today.')

@section('content')
<!-- Top Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-sage-400 to-sage-500 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-box text-white text-xl"></i>
            </div>
            <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded-full">+12%</span>
        </div>
        <h3 class="text-3xl font-bold text-sage-900 mb-1">1,234</h3>
        <p class="text-sage-500 text-sm font-medium">Total SKUs</p>
        <div class="mt-4 pt-4 border-t border-sage-100">
            <p class="text-xs text-sage-400">Last 30 days</p>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-chart-bar text-white text-xl"></i>
            </div>
            <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded-full">+8%</span>
        </div>
        <h3 class="text-3xl font-bold text-sage-900 mb-1">456</h3>
        <p class="text-sage-500 text-sm font-medium">Active Batches</p>
        <div class="mt-4 pt-4 border-t border-sage-100">
            <p class="text-xs text-sage-400">Last 6 months</p>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-qrcode text-white text-xl"></i>
            </div>
            <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded-full">+24%</span>
        </div>
        <h3 class="text-3xl font-bold text-sage-900 mb-1">8.9K</h3>
        <p class="text-sage-500 text-sm font-medium">QR Scans</p>
        <div class="mt-4 pt-4 border-t border-sage-100">
            <p class="text-xs text-sage-400">This month</p>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-certificate text-white text-xl"></i>
            </div>
            <span class="px-2.5 py-1 bg-red-50 text-red-600 text-xs font-semibold rounded-full">-3%</span>
        </div>
        <h3 class="text-3xl font-bold text-sage-900 mb-1">234</h3>
        <p class="text-sage-500 text-sm font-medium">COA Issued</p>
        <div class="mt-4 pt-4 border-t border-sage-100">
            <p class="text-xs text-sage-400">This week</p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Weekly Overview Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-sage-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-sage-900">Weekly Overview</h3>
                <p class="text-sm text-sage-500 mt-1">Batch production and QR scans</p>
            </div>
            <button class="p-2 hover:bg-sage-50 rounded-lg transition-all">
                <i class="fas fa-ellipsis-v text-sage-400"></i>
            </button>
        </div>
        
        <!-- Chart Placeholder -->
        <div class="h-64 flex items-end justify-between space-x-2">
            <div class="flex-1 bg-gradient-to-t from-sage-400 to-sage-300 rounded-t-lg opacity-60" style="height: 45%"></div>
            <div class="flex-1 bg-gradient-to-t from-sage-400 to-sage-300 rounded-t-lg opacity-70" style="height: 60%"></div>
            <div class="flex-1 bg-gradient-to-t from-sage-400 to-sage-300 rounded-t-lg opacity-50" style="height: 55%"></div>
            <div class="flex-1 bg-gradient-to-t from-sage-400 to-sage-300 rounded-t-lg opacity-80" style="height: 70%"></div>
            <div class="flex-1 bg-gradient-to-t from-sage-400 to-sage-300 rounded-t-lg" style="height: 85%"></div>
            <div class="flex-1 bg-gradient-to-t from-sage-400 to-sage-300 rounded-t-lg opacity-90" style="height: 65%"></div>
            <div class="flex-1 bg-gradient-to-t from-sage-400 to-sage-300 rounded-t-lg opacity-75" style="height: 50%"></div>
        </div>
        <div class="flex justify-between mt-4 text-xs text-sage-500">
            <span>Mon</span>
            <span>Tue</span>
            <span>Wed</span>
            <span>Thu</span>
            <span>Fri</span>
            <span>Sat</span>
            <span>Sun</span>
        </div>
    </div>

    <!-- Product Distribution -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-sage-900">Top Products</h3>
                <p class="text-sm text-sage-500 mt-1">By scan volume</p>
            </div>
            <button class="p-2 hover:bg-sage-50 rounded-lg transition-all">
                <i class="fas fa-ellipsis-v text-sage-400"></i>
            </button>
        </div>

        <!-- Donut Chart Placeholder -->
        <div class="flex items-center justify-center mb-6">
            <div class="relative w-40 h-40">
                <svg class="w-40 h-40 transform -rotate-90">
                    <circle cx="80" cy="80" r="60" fill="none" stroke="#D4DCC8" stroke-width="20"/>
                    <circle cx="80" cy="80" r="60" fill="none" stroke="#8B9B7A" stroke-width="20" 
                            stroke-dasharray="251.2" stroke-dashoffset="62.8"/>
                    <circle cx="80" cy="80" r="60" fill="none" stroke="#6B7A5A" stroke-width="20" 
                            stroke-dasharray="251.2" stroke-dashoffset="188.4"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="text-2xl font-bold text-sage-900">847</span>
                    <span class="text-xs text-sage-500">Total</span>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-sage-400 mr-2"></div>
                    <span class="text-sm text-sage-700">BPC-157</span>
                </div>
                <span class="text-sm font-semibold text-sage-900">45%</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-sage-600 mr-2"></div>
                    <span class="text-sm text-sage-700">TB-500</span>
                </div>
                <span class="text-sm font-semibold text-sage-900">30%</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-sage-200 mr-2"></div>
                    <span class="text-sm text-sage-700">Others</span>
                </div>
                <span class="text-sm font-semibold text-sage-900">25%</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Batch Timeline -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Batches -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-sage-900">Recent Batches</h3>
                <p class="text-sm text-sage-500 mt-1">Latest batch activities</p>
            </div>
            <a href="{{ route('admin.batches.index') }}" class="text-sm text-sage-600 hover:text-sage-700 font-medium">View All</a>
        </div>

        <div class="space-y-4">
            <!-- Batch Item -->
            <div class="flex items-center p-4 rounded-lg border border-sage-100 hover:border-sage-300 transition-all">
                <div class="w-10 h-10 bg-sage-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flask text-sage-600"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-semibold text-sage-900">Batch #2024-001</h4>
                    <p class="text-xs text-sage-500">BPC-157 - 5mg</p>
                </div>
                <div class="text-right">
                    <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-medium rounded-full">Active</span>
                    <p class="text-xs text-sage-400 mt-1">2 hours ago</p>
                </div>
            </div>

            <!-- Batch Item -->
            <div class="flex items-center p-4 rounded-lg border border-sage-100 hover:border-sage-300 transition-all">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flask text-blue-600"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-semibold text-sage-900">Batch #2024-002</h4>
                    <p class="text-xs text-sage-500">TB-500 - 10mg</p>
                </div>
                <div class="text-right">
                    <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-medium rounded-full">Pending</span>
                    <p class="text-xs text-sage-400 mt-1">5 hours ago</p>
                </div>
            </div>

            <!-- Batch Item -->
            <div class="flex items-center p-4 rounded-lg border border-sage-100 hover:border-sage-300 transition-all">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flask text-purple-600"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-semibold text-sage-900">Batch #2024-003</h4>
                    <p class="text-xs text-sage-500">GHK-Cu - 50mg</p>
                </div>
                <div class="text-right">
                    <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-medium rounded-full">Active</span>
                    <p class="text-xs text-sage-400 mt-1">1 day ago</p>
                </div>
            </div>

            <!-- Batch Item -->
            <div class="flex items-center p-4 rounded-lg border border-sage-100 hover:border-sage-300 transition-all">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flask text-red-600"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-semibold text-sage-900">Batch #2024-004</h4>
                    <p class="text-xs text-sage-500">Ipamorelin - 2mg</p>
                </div>
                <div class="text-right">
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">Expired</span>
                    <p class="text-xs text-sage-400 mt-1">3 days ago</p>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scan Activity -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-sage-900">QR Scan Activity</h3>
                <p class="text-sm text-sage-500 mt-1">Recent customer verifications</p>
            </div>
            <button class="p-2 hover:bg-sage-50 rounded-lg transition-all">
                <i class="fas fa-ellipsis-v text-sage-400"></i>
            </button>
        </div>

        <div class="space-y-4">
            <!-- Activity Item -->
            <div class="flex items-start">
                <div class="w-2 h-2 bg-sage-400 rounded-full mt-2 mr-4"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-sage-900">BPC-157 Verified</h4>
                        <span class="text-xs text-sage-400">Just now</span>
                    </div>
                    <p class="text-xs text-sage-500">Batch #2024-001 scanned from New York, USA</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-4"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-sage-900">TB-500 Verified</h4>
                        <span class="text-xs text-sage-400">15 min ago</span>
                    </div>
                    <p class="text-xs text-sage-500">Batch #2024-002 scanned from Los Angeles, USA</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-2 h-2 bg-purple-400 rounded-full mt-2 mr-4"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-sage-900">GHK-Cu Verified</h4>
                        <span class="text-xs text-sage-400">1 hour ago</span>
                    </div>
                    <p class="text-xs text-sage-500">Batch #2024-003 scanned from Chicago, USA</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-2 h-2 bg-amber-400 rounded-full mt-2 mr-4"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-sage-900">BPC-157 Verified</h4>
                        <span class="text-xs text-sage-400">3 hours ago</span>
                    </div>
                    <p class="text-xs text-sage-500">Batch #2024-001 scanned from Miami, USA</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-4"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-sage-900">Ipamorelin Verified</h4>
                        <span class="text-xs text-sage-400">5 hours ago</span>
                    </div>
                    <p class="text-xs text-sage-500">Batch #2024-004 scanned from Seattle, USA</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-4"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-sage-900">Failed Verification</h4>
                        <span class="text-xs text-sage-400">6 hours ago</span>
                    </div>
                    <p class="text-xs text-sage-500">Invalid QR code scanned from Unknown location</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('admin.skus.index') }}" class="bg-gradient-to-br from-sage-400 to-sage-500 rounded-xl shadow-sm p-6 text-white hover:shadow-lg transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus text-2xl"></i>
            </div>
            <i class="fas fa-arrow-right opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Add New SKU</h3>
        <p class="text-sage-100 text-sm">Create a new product SKU</p>
    </a>

    <a href="{{ route('admin.batches.index') }}" class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl shadow-sm p-6 text-white hover:shadow-lg transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-flask text-2xl"></i>
            </div>
            <i class="fas fa-arrow-right opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Create Batch</h3>
        <p class="text-blue-100 text-sm">Start a new production batch</p>
    </a>

    <a href="#" class="bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl shadow-sm p-6 text-white hover:shadow-lg transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-download text-2xl"></i>
            </div>
            <i class="fas fa-arrow-right opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Export Reports</h3>
        <p class="text-purple-100 text-sm">Download analytics and data</p>
    </a>
</div> -->

@endsection