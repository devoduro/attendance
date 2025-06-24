<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Transcript System') }} - Two Factor Authentication</title>

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
        
        .otp-input {
            width: 3rem;
            height: 3rem;
            text-align: center;
            font-size: 1.5rem;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
        }
        
        .otp-input:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 1px #0ea5e9;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-center mb-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold gradient-text mb-1">Two-Factor Authentication</h1>
                    <p class="text-gray-500 text-sm">Enter your authentication code to continue</p>
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
            
            <div x-data="{ recovery: false }">
                <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
                    {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                </div>

                <div class="mb-4 text-sm text-gray-600" x-show="recovery">
                    {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                </div>

                <form method="POST" action="{{ route('two-factor.login') }}">
                    @csrf
                    
                    <div class="mt-4" x-show="! recovery">
                        <label for="code" class="block text-sm font-medium text-gray-700">{{ __('Code') }}</label>
                        
                        <div class="flex justify-between mt-2 space-x-2">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input" autofocus>
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input">
                        </div>
                        
                        <input id="code" type="hidden" name="code" class="form-control">
                    </div>

                    <div class="mt-4" x-show="recovery">
                        <label for="recovery_code" class="block text-sm font-medium text-gray-700">{{ __('Recovery Code') }}</label>
                        <input id="recovery_code" type="text" name="recovery_code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                x-show="! recovery"
                                x-on:click="recovery = true">
                            {{ __('Use a recovery code') }}
                        </button>

                        <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                x-show="recovery"
                                x-on:click="recovery = false">
                            {{ __('Use an authentication code') }}
                        </button>

                        <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white gradient-bg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const inputs = document.querySelectorAll('.otp-input');
                        const codeInput = document.getElementById('code');
                        
                        // Auto-focus next input
                        inputs.forEach((input, index) => {
                            input.addEventListener('input', function() {
                                if (this.value.length === 1) {
                                    if (index < inputs.length - 1) {
                                        inputs[index + 1].focus();
                                    }
                                }
                                updateHiddenInput();
                            });
                            
                            // Handle backspace
                            input.addEventListener('keydown', function(e) {
                                if (e.key === 'Backspace' && !this.value) {
                                    if (index > 0) {
                                        inputs[index - 1].focus();
                                    }
                                }
                            });
                        });
                        
                        // Update hidden input with combined value
                        function updateHiddenInput() {
                            let code = '';
                            inputs.forEach(input => {
                                code += input.value;
                            });
                            codeInput.value = code;
                        }
                    });
                </script>
            </div>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Transcript Management System. All rights reserved.
        </div>
    </div>
</body>
</html>
