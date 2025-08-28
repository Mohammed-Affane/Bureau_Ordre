<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-indigo-600 p-6">
                    <h1 class="text-2xl font-semibold text-white">Traitement du Courrier</h1>
                    <p class="text-indigo-200 mt-1">Référence: <span class="font-medium">{{ $affectation->courrier->reference_arrive }}</span></p>
                </div>

                <!-- Courrier Details Table -->
                <div class="p-6">
                    <table class="w-full text-left table-auto border-collapse">
                        <tbody class="divide-y divide-gray-200">
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-sm font-medium text-gray-600">Objet</th>
                                <td class="px-4 py-2 text-gray-700">{{ $affectation->courrier->objet }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-sm font-medium text-gray-600">Expéditeur</th>
                                <td class="px-4 py-2 text-gray-700">{{ $affectation->courrier->expediteur->name ?? '-' }}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-sm font-medium text-gray-600">Date d'arrivée</th>
                                <td class="px-4 py-2 text-gray-700">{{ $affectation->courrier->date_arrive ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-sm font-medium text-gray-600">Priorité</th>
                                <td class="px-4 py-2 text-gray-700">{{ ucfirst($affectation->courrier->priorite) ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Form Section -->
                <div class="p-6 border-t border-gray-200">
                    <form method="POST" action="{{ route('division.affectations.traitement.store', $affectation) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="action" class="block text-sm font-medium text-gray-700">Actions</label>
                            <textarea name="action" id="action" rows="4" placeholder="Ajouter vos actions ici..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('action') }}</textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('division.courriers.arrive') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
