<x-app-layout>
    <x-slot name="title">Dashboard Admin</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Admin', 'url' => route('admin.dashboard')]]
    </x-slot>

    <h2 class="text-2xl font-bold text-gray-900">Bienvenue Admin</h2>
    <p class="mt-2 text-gray-600">Vue d'ensemble du système de gestion des courriers.</p>
    @php
        $adminStats = [
            ['title' => 'Total Courriers', 'value' => $stats['total_courriers'], 'icon' => 'mail', 'color' => 'blue'],
            ['title' => 'Courriers Arrivés', 'value' => $stats['courriers_arrive'], 'icon' => 'inbox', 'color' => 'green'],
            ['title' => 'Courriers Départ', 'value' => $stats['courriers_depart'], 'icon' => 'paper-airplane', 'color' => 'indigo'],
            ['title' => 'Courriers Internes', 'value' => $stats['courriers_interne'], 'icon' => 'refresh', 'color' => 'yellow']
        ];

        $statusStats = [
            ['title' => 'En Attente', 'value' => $stats['en_attente'], 'icon' => 'clock', 'color' => 'yellow'],
            ['title' => 'En Traitement', 'value' => $stats['en_traitement'], 'icon' => 'cog', 'color' => 'blue'],
            ['title' => 'Clôturés', 'value' => $stats['cloture'], 'icon' => 'check-circle', 'color' => 'emerald'],
            ['title' => 'En Retard', 'value' => $stats['retard'], 'icon' => 'exclamation', 'color' => 'red']
        ];
    @endphp
    
    @include('shared.stats-cards', ['stats' => $adminStats])
    @include('shared.stats-cards', ['stats' => $statusStats])

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Graphique d'activité -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Évolution mensuelle des courriers</h3>
            <div id="monthly-chart" class="h-64"></div>
        </div>

        <!-- Graphique par priorité -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par priorité</h3>
            <div id="priority-chart" class="h-64"></div>
        </div>
    </div>

    <!-- Performance des entités -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Performance des Entités</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courriers en cours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courriers en retard</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux de retard</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($entitesStats as $entite)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $entite->nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $entite->courriers_affectes_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $entite->courriers_en_retard_count > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $entite->courriers_en_retard_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($entite->courriers_affectes_count > 0)
                                    {{ round(($entite->courriers_en_retard_count / $entite->courriers_affectes_count) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Courriers récents -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Courriers Récents</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentCourriers as $courrier)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $courrier->reference }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $courrier->type_courrier === 'arrive' ? 'bg-green-100 text-green-800' : 
                                       ($courrier->type_courrier === 'depart' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($courrier->type_courrier) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($courrier->objet, 30) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($courrier->expediteur)
                                    {{ $courrier->expediteur->nom }}
                                @elseif($courrier->entiteExpediteur)
                                    {{ $courrier->entiteExpediteur->nom }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $courrier->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $courrier->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($courrier->statut === 'en_traitement' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $courrier->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('courriers.show', $courrier->id) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Utilisateurs les plus actifs -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Utilisateurs les plus actifs (30 derniers jours)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courriers traités</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activeUsers as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $user->courriers_traites_count }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Données pour le graphique mensuel
            var monthlyData = @json($monthlyStats ?? []);
            var monthlyLabels = Object.keys(monthlyData);
            
            var monthlyChartOptions = {
                series: [
                    {
                        name: 'Total',
                        data: monthlyLabels.map(month => monthlyData[month].total)
                    },
                    {
                        name: 'Arrivés',
                        data: monthlyLabels.map(month => monthlyData[month].arrive)
                    },
                    {
                        name: 'Départ',
                        data: monthlyLabels.map(month => monthlyData[month].depart)
                    },
                    {
                        name: 'Internes',
                        data: monthlyLabels.map(month => monthlyData[month].interne)
                    }
                ],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                colors: ['#4f46e5', '#10b981', '#3b82f6', '#f59e0b'],
                xaxis: {
                    categories: monthlyLabels
                },
                yaxis: {
                    title: {
                        text: 'Nombre de courriers'
                    }
                },
                legend: {
                    position: 'top'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            
            var monthlyChart = new ApexCharts(document.querySelector("#monthly-chart"), monthlyChartOptions);
            monthlyChart.render();
            
            // Données pour le graphique par priorité
            var priorityData = [
                {{ $prioritesStats['normale'] ?? 0 }},
                {{ $prioritesStats['moyenne'] ?? 0 }},
                {{ $prioritesStats['elevee'] ?? 0 }},
                {{ $prioritesStats['urgente'] ?? 0 }}
            ];
            
            var priorityChartOptions = {
                series: priorityData,
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: ['Normale', 'Moyenne', 'Élevée', 'Urgente'],
                colors: ['#10b981', '#f59e0b', '#f97316', '#ef4444'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => {
                                            return a + b
                                        }, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            
            var priorityChart = new ApexCharts(document.querySelector("#priority-chart"), priorityChartOptions);
            priorityChart.render();
        });
    </script>
    @endsection
</x-app-layout>