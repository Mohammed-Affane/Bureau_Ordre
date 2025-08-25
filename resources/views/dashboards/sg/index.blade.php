<x-app-layout>
    <x-slot name="title">Dashboard SG</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Secrétariat Général', 'url' => route('sg.dashboard')]]
    </x-slot>

    <h2 class="text-2xl font-bold text-gray-900">Bienvenue Secrétariat Général</h2>
    <p class="mt-2 text-gray-600">Affectez les courriers et suivez les divisions.</p>

            <!-- Stats SG -->
            @php
            $sgStats = [
                ['title' => 'Courriers SG', 'value' => '15', 'icon' => 'document-text', 'color' => 'blue'],
                ['title' => 'Administratif', 'value' => '8', 'icon' => 'clipboard-document', 'color' => 'green'],
                ['title' => 'Coordination', 'value' => '5', 'icon' => 'users', 'color' => 'purple'],
                ['title' => 'Archivés', 'value' => '42', 'icon' => 'archive-box', 'color' => 'gray']
            ];
        @endphp
        @include('shared.stats-cards', ['stats' => $sgStats])

        <!-- Répartition des types de courriers -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Types de Courriers Traités</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $courierTypes = [
                        ['type' => 'Administratif', 'count' => 25, 'color' => 'blue', 'icon' => 'document-text'],
                        ['type' => 'RH', 'count' => 18, 'color' => 'green', 'icon' => 'users'],
                        ['type' => 'Financier', 'count' => 12, 'color' => 'yellow', 'icon' => 'currency-dollar'],
                        ['type' => 'Juridique', 'count' => 8, 'color' => 'purple', 'icon' => 'scale']
                    ];
                @endphp
                
                @foreach($courierTypes as $type)
                    <div class="text-center p-4 bg-{{ $type['color'] }}-50 rounded-lg">
                        <div class="mx-auto w-12 h-12 flex items-center justify-center bg-{{ $type['color'] }}-100 rounded-full mb-3">
                            @include('components.icons.' . $type['icon'], ['class' => 'w-6 h-6 text-' . $type['color'] . '-600'])
                        </div>
                        <div class="text-2xl font-bold text-{{ $type['color'] }}-600 mb-1">{{ $type['count'] }}</div>
                        <div class="text-sm text-{{ $type['color'] }}-800">{{ $type['type'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Courriers en cours et workflow -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Courriers en cours -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Courriers en Cours de Traitement</h3>
                <div class="space-y-4">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="text-sm font-medium text-gray-900">SG-2024-{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                            {{ $i <= 2 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $i <= 2 ? 'En cours' : 'Révision' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        {{ ['Note de service', 'Procédure administrative', 'Circulaire interne', 'Rapport mensuel'][$i-1] }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Assigné à: {{ ['M. Alami', 'Mme. Bennani', 'M. Cherif', 'Mme. Debbagh'][$i-1] }}
                                    </p>
                                    
                                    <!-- Progress bar -->
                                    <div class="mt-3">
                                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                                            <span>Progression</span>
                                            <span>{{ [25, 60, 80, 90][$i-1] }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ [25, 60, 80, 90][$i-1] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <button class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                        Suivre
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Workflow et validations -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Validations en Attente</h3>
                <div class="space-y-4">
                    <div class="border-l-4 border-red-400 bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Validation urgente requise
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>3 documents en attente de signature depuis plus de 48h</p>
                                </div>
                                <div class="mt-4">
                                    <button class="bg-red-100 px-3 py-1.5 rounded-md text-sm font-medium text-red-800 hover:bg-red-200">
                                        Traiter maintenant
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-l-4 border-yellow-400 bg-yellow-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Révision en cours
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>2 procédures nécessitent une révision avant validation</p>
                                </div>
                                <div class="mt-4">
                                    <button class="bg-yellow-100 px-3 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-200">
                                        Voir les détails
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-l-4 border-green-400 bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    Validation terminée
                                </h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>1 document validé</p>
                                </div>
                                <div class="mt-4">
                                    <button class="bg-green-100 px-3 py-1.5 rounded-md text-sm font-medium text-green-800 hover:bg-green-200">
                                        Voir les détails
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white shadow rounded-lg p-6 lg:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activité Récente</h3>
                <div class="space-y-4">
                    @php
                        $activities = [
                            ['user' => 'Mme. Bennani', 'action' => 'a soumis un nouveau courrier administratif', 'time' => 'Il y a 2 heures', 'icon' => 'document-plus', 'color' => 'blue'],
                            ['user' => 'M. Alami', 'action' => 'a validé la procédure RH #SG-2024-015', 'time' => 'Il y a 5 heures', 'icon' => 'check-circle', 'color' => 'green'],
                            ['user' => 'M. Cherif', 'action' => 'a demandé des modifications sur le rapport financier', 'time' => 'Il y a 1 jour', 'icon' => 'exclamation-circle', 'color' => 'yellow'],
                            ['user' => 'Mme. Debbagh', 'action' => 'a archivé 3 documents expirés', 'time' => 'Il y a 2 jours', 'icon' => 'archive-box-arrow-down', 'color' => 'purple'],
                            ['user' => 'M. El Fassi', 'action' => 'a créé une nouvelle circulaire interne', 'time' => 'Il y a 3 jours', 'icon' => 'document-text', 'color' => 'indigo']
                        ];
                    @endphp
                    
                    @foreach($activities as $activity)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="bg-{{ $activity['color'] }}-100 p-2 rounded-full">
                                    @include('components.icons.' . $activity['icon'], ['class' => 'w-5 h-5 text-' . $activity['color'] . '-600'])
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
                    @endforeach
                </div>
                <div class="mt-6">
                    <button class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Voir toute l'activité →
                    </button>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white shadow rounded-lg p-6">
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