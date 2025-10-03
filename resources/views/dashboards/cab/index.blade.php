<x-app-layout>
    <x-slot name="title">Dashboard Cabinet</x-slot>

    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex-1">
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
                                <i class="fas fa-crown text-purple-600 mr-3"></i>
                                Dashboard Cabinet
                            </h1>
                            <p class="text-gray-600 text-sm lg:text-base">Vue d'ensemble de votre activité</p>
                        </div>
                        <div class="mt-4 lg:mt-0">
                            <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                <i class="fas fa-circle animate-pulse mr-2 text-green-500"></i>
                                Mis à jour: <span id="lastUpdate" class="ml-1">{{ now()->format('H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Indicators -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-tachometer-alt text-blue-600 text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Taux de Traitement</p>
                <p class="text-2xl font-bold text-gray-900">{{ $performanceMetrics['response_rate'] ?? 0 }}%</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-green-600 text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Délai Moyen</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($performanceMetrics['avg_processing_hours'] ?? 0, 1) }}h</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-600 text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Taux d'Achèvement</p>
                <p class="text-2xl font-bold text-gray-900">{{ $performanceMetrics['completion_rate'] ?? 0 }}%</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Critiques</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['urgents'] + $stats['en_retard'] }}</p>
            </div>
        </div>
    </div>
