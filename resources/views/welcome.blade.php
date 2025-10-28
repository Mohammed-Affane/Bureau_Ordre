<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bureau d'Ordre | Système de Gestion de Courrier</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        a:hover{
            cursor: pointer;
            
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 704px;
            padding-top:12rem;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        .fade-in {
            animation: fadeIn 1s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .typing-animation {
            border-right: 3px solid #4f46e5;
            animation: typing 3s steps(40, end), blink 0.75s step-end infinite;
        }
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blink {
            from, to { border-color: transparent; }
            50% { border-color: #4f46e5; }
        }
    </style>
</head>
<body class="font-inter bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-lg">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg> -->
                        <img src="logo_ministere.png" class="w-16 h-16" alt="" srcset="">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Préfecture d'Arrondissement <br><p class="text-center">Ain Chock</span></h1>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                        @if (Route::has('login'))
                            @auth
                                <div class="hidden md:flex items-center space-x-4">
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ url('/admin/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">Admin Dashboard</a>
                                    @elseif(auth()->user()->hasRole('bo'))
                                        <a href="{{ url('/courriers.arrive') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">BO Dashboard</a>
                                    @elseif(auth()->user()->hasRole('cab'))
                                        <a href="{{ url('/cab/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">Cabinet</a>
                                    @elseif(auth()->user()->hasRole('sg'))
                                        <a href="{{ url('/sg/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">Secrétariat Général</a>
                                    @elseif(auth()->user()->hasRole('chef_division'))
                                        <a href="{{ url('division/courriers.arrive') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">Chef Division</a>
                                    @elseif(auth()->user()->hasRole('dai'))
                                        <a href="{{ url('/dai/courriers.arrive') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">DAI</a>
                                    @else
                                        <span class="text-sm text-blue-600">Role not assigned</span>
                                    @endif
                                </div>
                                
                            @endauth
                        @endif
                <div class="flex items-center space-x-4">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="group inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold text-red-600 hover:text-white border-2 border-red-600 hover:bg-red-600 transition-all duration-300 transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L12.414 9H20a1 1 0 110 2h-7.586l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Se déconnecter
                                </button>
                            </form>
                        @else
                            <a href="{{route('login')}}" class="group inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold text-indigo-600 hover:text-white border-2 border-indigo-600 hover:bg-indigo-600 transition-all duration-300 transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Se connecter
                            </a>
                        @endauth
                    
                    {{-- <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Demander l'accès
                    </button> --}}
                </div>

                
            </div>
        </nav>

    <!-- Hero Section -->
    <section class="gradient-bg ">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 ">
            <div class="text-center text-white fade-in  ">
                <h2 class="text-5xl lg:text-6xl font-bold mb-6 ">
                    Système de Gestion
                    <span class="block text-yellow-300">des Courriers</span>
                </h2>
                <p class="text-xl lg:text-2xl text-blue-100 mb-8 max-w-4xl mx-auto">
                    Arrivé, Départ, Interne, Décision et Visa
                    
                </p>
            </div>
        </div>
    </section>



    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">

           
            
            <div class="border-t border-gray-800 mt-2 pt-2 text-center text-gray-400">
                <p>&copy; 2025 Service des Systemes d'Information et de Communication.</p>
            </div>

    </footer>
    <!-- Scripts -->
    @stack('scripts')

    <script>
        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add intersection observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card-hover').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

      
    
    </script>
</body>
</html>