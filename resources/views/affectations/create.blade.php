<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">
                    Affectation de Courrier
                </h2>
                <p class="text-gray-600 text-lg">Courrier n°{{ $courrier->id }}</p>
            </div>

            <!-- Error Alert -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm animate-pulse">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-700 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Courrier Information Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h3 class="text-white font-semibold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Informations du Courrier
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Type de Courrier -->
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                <span class="text-sm font-semibold text-gray-600">Type</span>
                            </div>
                            <span class="text-lg font-medium text-gray-800 capitalize">{{ $courrier->type_courrier }}</span>
                        </div>

                        <!-- Références conditionnelles -->
                        @if($courrier->type_courrier == 'arrive')
                            @if($courrier->reference_arrive)
                                <div class="bg-green-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Référence Arrivée</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ $courrier->reference_arrive }}</span>
                                </div>
                            @endif
                            @if($courrier->reference_bo)
                                <div class="bg-orange-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Référence BO</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ $courrier->reference_bo }}</span>
                                </div>
                            @endif
                            @if($courrier->date_reception)
                                <div class="bg-blue-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Date de Réception</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ \Carbon\Carbon::parse($courrier->date_reception)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        @endif

                        @if($courrier->type_courrier == 'depart')
                            @if($courrier->reference_depart)
                                <div class="bg-red-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Référence Départ</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ $courrier->reference_depart }}</span>
                                </div>
                            @endif
                            @if($courrier->date_depart)
                                <div class="bg-purple-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Date de Départ</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        @endif

                        @if($courrier->type_courrier == 'visa')
                            @if($courrier->reference_visa)
                                <div class="bg-indigo-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-indigo-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Référence Visa</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ $courrier->reference_visa }}</span>
                                </div>
                            @endif
                            @if($courrier->date_depart)
                                <div class="bg-purple-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Date de Départ</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        @endif

                        @if($courrier->type_courrier == 'decision')
                            @if($courrier->reference_dec)
                                <div class="bg-teal-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-teal-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Référence Décision</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ $courrier->reference_dec }}</span>
                                </div>
                            @endif
                            @if($courrier->date_depart)
                                <div class="bg-purple-50 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-gray-600">Date de Départ</span>
                                    </div>
                                    <span class="text-lg font-medium text-gray-800">{{ \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        @endif

                        <!-- Objet du courrier -->
                        <div class="bg-gray-50 p-4 rounded-xl md:col-span-2">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                <span class="text-sm font-semibold text-gray-600">Objet</span>
                            </div>
                            <p class="text-gray-800 leading-relaxed">{{ $courrier->objet }}</p>
                        </div>

                        <!-- Priorité -->
                        @if($courrier->priorite)
                            <div class="bg-yellow-50 p-4 rounded-xl">
                                <div class="flex items-center mb-2">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-semibold text-gray-600">Priorité</span>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($courrier->priorite == 'urgent') bg-red-100 text-red-800
                                    @elseif($courrier->priorite == 'confidentiel') bg-purple-100 text-purple-800
                                    @elseif($courrier->priorite == 'A reponse obligatoire') bg-orange-100 text-orange-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $courrier->priorite }}
                                </span>
                            </div>
                        @endif

                        <!-- Statut -->
                        <div class="bg-blue-50 p-4 rounded-xl">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                <span class="text-sm font-semibold text-gray-600">Statut</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($courrier->statut == 'en_attente') bg-gray-100 text-gray-800
                                @elseif($courrier->statut == 'en_cours') bg-blue-100 text-blue-800
                                @elseif($courrier->statut == 'arriver') bg-green-100 text-green-800
                                @elseif($courrier->statut == 'cloture') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ str_replace('_', ' ', $courrier->statut) }}
                            </span>
                        </div>

                        <!-- Nombre de pièces -->
                        @if($courrier->Nbr_piece)
                            <div class="bg-cyan-50 p-4 rounded-xl">
                                <div class="flex items-center mb-2">
                                    <div class="w-2 h-2 bg-cyan-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-semibold text-gray-600">Nombre de Pièces</span>
                                </div>
                                <span class="text-lg font-medium text-gray-800">{{ $courrier->Nbr_piece }}</span>
                            </div>
                        @endif

                        <!-- Date d'enregistrement -->
                        @if($courrier->date_enregistrement)
                            <div class="bg-emerald-50 p-4 rounded-xl">
                                <div class="flex items-center mb-2">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-semibold text-gray-600">Date d'Enregistrement</span>
                                </div>
                                <span class="text-lg font-medium text-gray-800">{{ \Carbon\Carbon::parse($courrier->date_enregistrement)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <h3 class="text-white font-semibold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Formulaire d'Affectation
                    </h3>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ route('affectations.store', $courrier->id) }}" class="space-y-6">
                        @csrf
                        
                        <!-- User Selection -->
                        <div class="group">
                            <label for="id_affecte_a_utilisateur" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-blue-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Affecter à
                                </span>
                            </label>
                            <div class="relative">
                                <select name="id_affecte_a_utilisateur" 
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-all duration-200 hover:border-gray-300 shadow-sm appearance-none cursor-pointer">
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->roles()->first()->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- CAB Instruction -->
                        @if($showCabInstruction)
                            <div class="group transform transition-all duration-300 hover:scale-[1.01]">
                                <label for="instruction_cab" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-blue-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Instruction CAB
                                    </span>
                                </label>
                                <div class="relative">
                                    <textarea name="instruction_cab" 
                                              id="instruction_cab" 
                                              rows="3"
                                              placeholder="Saisir l'instruction CAB..."
                                              class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-all duration-200 hover:border-gray-300 shadow-sm resize-none"></textarea>
                                    <div class="absolute bottom-2 right-2 opacity-0 group-focus-within:opacity-100 transition-opacity duration-200">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- SG Instruction -->
                        @if($showSgInstruction)
                            <div class="group transform transition-all duration-300 hover:scale-[1.01]">
                                <label for="instruction_sg" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-blue-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Instruction SG
                                    </span>
                                </label>
                                <div class="relative">
                                    <textarea name="instruction_sg" 
                                              id="instruction_sg" 
                                              rows="3"
                                              placeholder="Saisir l'instruction SG..."
                                              class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-all duration-200 hover:border-gray-300 shadow-sm resize-none"></textarea>
                                    <div class="absolute bottom-2 right-2 opacity-0 group-focus-within:opacity-100 transition-opacity duration-200">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-500/30">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Affecter le Courrier
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="text-center mt-8 text-gray-500 text-sm">
                <p>Système de gestion de courriers • Affectation sécurisée</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Custom scrollbar for textareas */
        textarea::-webkit-scrollbar {
            width: 6px;
        }
        
        textarea::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        textarea::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        textarea::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</x-app-layout>