</div>

            <!-- Main Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Courriers -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Total Courriers</h3>
                        <i class="fas fa-mail-bulk text-blue-500 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($stats['total_courriers']) }}</p>
                    <div class="flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>{{ number_format((($stats['courriers_mois'] - $stats['courriers_semaine']) / max($stats['courriers_semaine'], 1)) * 100, 1) }}% ce mois</span>
                    </div>
                </div>

                <!-- Courriers Arrivés -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Courriers Arrivés</h3>
                        <i class="fas fa-inbox text-green-500 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-green-600 mb-2">{{ number_format($stats['courriers_arrive']) }}</p>
                    <div class="flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>{{ number_format(($stats['courriers_aujourd_hui'] / max($stats['courriers_arrive'], 1)) * 100, 1) }}% aujourd'hui</span>
                    </div>
                </div>

                <!-- En Attente -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">En Attente</h3>
                        <i class="fas fa-clock text-orange-500 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-orange-600 mb-2">{{ number_format($stats['en_attente']) }}</p>
                    <div class="flex items-center text-sm {{ $stats['en_attente'] > 50 ? 'text-red-600' : 'text-green-600' }}">
                        <i class="fas fa-arrow-{{ $stats['en_attente'] > 50 ? 'up' : 'down' }} mr-1"></i>
                        <span>{{ $stats['en_attente'] > 50 ? 'Attention' : 'Sous contrôle' }}</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Evolution Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Évolution Mensuelle</h3>
                        <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>6 derniers mois</option>
                        </select>
                    </div>
                    <div class="h-64">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- Priority Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Répartition par Priorité</h3>
                    </div>
                    <div class="h-64">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Overview Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Vue d'Ensemble des Statuts</h3>
                    <div class="flex space-x-2">
                        <button onclick="exportReport()" class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-download mr-2"></i>Exporter
                        </button>
                        <button onclick="refreshData()" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>Actualiser
                        </button>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Activities Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Critical Items -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Courriers Critiques</h3>
                    
                    @if($activityData['critical_items']->count() > 0)
                        <div class="space-y-4">
                            @foreach($activityData['critical_items'] as $courrier)
                                <div class="flex items-start space-x-3 p-4 bg-red-50 rounded-lg border border-red-200">
                                    <div class="flex-shrink-0 w-3 h-12 bg-red-500 rounded-full mt-1"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $courrier->objet }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                @if($courrier->delais && $courrier->delais < now())
                                                    DÉLAIS EXPIRÉ
                                                @else
                                                    URGENT
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                Réf: {{ $courrier->reference_bo ?? $courrier->reference_arrive ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Échéance: {{ $courrier->delais ? $courrier->delais->format('d/m/Y') : 'Non définie' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-4xl text-green-500 mb-3"></i>
                            <p class="text-gray-500">Aucun courrier critique</p>
                        </div>
                    @endif
                </div>

                <!-- Today's Activity -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité Aujourd'hui</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <i class="fas fa-inbox text-blue-600 text-2xl mb-2"></i>
                            <p class="text-2xl font-bold text-blue-600">{{ $activityData['today_activity']['received'] }}</p>
                            <p class="text-sm text-gray-600">Reçus</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                            <p class="text-2xl font-bold text-green-600">{{ $activityData['today_activity']['processed'] }}</p>
                            <p class="text-sm text-gray-600">Traités</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Statistiques Rapides</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Courriers internes:</span>
                                <span class="font-medium">{{ $stats['courriers_interne'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Courriers départ:</span>
                                <span class="font-medium">{{ $stats['courriers_depart'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">En cours:</span>
                                <span class="font-medium">{{ $stats['en_cours'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Courriers -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Total Courriers</h3>
            <i class="fas fa-mail-bulk text-blue-500 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($stats['total_courriers']) }}</p>
        <div class="flex items-center text-sm text-green-600">
            <i class="fas fa-arrow-up mr-1"></i>
            <span>{{ number_format((($stats['courriers_mois'] - $stats['courriers_semaine']) / max($stats['courriers_semaine'], 1)) * 100, 1) }}% ce mois</span>
        </div>
    </div>

    <!-- Courriers Arrivés -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Courriers Arrivés</h3>
            <i class="fas fa-inbox text-green-500 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-green-600 mb-2">{{ number_format($stats['courriers_arrive']) }}</p>
        <div class="text-sm text-gray-600">
            {{ number_format(($stats['courriers_arrive'] / max($stats['total_courriers'], 1)) * 100, 1) }}% du total
        </div>
    </div>

    <!-- Courriers Départ -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Courriers Départ</h3>
            <i class="fas fa-paper-plane text-purple-500 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($stats['courriers_depart']) }}</p>
        <div class="text-sm text-gray-600">
            {{ number_format(($stats['courriers_depart'] / max($stats['total_courriers'], 1)) * 100, 1) }}% du total
        </div>
    </div>

    <!-- Courriers Internes -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Courriers Internes</h3>
            <i class="fas fa-exchange-alt text-orange-500 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-orange-600 mb-2">{{ number_format($stats['courriers_interne']) }}</p>
        <div class="text-sm text-gray-600">
            {{ number_format(($stats['courriers_interne'] / max($stats['total_courriers'], 1)) * 100, 1) }}% du total
        </div>
    </div>
</div>

<!-- Type Distribution Chart -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Répartition par Type de Courrier</h3>
        <a href="{{ route('cab.courriers.all') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Voir tous les courriers <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="h-64">
        <canvas id="typeChart"></canvas>
    </div>
</div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            loadChartData();
            startAutoRefresh();
        });

        // Load chart data via AJAX
        function loadChartData() {
            fetch('{{ route("cab.chart-data") }}')
                .then(response => response.json())
                .then(data => {
                    initMonthlyChart(data.monthly);
                    initPriorityChart(data.priority);
                    initStatusChart(data.status);
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                    // Fallback to empty charts
                    initEmptyCharts();
                });
        }

        // Monthly Evolution Chart (Line)
        function initMonthlyChart(chartData) {
            const ctx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Total Courriers',
                            data: chartData.total,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Urgents',
                            data: chartData.urgent,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Priority Distribution Chart (Doughnut)
        function initPriorityChart(chartData) {
            const ctx = document.getElementById('priorityChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.data,
                        backgroundColor: [
                            '#10b981', // Normal - green
                            '#ef4444', // Urgent - red
                            '#8b5cf6', // Confidentiel - purple
                            '#f59e0b', // Réponse obligatoire - orange
                            '#6b7280'  // Others - gray
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Status Overview Chart (Bar)
        function initStatusChart(chartData) {
            const ctx = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels.map(label => {
                        // Convert status to readable format
                        const statusMap = {
                            'en_attente': 'En Attente',
                            'en_cours': 'En Cours',
                            'traite': 'Traité',
                            'cloture': 'Clôturé'
                        };
                        return statusMap[label] || label;
                    }),
                    datasets: [{
                        label: 'Nombre de Courriers',
                        data: chartData.data,
                        backgroundColor: [
                            '#f59e0b', // En attente - yellow
                            '#3b82f6', // En cours - blue
                            '#10b981', // Traité - green
                            '#6b7280'  // Clôturé - gray
                        ],
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Fallback empty charts
        function initEmptyCharts() {
            const emptyData = {
                monthly: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                    total: [0, 0, 0, 0, 0, 0],
                    urgent: [0, 0, 0, 0, 0, 0]
                },
                priority: {
                    labels: ['Normal', 'Urgent'],
                    data: [1, 0]
                },
                status: {
                    labels: ['En Attente', 'En Cours'],
                    data: [0, 0]
                }
            };
            
            initMonthlyChart(emptyData.monthly);
            initPriorityChart(emptyData.priority);
            initStatusChart(emptyData.status);
        }

        // Auto-refresh functionality
        function startAutoRefresh() {
            // Update timestamp every 30 seconds
            setInterval(updateTimestamp, 30000);
            
            // Refresh data every 2 minutes
            setInterval(refreshData, 120000);
        }

        function updateTimestamp() {
            const now = new Date();
            document.getElementById('lastUpdate').textContent = now.toLocaleTimeString('fr-FR');
        }

        function refreshData() {
            fetch('{{ route("cab.realtime-stats") }}')
                .then(response => response.json())
                .then(data => {
                    updateTimestamp();
                    // You could update specific elements here instead of full reload
                    console.log('Data refreshed:', data);
                })
                .catch(error => console.error('Error refreshing data:', error));
        }

        function exportReport() {
            // Implement export functionality
            alert('Fonctionnalité d\'export à implémenter');
        }


        // Type Distribution Chart (Pie)
function initTypeChart(chartData) {
    const ctx = document.getElementById('typeChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: chartData.labels.map(label => {
                const typeMap = {
                    'arrive': 'Arrivée',
                    'depart': 'Départ',
                    'interne': 'Interne',
                    'visa': 'Visa',
                    'decision': 'Décision'
                };
                return typeMap[label] || label;
            }),
            datasets: [{
                data: chartData.data,
                backgroundColor: [
                    '#10b981', // Arrivée - green
                    '#3b82f6', // Départ - blue
                    '#f59e0b', // Interne - orange
                    '#8b5cf6', // Visa - purple
                    '#ef4444'  // Décision - red
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

// Dans loadChartData, ajoutez l'initialisation du type chart
function loadChartData() {
    fetch('{{ route("cab.chart-data") }}')
        .then(response => response.json())
        .then(data => {
            initMonthlyChart(data.monthly);
            initPriorityChart(data.priority);
            initStatusChart(data.status);
            initTypeChart(data.type); // Nouveau graphique
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            initEmptyCharts();
        });
}
    </script>
    @endpush
</x-app-layout>