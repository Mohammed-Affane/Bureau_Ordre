<x-app-layout>
    <x-slot name="title">Affectations du Courrier</x-slot>
    
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 p-8">
                    <div class="flex items-center space-x-6">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 rounded-2xl shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                                Affectations du Courrier
                            </h1>
                            <p class="text-gray-600 mt-2 text-lg font-medium">{{ $courrier->objet }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($courrier->affectations->count() > 0)
                @php
                    // Group affectations by the person who assigned them
                    $groupedAffectations = $courrier->affectations->groupBy(function($affectation) {
                        return $affectation->affectePar->id ?? 'unknown';
                    });
                @endphp

                <!-- Affectations Cards -->
                <div class="space-y-6">
                    @foreach($groupedAffectations as $assignerId => $affectations)
                        @php
                            $assigner = $affectations->first()->affectePar;
                            $isEven = $loop->iteration % 2 == 0;
                            
                        @endphp
                        
                        <div class="group">
                            <!-- Card Container -->
                            <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 p-8 hover:shadow-3xl transition-all duration-500 transform hover:scale-[1.02]">
                                
                                <!-- Decorative Elements -->
                                <div class="absolute top-0 right-0 w-32 h-32 {{ $isEven ? 'bg-gradient-to-br from-blue-100/30 to-indigo-200/30' : 'bg-gradient-to-br from-gray-100/30 to-blue-100/30' }} rounded-full blur-3xl"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 {{ $isEven ? 'bg-gradient-to-tr from-indigo-100/30 to-blue-100/30' : 'bg-gradient-to-tr from-blue-100/30 to-gray-100/30' }} rounded-full blur-2xl"></div>
                                
                                <div class="relative">
                                    <!-- Assigner Header -->
                                    <div class="flex items-center justify-between mb-8">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-gradient-to-r {{ $isEven ? 'from-blue-500 to-indigo-600' : 'from-gray-600 to-blue-600' }} p-4 rounded-2xl shadow-lg">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-800">
                                                    {{ $assigner->name ?? 'Utilisateur Inconnu' }}
                                                </h3>
                                                <p class="text-gray-600 font-medium">Affectées par</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Affectations Count Badge -->
                                        <div class="bg-gradient-to-r {{ $isEven ? 'from-blue-50 to-indigo-50' : 'from-gray-50 to-blue-50' }} border border-gray-200 rounded-2xl px-6 py-3">
                                            <div class="text-center">
                                                <div class="text-3xl font-bold text-gray-800">{{ $affectations->count() }}</div>
                                                <div class="text-sm text-gray-600">Affectation{{ $affectations->count() > 1 ? 's' : '' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assignees Grid -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        @foreach($affectations as $affectation)
                                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 hover:bg-white/90 transition-all duration-300 transform hover:scale-105 group/item shadow-lg">
                                                <!-- Assignee Info -->
                                                <div class="flex items-center space-x-4 mb-4">
                                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-3 rounded-xl shadow-lg group-hover/item:shadow-xl transition-all duration-300">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-xl font-bold text-gray-800">
                                                            {{ $affectation->affecteA->name ?? 'N/A' }}
                                                        </h4>
                                                        <p class="text-gray-500 text-sm">Assigné à</p>
                                                    </div>
                                                </div>

                                                <!-- Instruction -->
                                                <div class="mb-4">
                                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border-l-4 border-gradient-to-b {{ $isEven ? 'border-blue-400' : 'border-gray-400' }}">
                                                        <div class="flex items-start space-x-3">
                                                            <svg class="w-5 h-5 text-gray-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            <p class="text-gray-700 font-medium leading-relaxed">
                                                                {{ $affectation->Instruction }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Status and Date -->
                                                <div class="flex items-center justify-between">
                                                    <!-- Status Badge -->
                                                    <div>
                                                        @php
                                                            $statusConfig = [
                                                                'en_attente' => [
                                                                    'colors' => 'from-amber-400 to-orange-500',
                                                                    'bg' => 'from-amber-500/20 to-orange-500/20',
                                                                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                                                                ],
                                                                'en_cours' => [
                                                                    'colors' => 'from-blue-400 to-indigo-500',
                                                                    'bg' => 'from-blue-500/20 to-indigo-500/20',
                                                                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'
                                                                ],
                                                                'termine' => [
                                                                    'colors' => 'from-emerald-400 to-green-500',
                                                                    'bg' => 'from-emerald-500/20 to-green-500/20',
                                                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                                                ]
                                                            ];
                                                            $config = $statusConfig[$affectation->statut_affectation] ?? $statusConfig['en_attente'];
                                                        @endphp
                                                        
                                                        <div class="inline-flex items-center bg-gradient-to-r {{ $config['bg'] }} border border-gray-200 rounded-xl px-4 py-2">
                                                            <div class="bg-gradient-to-r {{ $config['colors'] }} p-1.5 rounded-lg mr-3">
                                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-gray-800 font-bold text-sm">
                                                                {{ ucfirst(str_replace('_', ' ', $affectation->statut_affectation)) }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Date -->
                                                    <div class="text-right">
                                                        <div class="flex items-center space-x-2 text-gray-500">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-4h8v4z"></path>
                                                            </svg>
                                                            <div>
                                                                <div class="text-sm font-bold text-gray-800">
                                                                    {{ \Carbon\Carbon::parse($affectation->date_affectation)->format('d/m/Y') }}
                                                                </div>
                                                                <div class="text-xs text-gray-500">
                                                                    {{ \Carbon\Carbon::parse($affectation->date_affectation)->format('H:i') }}
                                                                </div>
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
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 p-12 max-w-2xl mx-auto">
                        <div class="mx-auto h-32 w-32 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-8">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">Aucune affectation trouvée</h3>
                        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                            Ce courrier n'a pas encore été affecté à un utilisateur. Les affectations apparaîtront ici une fois créées avec un magnifique design en cartes.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div></div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .border-gradient-to-b {
            border-image: linear-gradient(to bottom, var(--tw-gradient-stops)) 1;
        }
    </style>
</x-app-layout>