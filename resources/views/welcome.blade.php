<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NutriCount - National Hospital Sri Lanka</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('photo-1505576399279-565b52d4ac71.avif') }}');
            background-size: cover;
            background-position: center;
            color: white;
        }
        .feature-card {
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="antialiased">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-bold text-green-600">NutriCount</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="dashboard" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Simple Diet Management
                        </a>
                        {{-- <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Patients @svg('bxl-dev-to')
                        </a> --}}
                        <a href="simple/simple-diets" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Diets
                        </a>
                        <a href="simple/calender" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Reports
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                        <div>
                                @if (env('PIN_LOGIN', true))
                                    <a href="{{ url('/pin-login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Login</a>
                                @else
                                    <a href="{{ url('/dashboard/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Login</a>
                                @endif
                                {{-- <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-900">Log in</a> --}}
                
                                    {{-- <a href="{{ route(name: 'register') }}" class="ml-4 text-indigo-600 hover:text-indigo-900">Register</a> --}}
                        </div>
                    
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <div class="hero-section py-20 text-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl">
                    <span class="block">National Hospital Sri Lanka</span>
                    <span class="block text-green-400">Nutrition Management System</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Streamline patient nutrition planning, diet allocation, and monitoring for improved healthcare outcomes.
                </p>
                <div class="mt-10 sm:flex sm:justify-center">
                    <div class="rounded-md shadow">
                        <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                            Learn More
                        </a>
                    </div>
                    <div class="mt-3 sm:mt-0 sm:ml-3">
                        {{-- <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-green-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                            Sign In
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Comprehensive Nutrition Management
                    </h2>
                    <p class="mt-4 text-lg text-gray-500">
                        Efficiently manage dietary needs across all hospital units
                    </p>
                </div>

                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($features as $feature)
                        <div class="feature-card pt-6 border rounded-lg shadow-sm px-6 pb-8 bg-white">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white mx-auto">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-lg font-medium text-gray-900 text-center">{{ $feature['title'] }}</h3>
                            <p class="mt-2 text-base text-gray-500 text-center">
                                {{ $feature['description'] }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-gray-50 pt-12 sm:pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Trusted by healthcare professionals
                    </h2>
                    <p class="mt-3 text-xl text-gray-500 sm:mt-4">
                        Supporting nutritional management across the National Hospital Sri Lanka
                    </p>
                </div>
            </div>
            <div class="mt-10 pb-12 bg-white sm:pb-16">
                <div class="relative">
                    <div class="absolute inset-0 h-1/2 bg-gray-50"></div>
                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-4xl mx-auto">
                            <div class="rounded-lg bg-white shadow-lg sm:grid sm:grid-cols-2">
                                <div class="flex flex-col border-b border-gray-100 p-6 text-center sm:border-0 sm:border-r">
                                    <p class="order-1 text-sm font-medium text-gray-500">Daily Diets Managed</p>
                                    <p class="order-2 mt-2 text-3xl font-extrabold text-green-600">{{ $stats['dailyDiets'] }}+</p>
                                </div>
                                <div class="flex flex-col border-t border-b border-gray-100 p-6 text-center sm:border-0 sm:border-l sm:border-r">
                                    <p class="order-1 text-sm font-medium text-gray-500">Hospital Units</p>
                                    <p class="order-2 mt-2 text-3xl font-extrabold text-green-600">{{ $stats['hospitalUnits'] }}+</p>
                                </div>
                                {{-- <div class="flex flex-col border-t border-gray-100 p-6 text-center sm:border-0 sm:border-l">
                                    <p class="order-1 text-sm font-medium text-gray-500">Nutritional Plans</p>
                                    <p class="order-2 mt-2 text-3xl font-extrabold text-green-600">{{ $stats['nutritionalPlans'] }}+</p>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:order-2 space-x-6">
                    <a href="#" class="text-gray-400 hover:text-gray-300">
                        <span class="sr-only">Help</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm-1-4h2v2h-2v-2zm1.976-10.886c2.184 0 3.02 1.209 3.02 2.384 0 1.145-.979 1.781-1.64 2.208-.66.431-.92.879-.92 1.394v.538h-1.924v-.656c0-.92.652-1.585 1.313-2.016.66-.43 1.083-.774 1.083-1.311 0-.539-.424-.982-1.084-.982-.638 0-1.177.321-1.473.877l-1.66-.961c.551-1.025 1.666-1.474 3.284-1.474z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-300">
                        <span class="sr-only">Contact</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base text-gray-400">
                        &copy; 2025 National Hospital Sri Lanka. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>