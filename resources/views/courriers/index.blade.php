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
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->fichier_scan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->type_courrier }}</td>                                             
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->objet }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->date_reception }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $courrier->expediteur->nom ?? '-' }}</td>
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
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Aucun courrier trouvé.</td>
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