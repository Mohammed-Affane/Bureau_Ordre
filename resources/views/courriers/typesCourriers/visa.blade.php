<x-app-layout>
    <div class="py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">Liste des courriers Visa</h1>
                            <p class="text-sm text-gray-500 mt-1">Tous les courriers enregistrés dans le bureau d'ordre</p>
                        </div>
                        <a href="{{ route('courriers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Nouveau courrier</a>
                    </div>
                     <!-- Search and Filter Section -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('courriers.visa') }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- Search Input -->
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                                    <input type="text" name="search" id="search" 
                                           value="{{ request('search') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="Référence, Objet, Expéditeur...">
                                </div>
                                
                                <!-- Status Filter -->
                                <div>
                                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                                    <select name="statut" id="statut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Tous les statuts</option>
                                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="arriver" {{ request('statut') == 'arriver' ? 'selected' : '' }}>Arrivé</option>
                                        <option value="cloture" {{ request('statut') == 'cloture' ? 'selected' : '' }}>Clôturé</option>
                                        <option value="archiver" {{ request('statut') == 'archiver' ? 'selected' : '' }}>Archivé</option>
                                    </select>
                                </div>
                                
                                <!-- Priority Filter -->
                                <div>
                                    <label for="priorite" class="block text-sm font-medium text-gray-700">Priorité</label>
                                    <select name="priorite" id="priorite" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Toutes les priorités</option>
                                        <option value="normale" {{ request('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                                        <option value="urgent" {{ request('priorite') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        <option value="confidentiel" {{ request('priorite') == 'confidentiel' ? 'selected' : '' }}>Confidentiel</option>
                                        <option value="A reponse obligatoire" {{ request('priorite') == 'A reponse obligatoire' ? 'selected' : '' }}>À réponse obligatoire</option>
                                    </select>
                                </div>
                                
                                <!-- Date Range Filter -->
                                <div>
                                    <label for="date_range" class="block text-sm font-medium text-gray-700">Période</label>
                                    <select name="date_range" id="date_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Toutes les dates</option>
                                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Ce mois</option>
                                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>Cette année</option>
                                        <option value="custom" {{ request('date_from') || request('date_to') ? 'selected' : '' }}>Personnalisée</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Custom Date Range (hidden by default) -->
                            <div id="custom_date_range" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4" style="{{ !(request('date_from') || request('date_to')) ? 'display: none;' : '' }}">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700">Du</label>
                                    <input type="date" name="date_from" id="date_from" 
                                           value="{{ request('date_from') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700">Au</label>
                                    <input type="date" name="date_to" id="date_to" 
                                           value="{{ request('date_to') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-end space-x-3">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg>
                                    Filtrer
                                </button>
                                <a href="{{ route('courriers.visa') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Réinitialiser
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Results Count -->
                    @if(request()->hasAny(['search', 'statut', 'priorite', 'date_range', 'date_from', 'date_to']))
                    <div class="mb-4 text-sm text-gray-500">
                        Résultats filtrés : {{ $courriers->total() }} courrier(s) trouvé(s)
                    </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                               <tr>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence Arrivée</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence Visa</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Enregistrement</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nbr Pièces</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fichier Scan</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Réception</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recepteurs</th>

    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent en charge</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
    @forelse($courriers as $courrier)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->reference_arrive }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->reference_visa }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                @php
                    $statusClasses = [
                        'en_attente' => 'bg-yellow-100 text-yellow-800',
                        'en_cours'   => 'bg-blue-100 text-blue-800',
                        'arriver'    => 'bg-green-100 text-green-800',
                        'cloture'    => 'bg-gray-100 text-gray-800',
                        'archiver'   => 'bg-purple-100 text-purple-800',
                    ];
                @endphp

                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$courrier->statut] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $courrier->statut)) }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->date_enregistrement->format('d/m/Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->Nbr_piece }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($courrier->fichier_scan)
                    @php
                        $basePath = 'fichiers_scans_' . $courrier->type_courrier;
                        $filePath = public_path($basePath . '/' . $courrier->fichier_scan);
                        $fileUrl = asset($basePath . '/' . $courrier->fichier_scan);
                        $fileExtension = pathinfo($courrier->fichier_scan, PATHINFO_EXTENSION);
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'];
                    @endphp
                    @if(file_exists($filePath))
                        @if(in_array(strtolower($fileExtension), $imageExtensions))
                            <!-- Display image -->
                            <div class="relative group">
                                <img src="{{ $fileUrl }}" 
                                     alt="Fichier Scan" 
                                     class="w-10 h-10 object-cover rounded cursor-pointer hover:scale-110 transition-transform"
                                     onclick="openImageModal('{{ $fileUrl }}', '{{ $courrier->fichier_scan }}')">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded transition-all"></div>
                            </div>
                        @elseif(strtolower($fileExtension) === 'pdf')
                            <!-- Display PDF icon -->
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded cursor-pointer hover:bg-red-200 transition-colors"
                                 onclick="window.open('{{ $fileUrl }}', '_blank')">
                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z"/>
                                </svg>
                            </div>
                        @else
                            <!-- Display generic file icon -->
                            <div class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded cursor-pointer hover:bg-gray-200 transition-colors"
                                 onclick="window.open('{{ $fileUrl }}', '_blank')">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        @endif
                    @else
                        <!-- File not found -->
                        <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    @endif
                @else
                    <!-- No file -->
                    <span class="text-gray-400 text-sm">-</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->objet }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->date_reception->format('d/m/Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->expediteur->nom ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <a href="{{ route('courriers.destinataires', $courrier->id) }}"
   class="text-blue-600 hover:text-blue-800 underline">
   Voir les destinataires
</a>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->agent->name ?? '-' }}</td>

            <td class="px-6 py-4 whitespace-nowrap">
            @php
            $prioriteClasses = [
                        'normale' => 'bg-gray-100 text-gray-800',
                        'urgent' => 'bg-red-100 text-red-800',
                        'confidentiel' => 'bg-indigo-100 text-indigo-800',
                        'A reponse obligatoire' => 'bg-orange-100 text-orange-800',
                    ];
            @endphp
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $prioriteClasses[$courrier->priorite] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $courrier->priorite)) }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="{{ route('courriers.show', $courrier) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Voir</a>
                <a href="{{ route('courriers.edit', $courrier) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                <form action="{{ route('courriers.destroy', $courrier) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?')">Supprimer</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="18" class="px-6 py-4 text-center text-gray-500">Aucun courrier trouvé.</td>
        </tr>
    @endforelse
</tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $courriers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        // Show/hide custom date range based on selection
        document.getElementById('date_range').addEventListener('change', function() {
            const customRange = document.getElementById('custom_date_range');
            if (this.value === 'custom') {
                customRange.style.display = 'grid';
            } else {
                customRange.style.display = 'none';
                document.getElementById('date_from').value = '';
                document.getElementById('date_to').value = '';
            }
        });

        // Initialize date pickers if using flatpickr
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#date_from", { dateFormat: "Y-m-d" });
            flatpickr("#date_to", { dateFormat: "Y-m-d" });
        }
    </script>
    @endpush
</x-app-layout>