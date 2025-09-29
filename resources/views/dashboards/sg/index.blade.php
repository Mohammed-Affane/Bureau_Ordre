<x-app-layout>
    <x-slot name="title">Secrétariat Général - Tableau de Bord</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Secrétariat Général', 'url' => route('sg.dashboard')]]
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
        
        .card-yellow { border-left-color: #eab308; }
        .card-yellow .metric-icon { background: linear-gradient(135deg, #eab308, #ca8a04); }
        .card-yellow .metric-value { color: #854d0e; }
        
        .card-gray { border-left-color: #6b7280; }
        .card-gray .metric-icon { background: linear-gradient(135deg, #6b7280, #4b5563); }
        .card-gray .metric-value { color: #374151; }

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
            <h1><i class="fas fa-building" style="color: #3b82f6; margin-right: 15px;"></i>Secrétariat Général</h1>
            <p>Tableau de Bord - Gestion des Courriers et Affectations</p>
            <div class="real-time-indicator">
                <i class="fas fa-circle pulse" style="font-size: 8px;"></i>
                Données en temps réel - Dernière mise à jour: <span id="lastUpdate">{{ now()->format('H:i:s') }}</span>
            </div>
        </div>

        <!-- Main Metrics Grid -->
        <div class="metrics-grid">
            <!-- Volume Metrics -->
            <div class="metric-card card-blue">
                <div class="metric-icon"><i class="fas fa-mail-bulk"></i></div>
                <div class="metric-value">{{ number_format($stats['total_courriers']) }}</div>
                <div class="metric-label">Total Courriers</div>
                <div class="metric-trend trend-stable">
                    <i class="fas fa-minus"></i> Suivi en cours
                </div>
            </div>

            <div class="metric-card card-green">
                <div class="metric-icon"><i class="fas fa-inbox"></i></div>
                <div class="metric-value">{{ number_format($stats['courriers_arrive']) }}</div>
                <div class="metric-label">Courriers Arrivés</div>
                <div class="metric-trend trend-stable">
                    <i class="fas fa-minus"></i> Suivi en cours
                </div>
            </div>

            <div class="metric-card card-purple">
                <div class="metric-icon"><i class="fas fa-paper-plane"></i></div>
                <div class="metric-value">{{ number_format($stats['courriers_depart']) }}</div>
                <div class="metric-label">Courriers Départ</div>
                <div class="metric-trend trend-stable">
                    <i class="fas fa-minus"></i> Suivi en cours
                </div>
            </div>

            <div class="metric-card card-indigo">
                <div class="metric-icon"><i class="fas fa-exchange-alt"></i></div>
                <div class="metric-value">{{ number_format($stats['courriers_interne']) }}</div>
                <div class="metric-label">Courriers Internes</div>
                <div class="metric-trend trend-stable">
                    <i class="fas fa-minus"></i> Suivi en cours
                </div>
            </div>

            <div class="metric-card card-yellow">
                <div class="metric-icon"><i class="fas fa-clock"></i></div>
                <div class="metric-value">{{ number_format($stats['en_attente']) }}</div>
                <div class="metric-label">En Attente</div>
                <div class="metric-trend trend-stable">
                    <i class="fas fa-minus"></i> À traiter
                </div>
            </div>

            <div class="metric-card card-blue">
                <div class="metric-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="metric-value">{{ number_format($stats['en_traitement']) }}</div>
                <div class="metric-label">En Traitement</div>
                <div class="metric-trend trend-stable">
                    <i class="fas fa-minus"></i> En cours
                </div>
            </div>

            <div class="metric-card card-red">
                <div class="metric-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="metric-value">{{ number_format($stats['retard']) }}</div>
                <div class="metric-label">En Retard</div>
                <div class="metric-trend trend-down">
                    <i class="fas fa-arrow-down"></i> Critique
                </div>
            </div>

            <div class="metric-card card-gray">
                <div class="metric-icon"><i class="fas fa-archive"></i></div>
                <div class="metric-value">0</div>
                <div class="metric-label">Archivés</div>
                <div class="metric-trend trend-stable">
                    <i class="fas fa-minus"></i> Stable
                </div>
            </div>
        </div>

        <!-- Activities Section -->
        <div class="activities-section">
            <!-- Courriers en cours -->
            <div class="activity-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Courriers en Cours de Traitement</h3>
                        <p class="chart-subtitle">Courriers nécessitant un suivi</p>
                    </div>
                </div>
                
                @forelse($stats['recent_courriers'] ?? [] as $courrier)
                    <div class="courrier-item">
                        <div class="courrier-priority priority-{{ strtolower(str_replace(' ', '_', $courrier->priorite ?? 'normal')) }}"></div>
                        <div class="courrier-content">
                            <div class="courrier-ref">
                                {{ $courrier->reference }}
                                @if($courrier->priorite === 'urgent')
                                    - <span style="color: #ef4444;">URGENT</span>
                                @elseif($courrier->priorite === 'confidentiel')
                                    - <span style="color: #8b5cf6;">CONFIDENTIEL</span>
                                @endif
                            </div>
                            <div class="courrier-subject">{{ Str::limit($courrier->objet, 80) }}</div>
                            <div class="courrier-meta">
                                <span><i class="fas fa-user"></i> {{ $courrier->expediteur->nom ?? 'N/A' }}</span>
                                <span><i class="fas fa-calendar"></i> Échéance: {{ $courrier->delais ? $courrier->delais->format('d/m/Y') : 'Non définie' }}</span>
                                <span><i class="fas fa-clock"></i> Reçu {{ $courrier->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="courrier-actions">
                            <a href="{{ route('sg.courriers.show', $courrier->id) }}" class="btn btn-primary">Suivre</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        Aucun courrier en cours de traitement
                    </div>
                @endforelse
            </div>
            
            <!-- Validations en attente -->
            <div class="activity-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Validations en Attente</h3>
                        <p class="chart-subtitle">Courriers nécessitant votre validation</p>
                    </div>
                </div>
                
                @forelse($stats['validations'] ?? [] as $validation)
                    <div class="courrier-item">
                        <div class="courrier-priority priority-{{ strtolower(str_replace(' ', '_', $validation->priorite ?? 'normal')) }}"></div>
                        <div class="courrier-content">
                            <div class="courrier-ref">
                                {{ $validation->reference }}
                                @if($validation->priorite === 'urgent')
                                    - <span style="color: #ef4444;">URGENT</span>
                                @endif
                            </div>
                            <div class="courrier-subject">{{ Str::limit($validation->objet, 80) }}</div>
                            <div class="courrier-meta">
                                <span><i class="fas fa-user"></i> De: {{ $validation->user->name ?? 'N/A' }}</span>
                                <span><i class="fas fa-clock"></i> {{ $validation->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="courrier-actions">
                            <a href="{{ route('sg.courriers.show', $validation->id) }}" class="btn btn-primary">Valider</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        Aucune validation en attente
                    </div>
                @endforelse
            </div>
        </div>


        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Évolution Mensuelle des Courriers</h3>
                        <p class="chart-subtitle">Tendances sur les derniers mois</p>
                    </div>
                    <select style="padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option>6 derniers mois</option>
                        <option>12 derniers mois</option>
                        <option>Cette année</option>
                    </select>
                </div>
                <canvas id="monthlyActivityChart" height="250"></canvas>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Répartition par Type</h3>
                        <p class="chart-subtitle">Distribution actuelle</p>
                    </div>
                </div>
                <canvas id="typeChart" height="250"></canvas>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Graphique d'activité mensuelle
                const ctxMonthly = document.getElementById('monthlyActivityChart').getContext('2d');
                const monthlyData = @json($monthlyStats);

                const labels = Object.keys(monthlyData);
                const arrive = labels.map(m => monthlyData[m].arrive);
                const depart = labels.map(m => monthlyData[m].depart);
                const interne = labels.map(m => monthlyData[m].interne);

                new Chart(ctxMonthly, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Courriers Arrivée',
                                data: arrive,
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16,185,129,0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Courriers Départ',
                                data: depart,
                                borderColor: '#6366F1',
                                backgroundColor: 'rgba(99,102,241,0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Courriers Internes',
                                data: interne,
                                borderColor: '#F59E0B',
                                backgroundColor: 'rgba(245,158,11,0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });
                
                // Graphique de répartition par type
                const ctxType = document.getElementById('typeChart').getContext('2d');
                
                // Utiliser les données existantes ou créer des données de démonstration
                const typeData = {
                    labels: ['Arrivée', 'Départ', 'Interne'],
                    datasets: [{
                        data: [
                            {{ $stats['courriers_arrive'] }}, 
                            {{ $stats['courriers_depart'] }}, 
                            {{ $stats['courriers_interne'] }}
                        ],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',  // vert
                            'rgba(99, 102, 241, 0.8)',  // indigo
                            'rgba(245, 158, 11, 0.8)'   // orange
                        ],
                        borderColor: [
                            'rgb(16, 185, 129)',
                            'rgb(99, 102, 241)',
                            'rgb(245, 158, 11)'
                        ],
                        borderWidth: 1
                    }]
                };
                
                new Chart(ctxType, {
                    type: 'doughnut',
                    data: typeData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        cutout: '70%'
                    }
                });
            });
        </script>
        @endpush

            <!-- Recent Activity -->
            <div class="bg-white shadow rounded-lg p-6 lg:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activité Récente</h3>
                <div class="space-y-4">
                    @forelse($stats['activities'] ?? [] as $activity)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="bg-{{ $activity['color'] }}-100 p-2 rounded-full">
                                    <x-dynamic-component :component="'icons.' . $activity['icon']" class="w-5 h-5 text-{{ $activity['color'] }}-600" />
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $activity['user'] }} <span class="font-normal text-gray-600">{{ $activity['action'] }}</span>
                                    </p>
                                    <span class="text-xs text-gray-500">{{ $activity['time'] }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            Aucune activité récente
                        </div>
                    @endforelse
                </div>
            </div>
                <!-- Calendrier des Échéances -->
<div class="bg-white shadow rounded-lg p-6 mt-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Calendrier des Échéances</h3>
    <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base font-medium text-gray-900">Juillet 2024</h4>
                        <div class="flex space-x-2">
                            <button type="button" class="p-1 rounded-md text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Previous</span>
                                @include('components.icons.chevron-left', ['class' => 'h-5 w-5'])
                            </button>
                            <button type="button" class="p-1 rounded-md text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Next</span>
                                @include('components.icons.chevron-right', ['class' => 'h-5 w-5'])
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-7 text-center text-xs leading-6 text-gray-500">
                        <div>L</div>
                        <div>M</div>
                        <div>M</div>
                        <div>J</div>
                        <div>V</div>
                        <div>S</div>
                        <div>D</div>
                    </div>
                    <div class="mt-2 grid grid-cols-7 text-sm">
                        @php
                            $days = [
                                [null, null, null, 1, 2, 3, 4],
                                [5, 6, 7, 8, 9, 10, 11],
                                [12, 13, 14, 15, 16, 17, 18],
                                [19, 20, 21, 22, 23, 24, 25],
                                [26, 27, 28, 29, 30, 31, null]
                            ];
                            $deadlines = [15 => 'Rapport mensuel', 22 => 'Réunion SG', 30 => 'Clôture comptable'];
                        @endphp
                        
                        @foreach($days as $week)
                            @foreach($week as $day)
                                <div class="py-1.5">
                                    @if($day)
                                        <button class="mx-auto flex h-8 w-8 items-center justify-center rounded-full 
                                            {{ array_key_exists($day, $deadlines) ? 'bg-red-100 text-red-600 font-medium' : 'text-gray-900 hover:bg-gray-100' }}">
                                            {{ $day }}
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-6 space-y-3">
                    @foreach($deadlines as $day => $deadline)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="bg-red-100 p-1 rounded-full">
                                    @include('components.icons.calendar', ['class' => 'w-4 h-4 text-red-600'])
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $deadline }}</p>
                                <p class="text-xs text-gray-500">{{ $day }} Juillet 2024</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions Rapides</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    @include('components.icons.document-plus', ['class' => 'w-8 h-8 text-indigo-600 mb-2'])
                    <span class="text-sm font-medium text-gray-900">Nouveau Courrier</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    @include('components.icons.user-plus', ['class' => 'w-8 h-8 text-indigo-600 mb-2'])
                    <span class="text-sm font-medium text-gray-900">Assigner un Agent</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    @include('components.icons.archive-box', ['class' => 'w-8 h-8 text-indigo-600 mb-2'])
                    <span class="text-sm font-medium text-gray-900">Archiver Documents</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    @include('components.icons.chart-bar', ['class' => 'w-8 h-8 text-indigo-600 mb-2'])
                    <span class="text-sm font-medium text-gray-900">Générer Rapport</span>
                </button>
            </div>
        </div>
    </div>

</x-app-layout>