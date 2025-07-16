<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bureau d'Ordre | Système de Gestion de Courrier</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        a:hover{
            cursor: pointer;
            
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                    <div class="bg-indigo-600 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">BUREAU D'ORDRE</h1>
                        <p class="text-sm text-gray-600">Gestion de Courrier</p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                        @if (Route::has('login'))
                            @auth
                                <div class="hidden md:flex items-center space-x-4">
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ url('/admin/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">Admin Dashboard</a>
                                    @elseif(auth()->user()->hasRole('bo'))
                                        <a href="{{ url('/bo/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">BO Dashboard</a>
                                    @elseif(auth()->user()->hasRole('cab'))
                                        <a href="{{ url('/cab/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">Cabinet</a>
                                    @elseif(auth()->user()->hasRole('sg'))
                                        <a href="{{ url('/sg/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">Secrétariat Général</a>
                                    @elseif(auth()->user()->hasRole('chef_division'))
                                        <a href="{{ url('/chef_division/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-red-500 transition">Chef Division</a>
                                    @else
                                        <span class="text-sm text-blue-600">Role not assigned</span>
                                    @endif
                                </div>
                                
                
                            @else
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                        </svg>
                                        S'inscrire
                                    </a>
                                @endif
                            @endauth
                        @endif
                <div class="flex items-center space-x-4">
                    <a href="{{route('login')}}" class="group inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold text-indigo-600 hover:text-white border-2 border-indigo-600 hover:bg-indigo-600 transition-all duration-300 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Se connecter
                    </a>
                    
                    {{-- <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Demander l'accès
                    </button> --}}
                </div>

                
            </div>
        </nav>



    <!-- Hero Section -->
    <section class="gradient-bg py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center text-white fade-in">
                <h2 class="text-5xl lg:text-6xl font-bold mb-6">
                    Système de Gestion
                    <span class="block text-yellow-300">de Courrier</span>
                </h2>
                <p class="text-xl lg:text-2xl text-blue-100 mb-8 max-w-4xl mx-auto">
                    Optimisez votre flux documentaire avec notre solution complète de Bureau d'Ordre.
                    Suivez, gérez et traitez tous vos courriers arrive et depart
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg">
                        Commencer maintenant
                    </button>
                    <button class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors">
                        Découvrir les fonctionnalités
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Pourquoi choisir notre système ?
                </h3>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Notre solution offre une gestion complète et sécurisée de tous vos documents administratifs
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-gray-50 p-8 rounded-xl">
                    <div class="bg-indigo-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-indigo-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-4">Suivi en Temps Réel</h4>
                    <p class="text-gray-600">
                        Suivez chaque document de sa réception à son traitement final avec un historique complet et des notifications automatiques.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover bg-gray-50 p-8 rounded-xl">
                    <div class="bg-green-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-4">Workflow Automatisé</h4>
                    <p class="text-gray-600">
                        Automatisez le routage et l'approbation des documents selon vos procédures internes pour gagner du temps.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover bg-gray-50 p-8 rounded-xl">
                    <div class="bg-red-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-4">Sécurité Renforcée</h4>
                    <p class="text-gray-600">
                        Contrôle d'accès basé sur les rôles, chiffrement des données et audit complet pour une sécurité maximale.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Accès par Rôle
                </h3>
                <p class="text-xl text-gray-600">
                    Chaque utilisateur dispose d'un tableau de bord adapté à ses responsabilités
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Admin Role -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-purple-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 ml-3">Administrateur</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Gestion complète du système, configuration des utilisateurs et supervision globale
                    </p>
                </div>

                <!-- BO Role -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 ml-3">Bureau d'Ordre</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Enregistrement, classification et dispatch des courriers entrants et sortants
                    </p>
                </div>

                <!-- Cabinet Role -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 ml-3">Cabinet</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Traitement des courriers prioritaires et gestion des dossiers sensibles
                    </p>
                </div>

                <!-- SG Role -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-yellow-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 ml-3">Secrétariat Général</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Coordination administrative et suivi des procédures institutionnelles
                    </p>
                </div>

                <!-- Chef Division Role -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 ml-3">Chef Division</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Gestion des courriers de division et validation des traitements
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-indigo-600">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h3 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Prêt à moderniser votre gestion documentaire ?
            </h3>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Rejoignez les organisations qui ont déjà optimisé leur workflow avec notre solution
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg">
                    Demander une démonstration
                </button>
                <button class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors">
                    Contacter l'équipe
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-indigo-600 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold">Bureau d'Ordre</h4>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Solution complète de gestion documentaire pour les organisations modernes. 
                        Optimisez vos processus et gagnez en efficacité.
                    </p>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-4">Liens Utiles</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Support</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Formation</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-4">Contact</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Politique de confidentialité</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Conditions d'utilisation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Nous contacter</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Bureau d'Ordre Management System. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

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