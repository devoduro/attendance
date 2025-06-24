<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Transcript System') }} - Verify Email</title>

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
                    <h1 class="text-3xl font-bold gradient-text mb-1">Verify Email</h1>
                    <p class="text-gray-500 text-sm">Please verify your email address</p>
                </div>
            </div>
            
            @if (session('resent'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            
            <div class="mb-6 text-center">
                <div class="flex justify-center mb-4">
                    <div class="rounded-full bg-primary-100 p-3">
                        <i class="fas fa-envelope text-primary-600 text-xl"></i>
                    </div>
                </div>
                
                <p class="text-gray-700 mb-4">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                </p>
                <p class="text-gray-700 mb-6">
                    {{ __('If you did not receive the email') }},
                </p>
                
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white gradient-bg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        {{ __('Click here to request another') }}
                    </button>
                </form>
            </div>
            
            <div class="mt-6 flex items-center justify-center">
                <div class="text-sm">
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                        <i class="fas fa-arrow-left mr-1"></i> Back to login
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
