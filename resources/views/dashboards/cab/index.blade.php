<x-app-layout>
    <x-slot name="title">Cabinet du Gouverneur - Tableau de Bord Exécutif</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Cabinet', 'url' => route('cab.dashboard')]]
    </x-slot>

    @push('styles')
    <style>
        .dashboard-container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .header h1 { font-size: 2.5rem; font-weight: 700; color: #2d3748; margin-bottom: 10px; }
        .header p { color: #718096; font-size: 1.1rem; }
        .real-time-indicator {
            display: inline-flex; align-items: center; gap: 8px; background: #10b981;
            color: white; padding: 8px 16px; border-radius: 20px; font-size: 0.9rem; margin-top: 15px;
        }
        .pulse { animation: pulse 2s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
        
        .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .metric-card {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 16px;
            padding: 25px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border-left: 5px solid;
            transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative; overflow: hidden;
        }
        .metric-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); }
        .metric-icon {
            width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center;
            justify-content: center; font-size: 1.5rem; color: white; margin-bottom: 15px;
        }
        .metric-value { font-size: 2.5rem; font-weight: 700; margin-bottom: 5px; }
        .metric-label { color: #64748b; font-size: 0.95rem; font-weight: 500; }
        .metric-trend { display: flex; align-items: center; gap: 5px; font-size: 0.85rem; margin-top: 8px; }
        
        .card-blue { border-left-color: #3b82f6; }
        .card-blue .metric-icon { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .card-blue .metric-value { color: #1e40af; }
        
        .card-green { border-left-color: #10b981; }
        .card-green .metric-icon { background: linear-gradient(135deg, #10b981, #047857); }
        .card-green .metric-value { color: #065f46; }
        
        .card-purple { border-left-color: #8b5cf6; }
        .card-purple .metric-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .card-purple .metric-value { color: #6d28d9; }
        
        .card-red { border-left-color: #ef4444; }
        .card-red .metric-icon { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .card-red .metric-value { color: #b91c1c; }
        
        .card-orange { border-left-color: #f59e0b; }
        .card-orange .metric-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .card-orange .metric-value { color: #92400e; }
        
        .card-indigo { border-left-color: #6366f1; }
        .card-indigo .metric-icon { background: linear-gradient(135deg, #6366f1, #4f46e5); }
        .card-indigo .metric-value { color: #3730a3; }

        .performance-indicators { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .kpi-card {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 16px;
            padding: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .kpi-value { font-size: 2rem; font-weight: 700; margin-bottom: 5px; }
        .kpi-label { color: #6b7280; font-size: 0.9rem; }
        
        .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px; }
        .chart-card {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 16px;
            padding: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 350px; /* Fixed height for charts */
        }
        .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .chart-title { font-size: 1.2rem; font-weight: 600; color: #1f2937; }
        .chart-subtitle { color: #6b7280; font-size: 0.9rem; }
        
        .activities-section { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px; }
        .activity-card {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 16px;
            padding: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .courrier-item { display: flex; align-items: start; gap: 15px; padding: 15px 0; border-bottom: 1px solid #f1f5f9; }
        .courrier-item:last-child { border-bottom: none; }
        .courrier-priority { width: 8px; height: 60px; border-radius: 4px; flex-shrink: 0; }
        .priority-urgent { background: #ef4444; }
        .priority-normal { background: #10b981; }
        .priority-confidentiel { background: #8b5cf6; }
        .priority-reponse { background: #f59e0b; }
        
        .courrier-content { flex: 1; }
        .courrier-ref { font-weight: 600; color: #1f2937; margin-bottom: 5px; }
        .courrier-subject { color: #374151; font-size: 0.95rem; margin-bottom: 5px; }
        .courrier-meta { display: flex; gap: 15px; font-size: 0.85rem; color: #6b7280; }
        .courrier-actions { display: flex; flex-direction: column; gap: 8px; }
        
        .btn {
            padding: 8px 16px; border-radius: 8px; border: none; font-size: 0.85rem;
            font-weight: 500; cursor: pointer; transition: all 0.2s ease;
        }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        .btn-outline { background: transparent; border: 1px solid #d1d5db; color: #374151; }
        .btn-outline:hover { background: #f9fafb; border-color: #9ca3af; }
        
        .trend-up { color: #059669; }
        .trend-down { color: #dc2626; }
        .trend-stable { color: #6b7280; }
        
        .expired-label {
            background-color: #ef4444;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-left: 10px;
        }
        
        @media (max-width: 1024px) {
            .charts-grid, .activities-section { grid-template-columns: 1fr; }
            .chart-card { height: 300px; }
        }
        
        @media (max-width: 768px) {
            .header h1 { font-size: 2rem; }
            .metrics-grid { grid-template-columns: 1fr; }
            .chart-card { height: 250px; }
        }
    </style>
    @endpush

    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="header">
            <h1><i class="fas fa-crown" style="color: #8b5cf6; margin-right: 15px;"></i>Cabinet du Gouverneur</h1>
            <p>Tableau de Bord Exécutif - Gestion des Courriers et Correspondances Officielles</p>
            <div class="real-time-indicator">
                <i class="fas fa-circle pulse" style="font-size: 8px;"></i>
                Données en temps réel - Dernière mise à jour: <span id="lastUpdate">{{ now()->format('H:i:s') }}</span>
            </div>
        </div>

        <!-- Key Performance Indicators -->
        <div class="performance-indicators">
            <div class="kpi-card">
                <div class="kpi-value" style="color: #3b82f6;">{{ $performanceMetrics['response_rate'] }}%</div>
                <div class="kpi-label">Taux de Traitement</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value" style="color: #10b981;">{{ number_format($performanceMetrics['avg_processing_days'], 1) }}j</div>
                <div class="kpi-label">Délai Moyen</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value" style="color: #8b5cf6;">{{ $performanceMetrics['productivity_score'] }}%</div>
                <div class="kpi-label">Score Productivité</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value" style="color: #ef4444;">{{ $stats['urgents'] + $stats['en_retard'] }}</div>
                <div class="kpi-label">Courriers Critiques</div>
            </div>
        </div>

        <!-- Main Metrics Grid -->
        <div class="metrics-grid">
            <!-- Volume Metrics -->
            <div class="metric-card card-blue">
                <div class="metric-icon"><i class="fas fa-mail-bulk"></i></div>
                <div class="metric-value">{{ number_format($stats['total_courriers']) }}</div>
                <div class="metric-label">Total Courriers</div>
                <div class="metric-trend trend-up">
                    <i class="fas fa-arrow-up"></i> +{{ number_format((($stats['courriers_mois'] - $stats['courriers_semaine']) / max($stats['courriers_semaine'], 1)) * 100, 1) }}% ce mois
                </div>
            </div>

            <div class="metric-card card-green">
                <div class="metric-icon"><i class="fas fa-inbox"></i></div>
                <div class="metric-value">{{ number_format($stats['courriers_arrive']) }}</div>
                <div class="metric-label">Courriers Arrivés</div>
                <div class="metric-trend trend-up">
                    <i class="fas fa-arrow-up"></i> +{{ number_format(($stats['courriers_aujourd_hui'] / max($stats['courriers_arrive'], 1)) * 100, 1) }}% aujourd'hui
                </div>
            </div>

            <div class="metric-card card-purple">
                <div class="metric-icon"><i class="fas fa-paper-plane"></i></div>
                <div class="metric-value">{{ number_format($stats['courriers_depart']) }}</div>
                <div class="metric-label">Courriers Départ</div>
                <div class="metric-trend trend-stable">
                    <i class="fas fa-minus"></i> Stable
                </div>
            </div>

            <div class="metric-card card-indigo">
                <div class="metric-icon"><i class="fas fa-exchange-alt"></i></div>
                <div class="metric-value">{{ number_format($stats['courriers_interne']) }}</div>
                <div class="metric-label">Courriers Internes</div>
                <div class="metric-trend trend-up">
                    <i class="fas fa-arrow-up"></i> +15.2%
                </div>
            </div>

            <div class="metric-card card-orange">
                <div class="metric-icon"><i class="fas fa-clock"></i></div>
                <div class="metric-value">{{ number_format($stats['en_attente']) }}</div>
                <div class="metric-label">En Attente</div>
                <div class="metric-trend {{ $stats['en_attente'] > 100 ? 'trend-up' : 'trend-down' }}">
                    <i class="fas fa-arrow-{{ $stats['en_attente'] > 100 ? 'up' : 'down' }}"></i> 
                    {{ $stats['en_attente'] > 100 ? '+' : '-' }}{{ abs($stats['en_attente'] - 100) }}
                </div>
            </div>

            <div class="metric-card card-red">
                <div class="metric-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="metric-value">{{ number_format($stats['en_retard']) }}</div>
                <div class="metric-label">En Retard</div>
                <div class="metric-trend trend-down">
                    <i class="fas fa-arrow-down"></i> Critique
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Évolution Mensuelle des Courriers</h3>
                        <p class="chart-subtitle">Tendances sur les 6 derniers mois</p>
                    </div>
                    <select style="padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option>6 derniers mois</option>
                        <option>12 derniers mois</option>
                        <option>Cette année</option>
                    </select>
                </div>
                <canvas id="monthlyChart" height="250"></canvas>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Répartition par Priorité</h3>
                        <p class="chart-subtitle">Distribution actuelle</p>
                    </div>
                </div>
                <canvas id="priorityChart" height="250"></canvas>
            </div>
        </div>

        <!-- Activities Section -->
        <div class="activities-section">
            <div class="activity-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Courriers avec Délais Expirés</h3>
                        <p class="chart-subtitle">Éléments nécessitant une attention immédiate</p>
                    </div>
                </div>

                @php
                    // Filter only expired courriers
                    $expiredCourriers = $activityData['critical_items']->filter(function($courrier) {
                        return $courrier->delais && $courrier->delais < now();
                    });
                @endphp

                @foreach($activityData['critical_items'] as $courrier)
                    <div class="courrier-item">
                        <div class="courrier-priority priority-{{ strtolower(str_replace(' ', '_', $courrier->priorite ?? 'normal')) }}"></div>
                        <div class="courrier-content">
                            <div class="courrier-ref">
                                C{{ date('Y') }}-{{ str_pad($courrier->id, 4, '0', STR_PAD_LEFT) }}
                                @if($courrier->priorite === 'urgent')
                                    - <span style="color: #ef4444;">URGENT</span>
                                @elseif($courrier->priorite === 'confidentiel')
                                    - <span style="color: #8b5cf6;">CONFIDENTIEL</span>
                                @endif
                                <span class="expired-label">DÉLAIS EXPIRÉ</span>
                            </div>
                            <div class="courrier-subject">{{ Str::limit($courrier->objet, 80) }}</div>
                            <div class="courrier-meta">
                                <span><i class="fas fa-user"></i> {{ $courrier->expediteur->nom ?? 'N/A' }}</span>
                                <span><i class="fas fa-calendar"></i> Échéance: {{ $courrier->delais ? $courrier->delais->format('d/m/Y') : 'Non définie' }}</span>
                                <span><i class="fas fa-clock"></i> Reçu {{ $courrier->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                    <div class="mt-4">
                        {{ $activityData['critical_items']->links() }}
                    </div>



                @if($expiredCourriers->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-check-circle text-4xl mb-4 text-green-500"></i>
                        <p>Aucun courrier avec délais expiré</p>
                    </div>
                @endif
            </div>

            <div class="activity-card">

                <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                    <h4 style="font-weight: 600; margin-bottom: 15px; color: #374151;">Activité Aujourd'hui</h4>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; text-align: center;">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #10b981;">{{ $activityData['today_activity']['received'] }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Reçus</div>
                        </div>
                        <div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #3b82f6;">{{ $activityData['today_activity']['processed'] }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Traités</div>
                        </div>
                        <div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #8b5cf6;">{{ $activityData['today_activity']['completed'] }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Clôturés</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Overview -->
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Vue d'Ensemble des Statuts</h3>
                    <p class="chart-subtitle">Distribution actuelle par état de traitement</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-outline" onclick="exportReport()">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                    <button class="btn btn-primary" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i> Actualiser
                    </button>
                </div>
            </div>
            <canvas id="statusChart" height="150"></canvas>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Data from Laravel backend
        const monthlyData = @json($temporalData['monthly'] ?? []);
        const priorityData = @json($distributionStats['priority'] ?? []);
        const statusData = @json($distributionStats['status'] ?? []);

        // Update timestamp
        function updateTimestamp() {
            const now = new Date();
            document.getElementById('lastUpdate').textContent = now.toLocaleTimeString('fr-FR');
        }
        updateTimestamp();
        setInterval(updateTimestamp, 30000);

        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyLabels = monthlyData.map(item => {
            const date = new Date(item.year, item.month - 1);
            return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
        });

        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels.length ? monthlyLabels : ['Avr 2024', 'Mai 2024', 'Juin 2024', 'Juil 2024', 'Août 2024', 'Sept 2024'],
                datasets: [{
                    label: 'Total Courriers',
                    data: monthlyData.length ? monthlyData.map(item => item.total) : [420, 389, 445, 467, 492, 523],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Urgents',
                    data: monthlyData.length ? monthlyData.map(item => item.urgent) : [45, 38, 52, 61, 48, 67],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Clôturés',
                    data: monthlyData.length ? monthlyData.map(item => item.completed) : [398, 367, 421, 442, 476, 498],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Priority Chart
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');
        new Chart(priorityCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(priorityData).length ? Object.keys(priorityData) : ['Normal', 'Urgent', 'Confidentiel', 'Réponse Obligatoire'],
                datasets: [{
                    data: Object.keys(priorityData).length ? Object.values(priorityData) : [1456, 234, 89, 167],
                    backgroundColor: ['#10b981', '#ef4444', '#8b5cf6', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true }
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(statusData).length ? Object.keys(statusData).map(key => key.replace('_', ' ').toUpperCase()) : ['En Attente', 'En Cours', 'En Traitement', 'Clôturé', 'Archivé'],
                datasets: [{
                    label: 'Nombre de Courriers',
                    data: Object.keys(statusData).length ? Object.values(statusData) : [156, 289, 445, 1834, 123],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#6b7280'],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Functions for interactions
        function exportReport() {
            window.open('{{ route("cab.export") }}', '_blank');
        }

        function refreshData() {
            fetch('{{ route("cab.realtime-stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update dashboard with new data
                    location.reload(); // Simple refresh for now
                });
        }

        // Real-time updates
        setInterval(function() {
            fetch('{{ route("cab.realtime-stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update critical indicators
                    console.log('Dashboard updated', data);
                });
        }, 60000); // Update every minute
    </script>
    @endpush
</x-app-layout>