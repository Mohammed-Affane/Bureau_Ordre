<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- En-tête avec bouton retour et actions -->
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Détails du courrier
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Informations complètes sur le courrier
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Retour
                        </a>
                        <a href="{{ route('courriers.edit', $courrier) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Modifier
                        </a>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                        <!-- Section gauche - Informations de base -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Informations générales
                            </h4>

                            <div class="space-y-4">
                                
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Référence Arrivée
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->reference_arrive ?? '-' }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Référence Depart
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->reference_depart ?? '-' }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Référence Bureau D'ordre
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->reference_bo ?? '-' }}
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Référence Decision
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->reference_dec ?? '-' }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Référence Visa
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->reference_visa ?? '-' }}
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Type de courrier
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->type_courrier ?? '-' }}
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Statut
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $courrier->statut === 'traité' ? 'bg-green-100 text-green-800' : 
                                               ($courrier->statut === 'en cours' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-gray-100 text-gray-800') }}">
                                            {{ $courrier->statut ?? '-' }}
                                        </span>
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Priorité
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $courrier->priorite === 'haute' ? 'bg-red-100 text-red-800' : 
                                               ($courrier->priorite === 'moyenne' ? 'bg-orange-100 text-orange-800' : 
                                               'bg-blue-100 text-blue-800') }}">
                                            {{ $courrier->priorite ?? '-' }}
                                        </span>
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Date de réception
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->date_reception ? \Carbon\Carbon::parse($courrier->date_reception)->format('d/m/Y') : '-' }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Date de depart
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->date_depart ? \Carbon\Carbon::parse($courrier->date_depart)->format('d/m/Y') : '-' }}
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Date d'enregistrement
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->date_enregistrement ? \Carbon\Carbon::parse($courrier->date_enregistrement)->format('d/m/Y') : '-' }}
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Nombre de pièces
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->Nbr_piece ?? '-' }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <!-- Section droite - Expéditeur/Destinataire -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Correspondance
                            </h4>

                            <div class="space-y-4">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Expéditeur
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->expediteur->nom ?? '-' }}
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Entité expéditeur
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->entiteExpediteur->nom ?? '-' }}
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Destinataires
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if($courrier->courrierDestinatairePivot->count() > 0)
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach ($courrier->courrierDestinatairePivot as $dest)
                                                    <li>
                                                        @if ($dest->entite)
                                                            {{ $dest->entite->nom }}
                                                        @else
                                                            <em>{{ $dest->nom }}</em>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>

                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Agent en charge
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $courrier->agent->name ?? '-' }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section objet -->
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Objet
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-gray-800">{{ $courrier->objet ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Section fichier joint -->
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Fichier joint
                        </h4>
                        @if($courrier->fichier_scan)
                            @php
                                $basePath = 'fichiers_scans_' . $courrier->type_courrier;
                                $fileUrl = asset($basePath . '/' . $courrier->fichier_scan);
                                $fileExtension = pathinfo($courrier->fichier_scan, PATHINFO_EXTENSION);
                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'];
                            @endphp

                            <div class="flex items-center space-x-4">
                                @if(in_array(strtolower($fileExtension), $imageExtensions))
                                    <div class="w-32 h-32 flex-shrink-0">
                                        <img src="{{ $fileUrl }}" 
                                             alt="Fichier Scan" 
                                             class="w-full h-full object-contain rounded-md cursor-pointer"
                                             onclick="window.open('{{ $fileUrl }}', '_blank')">
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $courrier->fichier_scan }}</p>
                                    <p class="text-sm text-gray-500">{{ strtoupper($fileExtension) }} file</p>
                                    <div class="mt-2">
                                        <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    </div>
</x-app-layout>