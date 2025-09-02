<x-app-layout>
    <div class="py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">Liste des courriers Arrive DAI</h1>
                            <p class="text-sm text-gray-500 mt-1">Tous les courriers enregistrés dans le bureau d'ordre</p>
                        </div>
                         {{-- Export Dropdown --}}

                            <div class="flex space-x-3">
                                  <!-- Export Dropdown -->
                                  <div x-data="{ open: false }" class="relative inline-block text-left">
                                      <div>
                                          <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="export-menu" aria-expanded="false" aria-haspopup="true">
                                              <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                  <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                              </svg>
                                              Exporter
                                          </button>
                                      </div>
                                  
                                      <!-- Dropdown panel -->
                                      <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="export-menu">
                                          <div class="py-1" role="none">
                                              <!-- Export to Excel -->
                                              <a href="{{ route('export.courriers.excel', ['type' => 'arrive']) . '?' . http_build_query(request()->query()) }}" 
                                                 class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                  <svg class="mr-3 h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                  </svg>
                                                  Excel
                                              </a> 
                                              <a target="_blank" href="{{ route('export.courriers.pdf', ['type' => 'arrive']) . '?' . http_build_query(request()->query()) }}" 
                                                 class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                  <svg class="mr-3 h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                  </svg>
                                                  PDF
                                              </a>
                                          </div>
                                      </div>
                                  </div>
                        @can('create', App\Models\Courrier::class)
                        <a href="{{ route('courriers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Nouveau courrier</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>


                     <!-- Search and Filter Section -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('dai.courriers.arrive') }}">
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
                                        <option value="en_traitement" {{ request('statut') == 'en_traitement' ? 'selected' : '' }}>En traitement</option>
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
                            
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                    Filtrer
                                </button>
                                <a href="{{ route('dai.courriers.arrive') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Réinitialiser
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Courriers Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de réception</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($courriers as $courrier)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $courrier->reference_arrive }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($courrier->objet, 50) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $courrier->expediteur->nom ?? 'Non spécifié' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $courrier->date_reception ? $courrier->date_reception->format('d/m/Y') : 'Non spécifiée' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @switch($courrier->priorite)
                                                @case('urgent')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>
                                                    @break
                                                @case('confidentiel')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Confidentiel</span>
                                                    @break
                                                @case('A reponse obligatoire')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">À réponse obligatoire</span>
                                                    @break
                                                @default
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Normale</span>
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @switch($courrier->statut)
                                                @case('en_attente')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                                    @break
                                                @case('en_traitement')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">En traitement</span>
                                                    @break
                                                @case('cloture')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Clôturé</span>
                                                    @break
                                                @case('archiver')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Archivé</span>
                                                    @break
                                                @default
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $courrier->statut }}</span>
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('courriers.show', $courrier->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Voir</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Aucun courrier trouvé
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
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