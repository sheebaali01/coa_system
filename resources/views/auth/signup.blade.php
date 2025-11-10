<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Renew Peptides</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-sage-50 flex items-center justify-center font-sans">
    <div class="bg-white shadow-lg rounded-2xl border border-sage-200 w-full max-w-md p-8">
        <!-- Logo / Header -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 bg-sage-400 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-dna text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-sage-900">Renew Peptides</h1>
            <p class="text-sage-500 text-sm">Create your account</p>
        </div>

        <!-- Signup Form -->
        <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sage-700 font-medium mb-1">Full Name</label>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    required 
                    class="w-full px-4 py-2.5 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sage-400 focus:border-sage-400 text-sage-900 placeholder-sage-400"
                    placeholder="John Doe"
                >
            </div>

            <div>
                <label for="email" class="block text-sage-700 font-medium mb-1">Email</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    required 
                    class="w-full px-4 py-2.5 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sage-400 focus:border-sage-400 text-sage-900 placeholder-sage-400"
                    placeholder="you@example.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sage-700 font-medium mb-1">Password</label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    class="w-full px-4 py-2.5 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sage-400 focus:border-sage-400 text-sage-900 placeholder-sage-400"
                    placeholder="••••••••"
                >
            </div>

            <div>
                <label for="password_confirmation" class="block text-sage-700 font-medium mb-1">Confirm Password</label>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    class="w-full px-4 py-2.5 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sage-400 focus:border-sage-400 text-sage-900 placeholder-sage-400"
                    placeholder="••••••••"
                >
            </div>

            @if($errors->any())
                <div class="p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <button 
                type="submit" 
                class="w-full py-3 bg-sage-500 hover:bg-sage-600 text-white font-semibold rounded-lg shadow-md transition-all"
            >
                Create Account
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-sage-500 text-sm mt-8">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-sage-600 font-medium hover:text-sage-700">Log in</a>
        </p>

        <p class="text-center text-sage-400 text-xs mt-4">
            &copy; {{ date('Y') }} Renew Peptides. All rights reserved.
        </p>
    </div>
</body>
</html>
