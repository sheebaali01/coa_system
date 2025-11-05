<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'COA System - Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-indigo-900 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">COA System</h1>
                <p class="text-indigo-300 text-sm">Certificate of Analysis</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.skus.index') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('admin.skus.*') ? 'bg-indigo-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-box mr-3"></i>
                    <span>SKU Management</span>
                </a>
                <a href="{{ route('admin.batches.index')}}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('admin.batches.*') ? 'bg-indigo-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Batch Management</span>
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-indigo-800">
                    <i class="fas fa-qrcode mr-3"></i>
                    <span>Scan Logs</span>
                </a>
                <!-- <a href="#" class="flex items-center px-6 py-3 hover:bg-indigo-800">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Settings</span>
                </a> -->
            </nav>

            <div class="absolute bottom-0 w-64 p-6 border-t border-indigo-800">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-indigo-700 flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ml-3">                     
                        <p class="font-medium">Sheeba Ali</p>
                        <button class="text-sm text-indigo-300 hover:text-white">Logout</button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-8 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title')</h2>
                    <!-- <div class="flex items-center space-x-4">
                        <button class="p-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-bell text-gray-600"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-search text-gray-600"></i>
                        </button>
                    </div> -->
                </div>
            </header>

            <!-- Flash Messages -->
            @if(Session::has('success'))
            <div class="mx-8 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <span class="text-green-800">{!! Session::get('success') !!}</span>
            </div>
    
            @endif

            @if(Session::has('error'))
            <div class="mx-8 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                <span class="text-red-800">{!! Session::get('error') !!}</span>
            </div>
            @endif

            <!-- @if($errors->any())
            <div class="mx-8 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <span class="text-red-800 font-medium">Please fix the following errors:</span>
                </div>
                <ul class="ml-8 text-red-700 text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif -->

            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>