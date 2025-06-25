<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bureau d'Ordre</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="p-6 text-right">
                @auth
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ url('/admin/dashboard') }}">Admin Dashboard</a>
                    @elseif(auth()->user()->hasRole('bo'))
                        <a href="{{ url('/bo/dashboard') }}">Bureau d'Ordre Dashboard</a>
                    @elseif(auth()->user()->hasRole('cab'))
                        <a href="{{ url('/cab/dashboard') }}">Cabinet Dashboard</a>
                    @elseif(auth()->user()->hasRole('sg'))
                        <a href="{{ url('/sg/dashboard') }}">Secrétariat Général Dashboard</a>
                    @elseif(auth()->user()->hasRole('chef_division'))
                        <a href="{{ url('/chef_division/dashboard') }}">Chef Division Dashboard</a>
                    @else
                        <span class="text-red-600">Role not assigned</span>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="ml-4 text-red-600 hover:underline">Logout</button>
                    </form>
                @endauth

                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Bureau d'Ordre</h1>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Welcome to Bureau d'Ordre Management System</h2>
                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    This system helps manage and track all incoming and outgoing correspondence efficiently.
                                    Please log in to access your dashboard and manage your tasks.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
