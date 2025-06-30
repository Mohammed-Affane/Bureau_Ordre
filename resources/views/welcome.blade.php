<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Enterprise Correspondence Management System">
        <title>Bureau d'Ordre | Enterprise Correspondence Management</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|inter:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .corporate-gradient {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            }
            .dark .corporate-gradient {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            }
        </style>
    </head>
    <body class="antialiased font-inter">
        <div class="min-h-screen corporate-gradient selection:bg-red-600 selection:text-white">
            <!-- Top Navigation Bar -->
            <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center space-x-8">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-red-600">
                                <path fill-rule="evenodd" d="M4.5 9.75a6 6 0 0111.573-2.226 3.75 3.75 0 014.133 4.303A4.5 4.5 0 0118 20.25H6.75a5.25 5.25 0 01-2.23-10.004 6.072 6.072 0 01-.02-.496z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-2 text-xl font-semibold text-gray-900 dark:text-white">BUREAU D'ORDRE</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        @if (Route::has('login'))
                            @auth
                                <div class="hidden md:flex items-center space-x-4">
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ url('/admin/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">Admin Dashboard</a>
                                    @elseif(auth()->user()->hasRole('bo'))
                                        <a href="{{ url('/bo/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">BO Dashboard</a>
                                    @elseif(auth()->user()->hasRole('cab'))
                                        <a href="{{ url('/cab/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">Cabinet</a>
                                    @elseif(auth()->user()->hasRole('sg'))
                                        <a href="{{ url('/sg/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">Secrétariat Général</a>
                                    @elseif(auth()->user()->hasRole('chef_division'))
                                        <a href="{{ url('/chef_division/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">Chef Division</a>
                                    @else
                                        <span class="text-sm text-red-600">Role not assigned</span>
                                    @endif
                                </div>
                                
                                <div class="relative group">
                                    <button class="flex items-center space-x-1 focus:outline-none">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-gray-500">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-200 dark:border-gray-700">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <main class="max-w-7xl mx-auto px-6 py-12 sm:py-16 lg:py-20">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
                        Enterprise Correspondence <br class="hidden lg:block"> Management System
                    </h1>
                    <p class="mt-6 text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                        Streamline your document workflow with our comprehensive Bureau d'Ordre solution. 
                        Track, manage, and optimize all incoming and outgoing correspondence with enterprise-grade security.
                    </p>
                    
                    <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                        @auth
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ url('/admin/dashboard') }}" class="px-6 py-3 rounded-md text-base font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                    Go to Admin Dashboard
                                </a>
                            @else
                                <a href="{{ url('/dashboard') }}" class="px-6 py-3 rounded-md text-base font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                    Go to Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-3 rounded-md text-base font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                Sign In
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-3 rounded-md text-base font-medium text-red-600 bg-white hover:bg-gray-50 transition shadow-sm border border-gray-300">
                                    Request Access
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-red-600">
                                <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375z" />
                                <path fill-rule="evenodd" d="M3.087 9l.54 9.176A3 3 0 006.62 21h10.757a3 3 0 002.995-2.824L20.913 9H3.087zm6.163 3.75A.75.75 0 0110 12h4a.75.75 0 010 1.5h-4a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Document Tracking</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Real-time tracking of all documents with complete audit trails and version control.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-red-600">
                                <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Workflow Automation</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Automated routing and approval workflows to reduce manual processing time.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-red-600">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Security & Compliance</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Enterprise-grade security with role-based access control and compliance reporting.
                        </p>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                <div class="max-w-7xl mx-auto px-6 py-8">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="flex justify-center md:order-2 space-x-6">
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Privacy Policy</span>
                                <span class="text-sm">Privacy</span>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Terms</span>
                                <span class="text-sm">Terms</span>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Contact</span>
                                <span class="text-sm">Contact</span>
                            </a>
                        </div>
                        <div class="mt-8 md:mt-0 md:order-1">
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                &copy; 2023 Bureau d'Ordre Management System. All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>