<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Détails du courrier</h1>
                    <div class="flex items-center mt-2">
                                                <p class="text-gray-600 mr-4">
                            Référence:
                            {{
                                match($courrier->type_courrier) {
                                    'arrive' => $courrier->reference_arrive ?? '',
                                    'depart' => $courrier->reference_depart ?? '',
                                    'interne' => $courrier->reference_depart ?? '',
                                    'visa' => $courrier->reference_visa ?? '',
                                    'decision' => $courrier->reference_dec ?? '',
                                    default => '',
                                }
                            }}
                        </p>

                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $courrier->type_courrier === 'arrive' ? 'bg-blue-100 text-blue-800' : 
                               ($courrier->type_courrier === 'depart' ? 'bg-green-100 text-green-800' : 
                               ($courrier->type_courrier === 'visa' ? 'bg-purple-100 text-purple-800' : 
                               ($courrier->type_courrier === 'decision' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-gray-100 text-gray-800'))) }}">
                            {{ ucfirst($courrier->type_courrier) }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <!-- Retour -->
                    <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Retour
                    </a>

                    <!-- Modifier button (conditional) -->
                    @if (
                        (auth()->user()->hasRole('bo') && $courrier->statut==='en_attente') ||
                        (auth()->user()->hasRole('cab') && $courrier->statut==='en_cours') ||
                        (auth()->user()->hasRole('sg') && $courrier->statut==='en_traitement') ||
                        (auth()->user()->hasRole('dai') && $courrier->statut==='en_traitement')
                    )
                        <a href="{{ route('courriers.edit', $courrier) }}" class="px-4 py-2 rounded-lg text-white bg-blue-600 hover:bg-blue-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Modifier
                        </a>
                    @endif
                </div>
            </div>

            <!-- Statut & Priorité Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center">
                        <div class="rounded-full p-3 bg-blue-50 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Statut</h3>
                            <span class="text-lg font-semibold 
                                {{ $courrier->statut === 'traité' ? 'text-green-600' : 
                                   ($courrier->statut === 'en_cours' ? 'text-yellow-600' : 
                                   'text-yellow-600') }}">
                                {{ ucfirst($courrier->statut ?? '-') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center">
                        <div class="rounded-full p-3 
                            {{ $courrier->priorite === 'haute' ? 'bg-red-50' : 
                               ($courrier->priorite === 'moyenne' ? 'bg-orange-50' : 
                               'bg-blue-50') }} mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 
                                {{ $courrier->priorite === 'haute' ? 'text-red-600' : 
                                   ($courrier->priorite === 'moyenne' ? 'text-orange-600' : 
                                   'text-blue-600') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Priorité</h3>
                            <span class="text-lg font-semibold 
                                {{ $courrier->priorite === 'haute' ? 'text-red-600' : 
                                   ($courrier->priorite === 'moyenne' ? 'text-orange-600' : 
                                   'text-blue-600') }}">
                                {{ ucfirst($courrier->priorite ?? '-') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Informations principales</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 py-6">
                    
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Références -->
                        @if(in_array($courrier->type_courrier, ['arrive','depart','decision','interne','visa']))
                        <div>
                            <h4 class="text-md font-bold text-gray-800 mb-3">Références</h4>
                            <dl class="space-y-3">
                                @if($courrier->type_courrier === 'arrive' || $courrier->type_courrier === 'visa')
                                <div class="flex justify-between"><dt class="text-gray-600">Arrivée :</dt><dd class="text-gray-900 font-medium">{{ $courrier->reference_arrive ?? '-' }}</dd></div>
                                @endif
                                
                                @if($courrier->type_courrier === 'depart' || $courrier->type_courrier === 'interne')
                                <div class="flex justify-between"><dt class="text-gray-600">Départ :</dt><dd class="text-gray-900 font-medium">{{ $courrier->reference_depart ?? '-' }}</dd></div>
                                @endif
                                @if($courrier->type_courrier === 'arrive')
                                <div class="flex justify-between"><dt class="text-gray-600">Bureau d'ordre :</dt><dd class="text-gray-900 font-medium">{{ $courrier->reference_bo ?? '-' }}</dd></div>
                                @endif
                                
                                @if($courrier->type_courrier === 'decision')
                                <div class="flex justify-between"><dt class="text-gray-600">Décision :</dt><dd class="text-gray-900 font-medium">{{ $courrier->reference_dec ?? '-' }}</dd></div>
                                @endif
                                
                                @if($courrier->type_courrier === 'visa')
                                <div class="flex justify-between"><dt class="text-gray-600">Visa :</dt><dd class="text-gray-900 font-medium">{{ $courrier->reference_visa ?? '-' }}</dd></div>
                                @endif
                            </dl>
                        </div>
                        @endif

                        <!-- Dates -->
                        <div>
                            <h4 class="text-md font-bold text-gray-800 mb-3">Dates</h4>
                            <dl class="space-y-3">
                                @if($courrier->type_courrier === 'arrive' || $courrier->type_courrier === 'visa')
                                <div class="flex justify-between"><dt class="text-gray-600">Réception :</dt><dd class="text-gray-900 font-medium">{{ $courrier->date_reception ? \Carbon\Carbon::parse($courrier->date_reception)->format('d/m/Y') : '-' }}</dd></div>
                                @endif
                                
                                @if($courrier->type_courrier === 'depart' || $courrier->type_courrier === 'interne')
                                <div class="flex justify-between"><dt class="text-gray-600">Départ :</dt><dd class="text-gray-900 font-medium">{{ $courrier->date_depart ? \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') : '-' }}</dd></div>
                                @endif
                                
                                <div class="flex justify-between"><dt class="text-gray-600">Enregistrement :</dt><dd class="text-gray-900 font-medium">{{ $courrier->date_enregistrement ? \Carbon\Carbon::parse($courrier->date_enregistrement)->format('d/m/Y') : '-' }}</dd></div>
                                
                                <div class="flex justify-between"><dt class="text-gray-600">Délais :</dt><dd class="text-gray-900 font-medium">{{ $courrier->delais ? \Carbon\Carbon::parse($courrier->delais)->format('d/m/Y') : '-' }}</dd></div>
                               
                            </dl>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Correspondents -->
                        <div>
                            <h4 class="text-md font-bold text-gray-800 mb-3">Correspondants</h4>
                            <dl class="space-y-3">

                             @if($courrier->type_courrier == 'arrive' || $courrier->type_courrier == 'visa')
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Expediteur :</dt>
                                    <dd class="text-gray-900 font-medium">{{ $courrier->expediteur->nom ?? '-' }}</dd>
                                </div>
                                @else
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Entité expéditrice :</dt>
                                    <dd class="text-gray-900 font-medium">{{ $courrier->entiteExpediteur->nom ?? '-' }}</dd>
                                </div>

                                @endif
                                
                                
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Agent en charge :</dt>
                                    <dd class="text-gray-900 font-medium">{{ $courrier->agent->name ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Affectations :</dt>
                                    <dd><a href="{{ route('courriers.affecte', $courrier->id) }}" class="text-blue-600 hover:underline font-medium">Voir les affectations</a></dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Other Info -->
                        <div>
                            <h4 class="text-md font-bold text-gray-800 mb-3">Autres informations</h4>
                            <dl class="space-y-3">
                                <div class="flex justify-between"><dt class="text-gray-600">Type :</dt><dd class="text-gray-900 font-medium">{{ ucfirst($courrier->type_courrier ?? '-') }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-600">Nombre de pièces :</dt><dd class="text-gray-900 font-medium">{{ $courrier->Nbr_piece ?? '-' }}</dd></div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Objet -->
                <div class="px-6 py-5 border-t border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">Objet</h4>
                    <div class="bg-gray-50 p-4 rounded-md text-gray-900">
                        {{ $courrier->objet ?? '-' }}
                    </div>
                </div>

                <!-- Attachment -->
                <div class="px-6 py-5 border-t border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">Fichier joint</h4>
                    @if($courrier->fichier_scan)
                        @php
                            $fileExtension = pathinfo($courrier->fichier_scan, PATHINFO_EXTENSION);
                            $imageExtensions = ['jpg','jpeg','png','gif','bmp','tiff','webp'];
                            $fileUrl = Storage::url($courrier->fichier_scan);
                        @endphp

                        <div class="flex items-center space-x-4">
                            @if(in_array(strtolower($fileExtension), $imageExtensions))
                                <div class="w-32 h-32 border border-gray-200 rounded-md overflow-hidden">
                                    <img src="{{ $fileUrl }}" alt="Fichier Scan" class="w-full h-full object-contain cursor-pointer" onclick="window.open('{{ $fileUrl }}', '_blank')">
                                </div>
                            @endif

                            <div>
                                <p class="font-medium">{{ $courrier->fichier_scan }}</p>
                                <p class="text-sm text-gray-500">{{ strtoupper($fileExtension) }} file</p>
                                <a href="{{ $fileUrl }}" target="_blank" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-green-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Aucun fichier joint</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>