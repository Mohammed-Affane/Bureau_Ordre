<x-app-layout>
    <div class="py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">Liste des courriers</h1>
                            <p class="text-sm text-gray-500 mt-1">Tous les courriers enregistrés dans le bureau d'ordre</p>
                        </div>
                        <a href="{{ route('courriers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Nouveau courrier</a>
                    </div>

                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                               <tr>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence Arrivée</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence BO</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence Visa</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence Décision</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence Départ</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Départ</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Enregistrement</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nbr Pièces</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fichier Scan</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Réception</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recepteurs</th>

    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entite Expediteur</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent en charge</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
    @forelse($courriers as $courrier)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->reference_arrive }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->reference_bo }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->reference_visa }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->reference_dec }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->reference_depart }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->statut }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->date_depart }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->date_enregistrement }}</td>
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
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->type_courrier }}</td>                                             
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->objet }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->date_reception }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->expediteur->nom ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
               @foreach ($courrier->courrierDestinatairePivot as $dest)
               @if ($dest->entite)
                    • {{ $dest->entite->nom }}<br>
                @else
                    <em>{{$dest->nom}}</em><br>
                @endif
            @endforeach


            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->entiteExpediteur->nom ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->agent->name ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($courrier->priorite) }}</td>
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
</x-app-layout>