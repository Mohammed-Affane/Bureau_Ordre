<x-app-layout>
    <x-slot name="title">Cabinet du Gouverneur - Tableau de Bord Exécutif</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Cabinet', 'url' => route('cab.dashboard')]]
    </x-slot>

    @push('styles')
    <style>
        .kpi-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .kpi-card:hover {
            transform: translateY(-5px);
        }
        .kpi-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .kpi-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .kpi-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .kpi-card.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            height: 400px;
        }
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .alert-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .alert-badge.urgent {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .alert-badge.retard {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
    @endpush

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="kpi-card primary">
            <div class="text-sm font-semibold opacity-90 mb-2">Total Courriers</div>
            <div class="text-3xl font-bold">{{ number_format($totalCourriers) }}</div>
            <div class="text-xs opacity-75 mt-2">Tous statuts confondus</div>
        </div>

        <div class="kpi-card info">
            <div class="text-sm font-semibold opacity-90 mb-2">Reçus ce Mois</div>
            <div class="text-3xl font-bold">{{ number_format($courriersRecusCeMois) }}</div>
            <div class="text-xs opacity-75 mt-2">{{ Carbon\Carbon::now()->translatedFormat('F Y') }}</div>
        </div>

        <div class="kpi-card success">
            <div class="text-sm font-semibold opacity-90 mb-2">Courriers Avec Instruction Gouverneur</div>
            <div class="text-3xl font-bold">{{ number_format($courriersTraites) }}</div><!-- change this to  the number pof the courrier that have an instruction  --> 
            <div class="text-xs opacity-75 mt-2">Validés / Clôturés</div>
        </div>

        <div class="kpi-card warning">
            <div class="text-sm font-semibold opacity-90 mb-2">Urgents en Attente</div>
            <div class="text-3xl font-bold">{{ number_format($courriersUrgentsEnAttente) }}</div>
            <div class="text-xs opacity-75 mt-2">Action requise</div>
        </div>

        <div class="kpi-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="text-sm font-semibold opacity-90 mb-2">Taux de Traitement</div>
            <div class="text-3xl font-bold">{{ $tauxTraitement }}%</div>
            <div class="text-xs opacity-75 mt-2">Performance globale</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Pie Chart: Répartition par Statut -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Répartition des Courriers par Statut</h3>
            <canvas id="statutChart"></canvas>
        </div>

        <!-- Bar Chart: Courriers par Type -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Courriers par Type</h3>
            <canvas id="typeChart"></canvas>
        </div>

        <!-- Line Chart: Évolution Mensuelle -->
        <div class="chart-container" style="height: 450px;">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Évolution Mensuelle (12 mois)</h3>
            <canvas id="evolutionChart"></canvas>
        </div>

        <!-- Stacked Bar: Priorité -->
        <div class="chart-container" style="height: 450px;">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Répartition par Priorité</h3>
            <canvas id="prioriteChart"></canvas>
        </div>
    </div>

  <!-- Tables Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <!-- Top 5 Expéditeurs -->
    <div class="table-container bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Top 5 Expéditeurs</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Courriers Envoyés</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topExpediteurs as $expediteur)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $expediteur->nom }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ ucfirst($expediteur->type_source ?? 'N/A') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-semibold text-blue-600">
                            {{ number_format($expediteur->total_courriers) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">
                            Aucune donnée disponible
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top 5 Courrier Arriver Avec Instruction -->
    <div class="table-container bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Top 5 Courriers arrives Avec Instruction</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence Arrivée</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence BO</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nbr_piece</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">priorite</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date Courrier</th>
                         <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Arriver</th>
                        
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topCourrierInstructs as $CourrierInstructs)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $CourrierInstructs->reference_arrive }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ ucfirst($CourrierInstructs->reference_bo ?? 'N/A') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ ucfirst($CourrierInstructs->Nbr_piece ?? 'N/A') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ ucfirst($CourrierInstructs->priorite ?? 'N/A') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ ucfirst($CourrierInstructs->statut ?? 'N/A') }}
                        </td>
                         <td class="px-4 py-3 whitespace-nowrap text-sm text-center font-semibold text-blue-600">
                            {{ \Carbon\Carbon::parse($CourrierInstructs->date_reception)->format('d-m-Y') }}

                        </td>
            
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center font-semibold text-blue-600">
                            {{ \Carbon\Carbon::parse($CourrierInstructs->date_enregistrement)->format('d-m-Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">
                            Aucune donnée disponible
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!--  add here les courrier avec une instruction de cab dans cette semaine -->
    <!-- Alerts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Courriers Urgents en Attente > 7 jours -->
        <div class="table-container">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Courriers  Urgents (>7 jours)</h3>
                <span class="alert-badge urgent">{{ $alertesUrgents->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Référence</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Expéditeur</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Jours</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($alertesUrgents as $alerte)
                        <tr class="hover:bg-red-50 cursor-pointer" onclick="window.location.href='{{ route('courriers.show', $alerte->id) }}'">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-red-700">
                                {{ $alerte->reference_arrivee ?? $alerte->reference_bo ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $alerte->expediteur->nom ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-red-600">
                                {{ number_format(Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($alerte->delais))) }} jours
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-sm text-green-600">
                                ✓ Aucune alerte urgente
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4" class="bg-green p-4 rounded-md shadow-md"> {{ $alertesUrgents->links() }}</div>
                
            </div>
        </div>

        <!-- Courriers en Attente > 30 jours -->
        <div class="table-container">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Courriers en Retard (>20 jours)</h3>
                <span class="alert-badge retard">{{ $alertesRetard->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-yellow-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Référence</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Expéditeur</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Jours</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($alertesRetard as $alerte)
                        <tr class="hover:bg-yellow-50 cursor-pointer" onclick="window.location.href='{{ route('courriers.show', $alerte->id) }}'">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-yellow-800">
                                {{ $alerte->reference_arrivee ?? $alerte->reference_bo ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $alerte->expediteur->nom ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-yellow-700">
                               {{ number_format(Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($alerte->delais))) }} jours
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-sm text-green-600">
                                ✓ Aucun courrier en retard
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
               <div class="mt-4" class="bg-green p-4 rounded-md shadow-md"> {{ $alertesRetard->links() }}</div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">


    // Backend data
    const statutData = @json($repartitionStatut);
    console.log(statutData);
    // Define all possible statuses (MUST match DB values)
    const allStatuses = ['en_attente','en_cours','en_traitement','cloture','arriver','archive'];

    // Fill missing statuses with 0
    const filledStatutData = {};
    allStatuses.forEach(s => {
        filledStatutData[s] = statutData[s] || 0;
    });

    // Create chart
    new Chart(document.getElementById('statutChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(filledStatutData).map(s => s.replace('_', ' ').toUpperCase()),
            datasets: [{
                data: Object.values(filledStatutData),
                backgroundColor: [
                    '#667eea', '#764ba2', '#f093fb', '#f5576c',
                    '#11998e', '#38ef7d', '#4facfe', '#00f2fe'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const dataset = context.dataset.data;
                            const total = dataset.reduce((a, b) => a + b, 0);
                            const value = context.raw;
                            const percentage = ((value / total) * 100).toFixed(2) + "%";
                            return context.label + ": " + value + " (" + percentage + ")";
                        }
                    }
                }
            }
        }
    });

        // Bar Chart: Courriers par Type
        const typeData = @json($courriersParType);
        new Chart(document.getElementById('typeChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(typeData).map(t => t.toUpperCase()),
                datasets: [{
                    label: 'Nombre de Courriers',
                    data: Object.values(typeData),
                    backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#11998e', '#38ef7d']
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
                        beginAtZero: true
                    }
                }
            }
        });

        // Line Chart: Évolution Mensuelle
        const evolutionData = @json($evolutionMensuelle);
        new Chart(document.getElementById('evolutionChart'), {
            type: 'line',
            data: {
                labels: evolutionData.map(e => e.month),
                datasets: [
                    {
                        label: 'Courriers Reçus',
                        data: evolutionData.map(e => e.recus),
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Courriers Traités',
                        data: evolutionData.map(e => e.traites),
                        borderColor: '#38ef7d',
                        backgroundColor: 'rgba(56, 239, 125, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Bar Chart: Priorité
        const prioriteData = @json($prioriteCourriers);
        new Chart(document.getElementById('prioriteChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(prioriteData).map(p => p.replace('_', ' ').toUpperCase()),
                datasets: [{
                    label: 'Nombre de Courriers',
                    data: Object.values(prioriteData),
                    backgroundColor: ['#4facfe', '#f5576c', '#f093fb', '#38ef7d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>