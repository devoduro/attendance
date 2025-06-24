<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'School Assessment Platform') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }
        
        .auth-card {
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.3s ease;
        }
        
        .auth-card:hover {
            box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.2), 0 10px 10px -5px rgba(59, 130, 246, 0.1);
            transform: translateY(-5px);
        }
        
        .input-field {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        
        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s;
            z-index: -1;
        }
        
        .btn-primary:hover:before {
            left: 100%;
        }
        
        .text-primary {
            color: #3b82f6;
        }
        
        .hover-text-primary:hover {
            color: #2563eb;
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -100px;
        }
        
        .shape-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -50px;
        }
        
        .shape-3 {
            width: 150px;
            height: 150px;
            bottom: 50px;
            right: 10%;
        }
        
        .shape-4 {
            width: 80px;
            height: 80px;
            top: 30%;
            left: 20%;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient relative">
        <!-- Floating shapes for visual interest -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
        
        <div class="py-6">
            @yield('content')
        </div>
    </div>
    
    <footer class="bg-white py-4 text-center text-sm text-gray-500 shadow-inner">
        <p>&copy; {{ date('Y') }} School Assessment Platform. All rights reserved.</p>
    </footer>
    
    <script>
        // Add subtle animation to shapes
        document.addEventListener('DOMContentLoaded', function() {
            const shapes = document.querySelectorAll('.shape');
            shapes.forEach((shape, index) => {
                // Add subtle floating animation
                shape.animate([
                    { transform: 'translateY(0) rotate(0deg)' },
                    { transform: 'translateY(-20px) rotate(5deg)' },
                    { transform: 'translateY(0) rotate(0deg)' }
                ], {
                    duration: 6000 + (index * 1000),
                    iterations: Infinity,
                    easing: 'ease-in-out'
                });
            });
        });
    </script>
</body>
</html>
