<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Renew Peptides - Admin')</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-sage-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg border-r border-sage-200">
            <div class="p-6 border-b border-sage-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-sage-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dna text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-sage-900">Renew Peptides</h1>
                        <p class="text-sage-500 text-xs">Certificate of Analysis</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-4 px-3">
                <div class="mb-6">
                    <p class="px-3 text-xs font-semibold text-sage-500 uppercase tracking-wider mb-2">Main Menu</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg hover:bg-sage-50 transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-sage-400 text-white shadow-md' : 'text-sage-700' }}">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-3 font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.skus.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg hover:bg-sage-50 transition-all {{ request()->routeIs('admin.skus.*') ? 'bg-sage-400 text-white shadow-md' : 'text-sage-700' }}">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-3 font-medium">SKU Management</span>
                    </a>
                    <a href="{{ route('admin.batches.index')}}" class="flex items-center px-4 py-3 mb-1 rounded-lg hover:bg-sage-50 transition-all {{ request()->routeIs('admin.batches.*') ? 'bg-sage-400 text-white shadow-md' : 'text-sage-700' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span class="ml-3 font-medium">Batch Management</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 mb-1 rounded-lg hover:bg-sage-50 transition-all text-sage-700">
                        <i class="fas fa-qrcode w-5"></i>
                        <span class="ml-3 font-medium">Scan Logs</span>
                    </a>
                </div>

                <!-- <div class="mb-6">
                    <p class="px-3 text-xs font-semibold text-sage-500 uppercase tracking-wider mb-2">Analytics</p>
                    <a href="#" class="flex items-center px-4 py-3 mb-1 rounded-lg hover:bg-sage-50 transition-all text-sage-700">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="ml-3 font-medium">Reports</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 mb-1 rounded-lg hover:bg-sage-50 transition-all text-sage-700">
                        <i class="fas fa-download w-5"></i>
                        <span class="ml-3 font-medium">Downloads</span>
                    </a>
                </div> -->
            </nav>

            <div class="absolute bottom-0 w-64 p-4 border-t border-sage-100 bg-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sage-400 to-sage-600 flex items-center justify-center">
                        <span class="text-white font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1">                     
                        <p class="font-medium text-sage-900 text-sm">{{ Auth::user()->name }}</p>
                        <a href="{{ route('logout') }}" class="text-xs text-sage-500 hover:text-sage-700 transition-colors">Logout</a>
                    </div>
                    <!-- <button class="p-2 hover:bg-sage-50 rounded-lg transition-all">
                        <i class="fas fa-ellipsis-v text-sage-500"></i>
                    </button> -->
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-sage-100">
                <div class="px-8 py-5 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-sage-900">@yield('page-title')</h2>
                        <!-- <p class="text-sm text-sage-500 mt-1">@yield('page-subtitle', 'Manage your products and analytics')</p> -->
                    </div>
                    <!-- <div class="flex items-center space-x-3">
                        <button class="p-2.5 hover:bg-sage-50 rounded-lg transition-all relative">
                            <i class="fas fa-bell text-sage-600"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <button class="p-2.5 hover:bg-sage-50 rounded-lg transition-all">
                            <i class="fas fa-search text-sage-600"></i>
                        </button>
                    </div> -->
                </div>
            </header>

            <!-- Flash Messages -->
            @if(Session::has('success'))
            <div class="mx-8 mt-6 p-4 bg-gradient-to-r from-sage-50 to-white border-l-4 border-sage-400 rounded-lg shadow-sm flex items-center">
                <div class="w-10 h-10 bg-sage-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white"></i>
                </div>
                <span class="ml-4 text-sage-800 font-medium">{!! Session::get('success') !!}</span>
                <button class="ml-auto text-sage-500 hover:text-sage-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if(Session::has('error'))
            <div class="mx-8 mt-6 p-4 bg-gradient-to-r from-red-50 to-white border-l-4 border-red-500 rounded-lg shadow-sm flex items-center">
                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation text-white"></i>
                </div>
                <span class="ml-4 text-red-800 font-medium">{!! Session::get('error') !!}</span>
                <button class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>