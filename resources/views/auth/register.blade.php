<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Transcript System') }} - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #075985 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #075985 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-center mb-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold gradient-text mb-1">Create Account</h1>
                    <p class="text-gray-500 text-sm">Register for a new account</p>
                </div>
            </div>
            
            @if ($errors->any())
                <div class="mb-4">
                    <div class="font-medium text-red-600">
                        {{ __('Whoops! Something went wrong.') }}
                    </div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters</p>
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white gradient-bg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Register
                    </button>
                </div>
            </form>
            
            <div class="mt-6 flex items-center justify-center">
                <div class="text-sm">
                    <span class="text-gray-500">Already have an account?</span>
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 ml-1">
                        Sign in
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Transcript Management System. All rights reserved.
        </div>
    </div>
</body>
</html>
