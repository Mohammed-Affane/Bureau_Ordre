<x-app-layout>
    <x-slot name="title">Dashboard Admin</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Admin', 'url' => route('admin.dashboard')]]
    </x-slot>

    <h2 class="text-2xl font-bold text-gray-900">Bienvenue Admin</h2>
    <p class="mt-2 text-gray-600">Vue d’ensemble du système de gestion des courriers.</p>

    @php
            $adminStats = [
                ['title' => 'Total Courriers', 'value' => '1,234', 'icon' => 'mail', 'color' => 'blue', 'change' => ['value' => 12, 'positive' => true]],
                ['title' => 'Utilisateurs Actifs', 'value' => '89', 'icon' => 'users', 'color' => 'green', 'change' => ['value' => 5, 'positive' => true]],
                ['title' => 'En Attente', 'value' => '45', 'icon' => 'clock', 'color' => 'yellow'],
                ['title' => 'Traités ce mois', 'value' => '256', 'icon' => 'check-circle', 'color' => 'emerald', 'change' => ['value' => 8, 'positive' => true]]
            ];
        @endphp
        @include('shared.stats-cards', ['stats' => $adminStats])

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Graphique d'activité -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activité des Courriers</h3>
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                    <p class="text-gray-500">Graphique d'activité (Chart.js)</p>
                </div>
            </div>

            <!-- Courriers récents -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Courriers Récents</h3>
                <div class="space-y-3">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    C{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    Courrier {{ $i }} - Demande d'information
                                </p>
                                <p class="text-sm text-gray-500">
                                    Reçu il y a {{ $i }} heures
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    En attente
                                </span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Performance des départements -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Performance des Départements</h3>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Département</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courriers Traités</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temps Moyen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $departments = [
                                ['name' => 'DT', 'treated' => 45, 'avg_time' => '2.5j', 'status' => 'excellent'],
                                ['name' => 'DHCP', 'treated' => 32, 'avg_time' => '3.1j', 'status' => 'bon'],
                                ['name' => 'SSI', 'treated' => 28, 'avg_time' => '4.2j', 'status' => 'moyen']
                            ];
                        @endphp
                        @foreach($departments as $dept)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dept['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dept['treated'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dept['avg_time'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $dept['status'] === 'excellent' ? 'bg-green-100 text-green-800' : 
                                           ($dept['status'] === 'bon' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($dept['status']) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</x-app-layout>
