<x-app-layout>
    <x-slot name="title">Dashboard Cabinet</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Cabinet', 'url' => route('cab.dashboard')]]
    </x-slot>

    <h2 class="text-2xl font-bold text-gray-900">Bienvenue Cabinet</h2>
    <p class="mt-2 text-gray-600">Suivez les affectations et courriers à traiter.</p>

    @php
            $cabStats = [
                ['title' => 'À Affecter', 'value' => '8', 'icon' => 'exclamation-circle', 'color' => 'red'],
                ['title' => 'Affectés au DAI', 'value' => '12', 'icon' => 'arrow-right', 'color' => 'blue'],
                ['title' => 'Affectés au SG', 'value' => '15', 'icon' => 'building-office', 'color' => 'green'],
                ['title' => 'Total traités', 'value' => '156', 'icon' => 'check-circle', 'color' => 'emerald']
            ];
        @endphp
        @include('shared.stats-cards', ['stats' => $cabStats])

        <!-- Courriers en attente d'affectation -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">Courriers en Attente d'Affectation</h3>
                <div class="flex space-x-2">
                    <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Filtrer
                    </button>
                    <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Trier
                    </button>
                </div>
            </div>
            
            <div class="space-y-4">
                @for($i = 1; $i <= 6; $i++)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        C2024-{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        {{ $i <= 2 ? 'bg-red-100 text-red-800' : ($i <= 4 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $i <= 2 ? 'Urgent' : ($i <= 4 ? 'Moyen' : 'Normal') }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Reçu {{ $i }}h
                                    </span>
                                </div>
                                
                                <h4 class="text-sm font-medium text-gray-900 mb-1">
                                    {{ ['Demande d\'autorisation', 'Rapport technique', 'Correspondance officielle', 'Demande d\'information', 'Plainte client', 'Demande de stage'][$i-1] }}
                                </h4>
                                
                                <p class="text-sm text-gray-600 mb-2">
                                    De: {{ ['Ministère Public', 'Direction Régionale', 'Entreprise SARL', 'Citoyen', 'Association', 'Université'][$i-1] }}
                                </p>
                                
                                <p class="text-xs text-gray-500 line-clamp-2">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore...
                                </p>
                            </div>
                            
                            <div class="ml-4 flex flex-col space-y-2">
                                <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Affecter au DAI
                                </button>
                                <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    Affecter au SG
                                </button>
                                <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Voir Détails
                                </button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
              <!-- Pagination -->
              <div class="flex items-center justify-between mt-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Précédent
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Suivant
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span class="font-medium">1</span> à <span class="font-medium">6</span> sur <span class="font-medium">8</span> résultats
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Précédent</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Suivant</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques d'affectation -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Répartition par destination -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition des Affectations</h3>
                <div class="space-y-4">
                    @php
                        $affectations = [
                            ['name' => 'DAI', 'count' => 45, 'percentage' => 60, 'color' => 'blue'],
                            ['name' => 'SG', 'count' => 30, 'percentage' => 40, 'color' => 'green'],
                            ['name' => 'DT', 'count' => 15, 'percentage' => 20, 'color' => 'purple'],
                            ['name' => 'DHCP', 'count' => 10, 'percentage' => 13, 'color' => 'yellow']
                        ];
                    @endphp
                    @foreach($affectations as $affectation)
                        <div class="flex items-center">
                            <div class="w-20 text-sm font-medium text-gray-900">{{ $affectation['name'] }}</div>
                            <div class="flex-1 ml-4">
                                <div class="bg-gray-200 rounded-full h-3">
                                    <div class="bg-{{ $affectation['color'] }}-500 h-3 rounded-full" style="width: {{ $affectation['percentage'] }}%"></div>
                                </div>
                            </div>
                            <div class="ml-4 text-sm text-gray-500 w-12">{{ $affectation['count'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Temps moyen de traitement -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Temps Moyen de Traitement</h3>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">2.3h</div>
                    <p class="text-sm text-gray-500 mb-4">Temps moyen d'affectation</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Moins d'1h:</span>
                            <span class="font-medium">65%</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">1-4h:</span>
                            <span class="font-medium">25%</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Plus de 4h:</span>
                            <span class="font-medium text-red-600">10%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
