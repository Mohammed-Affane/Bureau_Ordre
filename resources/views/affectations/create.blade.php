<x-app-layout>
    <x-slot name="title">Affectations du Courrier</x-slot>

    <div class="min-h-screen bg-slate-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 mb-4 shadow">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Affectation du Courrier</h1>
                <p class="text-gray-500 mt-2">Courrier n°{{ $courrier->id }}</p>
            </div>

            <!-- Error -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0
                            11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0
                            00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-700 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Two columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- LEFT: Courrier info -->
                <div class="bg-blue-50/70 border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-blue-100/70 border-b-2 border-blue-200">
                        <h2 class="text-lg font-bold text-slate-800 tracking-wide">Informations du Courrier</h2>
                    </div>

                    <div class="p-6 space-y-3">
                        <!-- Row helper style: bold label + border separation -->
                        <div class="flex items-start justify-between py-2 border-b border-slate-200">
                            <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Type</span>
                            <span class="text-slate-900 capitalize">{{ $courrier->type_courrier }}</span>
                        </div>

                        @if($courrier->type_courrier == 'arrive' || $courrier->type_courrier == 'visa')
                            @if($courrier->reference_arrive)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Référence Arrivée</span>
                                    <span class="text-slate-900">{{ $courrier->reference_arrive }}</span>
                                </div>
                            @endif
                            @if($courrier->reference_bo)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Référence BO</span>
                                    <span class="text-slate-900">{{ $courrier->reference_bo }}</span>
                                </div>
                            @endif
                            @if($courrier->date_reception)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Date de Réception</span>
                                    <span class="text-slate-900">
                                        {{ \Carbon\Carbon::parse($courrier->date_reception)->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif
                        @endif

                        @if($courrier->type_courrier == 'depart' || $courrier->type_courrier == 'interne')
                            @if($courrier->reference_depart)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Référence Départ</span>
                                    <span class="text-slate-900">{{ $courrier->reference_depart }}</span>
                                </div>
                            @endif
                            @if($courrier->date_depart)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Date de Départ</span>
                                    <span class="text-slate-900">
                                        {{ \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif
                        @endif

                        @if($courrier->type_courrier == 'visa')
                            @if($courrier->reference_visa)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Référence Visa</span>
                                    <span class="text-slate-900">{{ $courrier->reference_visa }}</span>
                                </div>
                            @endif
                            @if($courrier->date_depart)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Date de Départ</span>
                                    <span class="text-slate-900">
                                        {{ \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif
                        @endif

                        @if($courrier->type_courrier == 'decision')
                            @if($courrier->reference_dec)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Référence Décision</span>
                                    <span class="text-slate-900">{{ $courrier->reference_dec }}</span>
                                </div>
                            @endif
                            @if($courrier->date_depart)
                                <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                    <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Date de Départ</span>
                                    <span class="text-slate-900">
                                        {{ \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif
                        @endif

                        <div class="py-2 border-b border-slate-200">
                            <span class="block text-sm font-extrabold text-slate-700 uppercase tracking-wide mb-1">Objet</span>
                            <p class="text-slate-900 leading-relaxed break-words">{{ $courrier->objet }}</p>
                        </div>

                        @if($courrier->priorite)
                            <div class="flex items-center justify-between py-2 border-b border-slate-200">
                                <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Priorité</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($courrier->priorite == 'urgent') bg-red-100 text-red-800
                                    @elseif($courrier->priorite == 'confidentiel') bg-purple-100 text-purple-800
                                    @elseif($courrier->priorite == 'A reponse obligatoire') bg-orange-100 text-orange-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $courrier->priorite }}
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between py-2 border-b border-slate-200">
                            <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Statut</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($courrier->statut == 'en_attente') bg-gray-100 text-gray-800
                                @elseif($courrier->statut == 'en_cours') bg-blue-100 text-blue-800
                                @elseif($courrier->statut == 'arriver') bg-green-100 text-green-800
                                @elseif($courrier->statut == 'cloture') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ str_replace('_', ' ', $courrier->statut) }}
                            </span>
                        </div>

                        @if($courrier->Nbr_piece)
                            <div class="flex items-start justify-between py-2 border-b border-slate-200">
                                <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Nombre de Pièces</span>
                                <span class="text-slate-900">{{ $courrier->Nbr_piece }}</span>
                            </div>
                        @endif

                        @if($courrier->date_enregistrement)
                            <div class="flex items-start justify-between py-2">
                                <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">Date d'Enregistrement</span>
                                <span class="text-slate-900">
                                    {{ \Carbon\Carbon::parse($courrier->date_enregistrement)->format('d/m/Y') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- RIGHT: Affectation form -->
                <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-indigo-100/70 border-b-2 border-indigo-200">
                        <h2 class="text-lg font-bold text-slate-800 tracking-wide">Formulaire d'Affectation</h2>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('affectations.store', $courrier->id) }}" class="space-y-6">
                            @csrf

                            <!-- User selection (KEEP original name + classes) -->
                            <div>
                                <label for="id_affecte_a_utilisateur" class="block text-sm font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                                    Affecter à
                                </label>
                                <select name="id_affecte_a_utilisateur[]" id="id_affecte_a_utilisateur" multiple
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-gray-300 shadow-sm appearance-none cursor-pointer select2-multiple">
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->roles()->first()->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Instruction CAB (KEEP original flag + name) -->
                            @if($showCabInstruction)
                                <div>
                                    <label for="instruction_cab" class="block text-sm font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                                        Instruction CAB
                                    </label>
                                    <textarea name="instruction_cab" id="instruction_cab" rows="3"
                                              class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-gray-300 shadow-sm resize-none"
                                              placeholder="Saisir l'instruction CAB..."></textarea>
                                </div>
                            @endif

                            <!-- Instruction SG (KEEP original flag + name) -->
                            @if($showSgInstruction)
                                <div>
                                    <label for="instruction_sg" class="block text-sm font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                                        Instruction SG
                                    </label>
                                    <textarea name="instruction_sg" id="instruction_sg" rows="3"
                                              class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-gray-300 shadow-sm resize-none"
                                              placeholder="Saisir l'instruction SG..."></textarea>
                                </div>
                            @endif

                            <!-- Submit -->
                            <div class="pt-2">
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.01] transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                                    Affecter le Courrier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-gray-500 text-sm">
                <p>Système de gestion de courriers • Affectation sécurisée</p>
            </div>
        </div>
    </div>
</x-app-layout>
