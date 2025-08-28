<x-app-layout>
    <x-slot name="title">Affectations du Courrier</x-slot>
    
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Professional Header Section -->
            <div class="mb-8">
                <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200/50 p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="bg-gradient-to-br from-slate-700 to-slate-900 p-4 rounded-2xl shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold text-slate-900 tracking-tight">
                                    Affectations du Courrier
                                </h1>
                                <p class="text-slate-600 mt-2 text-lg font-medium">{{ $courrier->objet }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <div class="px-4 py-2 bg-slate-100 rounded-lg border border-slate-200">
                                <span class="text-sm font-medium text-slate-700">Référence:</span>
                                <span class="text-sm font-bold text-slate-900 ml-1">#{{ $courrier->id ?? 'DOC-001' }}</span>
                            </div>
                            <div class="text-sm text-slate-500">
                                {{ now()->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($courrier->affectations->count() > 0)
                <!-- Professional Metrics Dashboard -->
                <div class="mb-8">
                    <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200/50 p-6">
                        <div class="grid grid-cols-4 gap-6">
                            <div class="text-center p-4 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200">
                                <div class="text-3xl font-bold text-slate-900 mb-1">{{ $courrier->affectations->count() }}</div>
                                <div class="text-sm font-medium text-slate-600">Total Affectations</div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    // Group affectations by the person who assigned them
                    $groupedAffectations = $courrier->affectations->groupBy(function($affectation) {
                        return $affectation->affectePar->id ?? 'unknown';
                    });
                @endphp

                <!-- Professional Affectations Cards -->
                <div class="space-y-8">
                    @foreach($groupedAffectations as $assignerId => $affectations)
                        @php
                            $assigner = $affectations->first()->affectePar;
                            $isEven = $loop->iteration % 2 == 0;
                        @endphp
                        
                        <div class="group">
                            <!-- Enterprise Card Container -->
                            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200/50 overflow-hidden hover:shadow-2xl transition-all duration-500">
                                
                                <!-- Professional Group Header -->
                                <div class="bg-gradient-to-r {{ $isEven ? 'from-slate-50 to-slate-100' : 'from-blue-50 to-indigo-50' }} border-b border-slate-200 p-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-gradient-to-br {{ $isEven ? 'from-slate-600 to-slate-800' : 'from-blue-600 to-indigo-700' }} p-4 rounded-xl shadow-lg">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-slate-900">
                                                    {{ $assigner->name ?? 'Utilisateur Inconnu' }}
                                                </h3>
                                                <p class="text-slate-600 font-medium text-lg">Superviseur des Affectations</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Professional Count Badge -->
                                        <div class="flex items-center space-x-4">
                                            <div class="px-6 py-3 bg-white rounded-xl border border-slate-200 shadow-sm">
                                                <div class="text-center">
                                                    <div class="text-2xl font-bold text-slate-900">{{ $affectations->count() }}</div>
                                                    <div class="text-sm text-slate-600 font-medium">Affectation{{ $affectations->count() > 1 ? 's' : '' }}</div>
                                                </div>
                                            </div>
                                            <div class="w-3 h-3 {{ $isEven ? 'bg-slate-400' : 'bg-blue-400' }} rounded-full animate-pulse"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enterprise Assignees Grid -->
                                <div class="p-6">
                                    <div class="space-y-6">
                                        @foreach($affectations as $affectation)
                                            <div class="bg-white rounded-xl border border-slate-200 hover:border-slate-300 transition-all duration-300 hover:shadow-lg overflow-hidden">
                                                <div class="p-6">
                                                    <!-- Professional Assignee Header -->
                                                    <div class="flex items-start justify-between mb-6">
                                                        <div class="flex items-center space-x-4">
                                                            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-3 rounded-xl shadow-lg">
                                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <h4 class="text-xl font-bold text-slate-900">
                                                                    {{ $affectation->affecteA->name ?? 'N/A' }}
                                                                </h4>
                                                                <p class="text-slate-600 text-sm font-medium">Responsable Assigné</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Professional Status & Date -->
                                                        <div class="flex items-center space-x-4">
                                                            @php
                                                                $statusConfig = [
                                                                    'en_attente' => [
                                                                        'bg' => 'bg-gradient-to-r from-amber-50 to-amber-100',
                                                                        'text' => 'text-amber-800',
                                                                        'border' => 'border-amber-200',
                                                                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                                                                    ],
                                                                    'en_cours' => [
                                                                        'bg' => 'bg-gradient-to-r from-blue-50 to-blue-100',
                                                                        'text' => 'text-blue-800',
                                                                        'border' => 'border-blue-200',
                                                                        'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'
                                                                    ],
                                                                    'termine' => [
                                                                        'bg' => 'bg-gradient-to-r from-green-50 to-green-100',
                                                                        'text' => 'text-green-800',
                                                                        'border' => 'border-green-200',
                                                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                                                    ]
                                                                ];
                                                                $config = $statusConfig[$affectation->statut_affectation] ?? $statusConfig['en_attente'];
                                                            @endphp
                                                            
                                                            <div class="inline-flex items-center {{ $config['bg'] }} {{ $config['border'] }} border rounded-xl px-4 py-2">
                                                                <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-1.5 rounded-lg mr-3">
                                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                                                                    </svg>
                                                                </div>
                                                                <span class="{{ $config['text'] }} font-bold text-sm">
                                                                    {{ ucfirst(str_replace('_', ' ', $affectation->statut_affectation)) }}
                                                                </span>
                                                            </div>

                                                            <div class="text-right">
                                                                <div class="flex items-center space-x-2 text-slate-500">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <div>
                                                                        <div class="text-sm font-bold text-slate-900">
                                                                            {{ \Carbon\Carbon::parse($affectation->date_affectation)->format('d/m/Y') }}
                                                                        </div>
                                                                        <div class="text-xs text-slate-500">
                                                                            {{ \Carbon\Carbon::parse($affectation->date_affectation)->format('h:m') }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Professional Instruction Card -->
                                                    <div class="mb-6">
                                                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-5 border-l-4 {{ $isEven ? 'border-slate-400' : 'border-blue-400' }} border border-slate-200">
                                                            <div class="flex items-start space-x-4">
                                                                <div class="bg-gradient-to-br from-slate-600 to-slate-700 p-2 rounded-lg mt-1">
                                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                </div>
                                                                <div class="flex-1">
                                                                    <h5 class="text-sm font-bold text-slate-800 mb-2">Instructions d'Exécution</h5>
                                                                    <p class="text-slate-700 font-medium leading-relaxed">
                                                                        {{ $affectation->Instruction }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Professional Divider -->
                                                    <div class="border-t border-slate-200 my-4"></div>

                                                    <!-- Professional Action Area -->
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-4">
                                                            <div class="px-3 py-1 bg-slate-100 rounded-lg border border-slate-200">
                                                                <span class="text-xs font-medium text-slate-600">ID: {{ $affectation->id ?? 'N/A' }}</span>
                                                            </div>
                                                            <div class="text-sm text-slate-500">
                                                                <span class="font-medium">Priorité:</span>
                                                                <span class="ml-1 font-bold text-slate-700">Normale</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Professional Empty State -->
                <div class="text-center py-20">
                    <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200/50 p-12 max-w-2xl mx-auto">
                        <div class="mx-auto h-32 w-32 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mb-8">
                            <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-slate-900 mb-4">Aucune Affectation Disponible</h3>
                        <p class="text-slate-600 text-lg mb-8 leading-relaxed">
                            Ce document n'a pas encore été assigné. Les affectations apparaîtront dans cette interface professionnelle une fois créées par les responsables autorisés.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .backdrop-blur-xl {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .group:hover .animate-float {
            animation-play-state: paused;
        }
        
        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
</x-app-layout>