<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard') | Renew Peptides</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-sage-50 font-sans flex flex-col">

    <!-- Navbar -->
    <header class="bg-white border-b border-sage-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-sage-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dna text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-sage-900">Renew Peptides</h1>
            </div>

            <nav class="flex items-center space-x-6">
                <a href="{{ route('user.dashboard') }}" class="text-sage-700 hover:text-sage-900 font-medium {{ request()->routeIs('user.dashboard') ? 'text-sage-900 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="#" class="text-sage-700 hover:text-sage-900 font-medium {{ request()->routeIs('user.profile') ? 'text-sage-900 font-semibold' : '' }}">
                    Profile
                </a>
                <a href="{{ route('logout') }}" class="text-sage-600 hover:text-sage-800 font-medium flex items-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-4xl mx-auto mt-6 w-full px-4">
        @if(Session::has('success'))
            <div class="p-4 bg-gradient-to-r from-sage-50 to-white border-l-4 border-sage-400 rounded-lg shadow-sm flex items-center">
                <div class="w-8 h-8 bg-sage-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white"></i>
                </div>
                <span class="ml-3 text-sage-800 font-medium">{!! Session::get('success') !!}</span>
            </div>
        @endif

        @if(Session::has('error'))
            <div class="p-4 bg-gradient-to-r from-red-50 to-white border-l-4 border-red-500 rounded-lg shadow-sm flex items-center">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation text-white"></i>
                </div>
                <span class="ml-3 text-red-800 font-medium">{!! Session::get('error') !!}</span>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-6 py-8 w-full">
        <h2 class="text-2xl font-bold text-sage-900 mb-6">@yield('page-title', 'User Dashboard')</h2>
        @yield('content')
    </main>

</body>
</html>
