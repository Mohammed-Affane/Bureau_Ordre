<x-app-layout>
    <x-slot name="title">Dashboard DAI</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard DAI', 'url' => route('dai.dashboard')]]
    </x-slot>

    <h2 class="text-2xl font-bold text-gray-900">Bienvenue DAI</h2>
    <p class="mt-2 text-gray-600">Visualisez et gérez vos courriers à traiter et clôturés.</p>
    @php
            $daiStats = [
                ['title' => 'Reçus du CAB', 'value' => '12', 'icon' => 'inbox', 'color' => 'blue'],
                ['title' => 'En Cours', 'value' => '8', 'icon' => 'clock', 'color' => 'yellow'],
                ['title' => 'Complétés', 'value' => '25', 'icon' => 'check-circle', 'color' => 'green'],
                ['title' => 'Retard', 'value' => '3', 'icon' => 'exclamation-triangle', 'color' => 'red']
            ];
        @endphp
        @include('shared.stats-cards', ['stats' => $daiStats])

        <!-- Courriers assignés par division -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @php
                $divisions = [
                    ['name' => 'DT', 'assigned' => 5, 'completed' => 8, 'pending' => 2],
                    ['name' => 'DHCP', 'assigned' => 4, 'completed' => 12, 'pending' => 1],
                    ['name' => 'SSI', 'assigned' => 3, 'completed' => 5, 'pending' => 0]
                ];
            @endphp
            
            @foreach($divisions as $division)
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $division['name'] }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $division['assigned'] + $division['pending'] }} actifs
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Assignés</span>
                            <span class="text-sm font-medium text-blue-600">{{ $division['assigned'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Complétés</span>
                            <span class="text-sm font-medium text-green-600">{{ $division['completed'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">En retard</span>
                            <span class="text-sm font-medium {{ $division['pending'] > 0 ? 'text-red-600' : 'text-gray-400' }}">
                                {{ $division['pending'] }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button class="w-full text-center text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                            Voir les détails →
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Courriers récents et actions requises -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Courriers reçus récemment -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Courriers Reçus du Cabinet</h3>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-sm font-medium text-gray-900">C2024-{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            Nouveau
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">{{ ['Demande technique', 'Autorisation travaux', 'Rapport sécurité', 'Demande info', 'Plainte'][$i-1] }}</p>
                                    <p class="text-xs text-gray-500">Reçu il y a {{ $i }}h</p>
                                </div>
                                <button class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                    Assigner
                                </button>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Actions requises -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions Requises</h3>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-red-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-red-900">3 courriers en retard</p>
                            <p class="text-sm text-red-600">Nécessitent une attention immédiate</p>
                        </div>
                        <button class="text-sm text-red-600 hover:text-red-500 font-medium">
                            Voir
                        </button>
                    </div>
                    
                    <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-yellow-900">5 courriers urgents</p>
                            <p class="text-sm text-yellow-600">À traiter dans les 24h</p>
                        </div>
                        <button class="text-sm text-yellow-600 hover:text-yellow-500 font-medium">
                            Voir
                        </button>
                    </div>
                    
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-blue-900">12 courriers non assignés</p>
                            <p class="text-sm text-blue-600">En attente d'affectation</p>
                        </div>
                        <button class="text-sm text-blue-600 hover:text-blue-500 font-medium">
                            Assigner
                        </button>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
