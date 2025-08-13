<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Détails du courrier</h1>
                        <p class="mt-1 text-gray-600">Référence: {{ $courrier->reference_arrive ?? 'N/A' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Retour
                        </a>
                        
                        @if ((auth()->user()->hasRole('bo') && $courrier->statut==='en_attente')
                        ||
                        (auth()->user()->hasRole('cab') && $courrier->statut==='en_cours')
                        ||
                        (auth()->user()->hasRole('sg') && $courrier->statut==='en_traitements')
                        ||
                        (auth()->user()->hasRole('chef_division') && $courrier->statut==='arriver')
                        ||
                        (auth()->user()->hasRole('dai') && $courrier->statut==='en_traitements')
                        )
                         <a href="{{ route('courriers.edit', $courrier) }}" class="px-4 py-2 border border-transparent rounded-md text-white bg-blue-600 hover:bg-blue-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Modifier
                        </a>
                            
                        @endif
                       
                    </div>
                </div>
            </div>

            <!-- Status Bar -->
            <div class="mb-8 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-lg font-medium">Statut:</span>
                        <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium 
                            {{ $courrier->statut === 'traité' ? 'bg-green-100 text-green-800' : 
                               ($courrier->statut === 'en cours' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-gray-100 text-gray-800') }}">
                            {{ $courrier->statut ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-lg font-medium">Priorité:</span>
                        <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium 
                            {{ $courrier->priorite === 'haute' ? 'bg-red-100 text-red-800' : 
                               ($courrier->priorite === 'moyenne' ? 'bg-orange-100 text-orange-800' : 
                               'bg-blue-100 text-blue-800') }}">
                            {{ $courrier->priorite ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informations principales</h3>
                </div>
                
                <!-- Two Column Layout -->
                <div class="px-6 py-5 grid grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- References -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">Références</h4>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Arrivée:</dt>
                                    <dd class="font-medium">{{ $courrier->reference_arrive ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Départ:</dt>
                                    <dd class="font-medium">{{ $courrier->reference_depart ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Bureau d'ordre:</dt>
                                    <dd class="font-medium">{{ $courrier->reference_bo ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Décision:</dt>
                                    <dd class="font-medium">{{ $courrier->reference_dec ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Visa:</dt>
                                    <dd class="font-medium">{{ $courrier->reference_visa ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Dates -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">Dates</h4>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Réception:</dt>
                                    <dd class="font-medium">{{ $courrier->date_reception ? \Carbon\Carbon::parse($courrier->date_reception)->format('d/m/Y') : '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Départ:</dt>
                                    <dd class="font-medium">{{ $courrier->date_depart ? \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') : '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Enregistrement:</dt>
                                    <dd class="font-medium">{{ $courrier->date_enregistrement ? \Carbon\Carbon::parse($courrier->date_enregistrement)->format('d/m/Y') : '-' }}</dd>
                                </div>

                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Delais:</dt>
                                    <dd class="font-medium">{{ $courrier->delais ? \Carbon\Carbon::parse($courrier->delais)->format('d/m/Y') : '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Correspondents -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">Correspondants</h4>
                            <div class="space-y-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">Expéditeur</h5>
                                    <p class="mt-1 font-medium">{{ $courrier->expediteur->nom ?? '-' }}</p>
                                    <p class="text-gray-600">{{ $courrier->entiteExpediteur->nom ?? '-' }}</p>
                                </div>

                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">Destinataires</h5>
                                    @if($courrier->courrierDestinatairePivot->count() > 0)
                                       <a href="{{ route('courriers.destinataires', $courrier->id) }}"
class="text-blue-600 visited:text-purple-600 ...">
   Voir les destinataires
</a>
                                    @else
                                        <p class="mt-1 text-gray-500">-</p>
                                    @endif
                                </div>

                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">Agent en charge</h5>
                                    <p class="mt-1 font-medium">{{ $courrier->agent->name ?? '-' }}</p>
                                </div>

                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">Affecter Par/A qui</h5>
                                    <p class="mt-1 font-medium"> <a href="{{ route('courriers.affecte', $courrier->id) }}"
   class="text-blue-600 visited:text-purple-600 ...">
   Voir les Affectations
</a></p>
                                </div>
                            </div>
                        </div>

                        <!-- Other Info -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">Autres informations</h4>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Type de courrier:</dt>
                                    <dd class="font-medium">{{ $courrier->type_courrier ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Nombre de pièces:</dt>
                                    <dd class="font-medium">{{ $courrier->Nbr_piece ?? '-' }}</dd>
                                </div>


                                
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Object Section -->
                <div class="px-6 py-5 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Objet</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-gray-800">{{ $courrier->objet ?? '-' }}</p>
                    </div>
                </div>

                <!-- Attachment Section -->
                <div class="px-6 py-5 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Fichier joint</h3>
                    @if($courrier->fichier_scan)
                        @php
                            // Get the file extension
                            $fileExtension = pathinfo($courrier->fichier_scan, PATHINFO_EXTENSION);
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'];
                            
                            // Generate the storage URL using Laravel's storage system
                            $fileUrl = Storage::url($courrier->fichier_scan);
                            $filePath = storage_path('app/public/' . $courrier->fichier_scan);
                        @endphp

                        <div class="flex items-center space-x-4">
                            @if(in_array(strtolower($fileExtension), $imageExtensions))
                                <div class="w-32 h-32 flex-shrink-0 border border-gray-200 rounded-md overflow-hidden">
                                    <img src="{{ $fileUrl }}" 
                                         alt="Fichier Scan" 
                                         class="w-full h-full object-contain cursor-pointer"
                                         onclick="window.open('{{ $fileUrl }}', '_blank')">
                                </div>
                            @endif

                            <div>
                                <p class="font-medium">{{ $courrier->fichier_scan }}</p>
                                <p class="text-sm text-gray-500">{{ strtoupper($fileExtension) }} file</p>
                                <div class="mt-2">
                                    <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-green-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
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