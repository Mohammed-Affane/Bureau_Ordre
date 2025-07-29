<x-app-layout>
<div class="py-8">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Traitement du Courrier</h1>
                    <p class="text-sm text-gray-500 mt-1">Référence: {{ $affectation->courrier->reference_arrive }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Objet:</h3>
                    <p class="mt-1 text-gray-600">{{ $affectation->courrier->objet }}</p>
                </div>

                <form method="POST" action="{{ route('division.affectations.traitement.store', $affectation) }}">
                    @csrf


                    <div class="mb-4">
                        <label for="commentaire" class="block text-sm font-medium text-gray-700">Actions</label>
                        <textarea name="commentaire" id="commentaire" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('commentaire') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <div class="mt-1 space-y-2">
                            <div class="flex items-center">
                                <input id="brouillon" name="statut" type="radio" value="brouillon" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" {{ old('statut', 'brouillon') == 'brouillon' ? 'checked' : '' }}>
                                <label for="brouillon" class="ml-2 block text-sm text-gray-700">Enregistrer en brouillon</label>
                            </div>
                            <div class="flex items-center">
                                <input id="valide" name="statut" type="radio" value="validé" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" {{ old('statut') == 'validé' ? 'checked' : '' }}>
                                <label for="valide" class="ml-2 block text-sm text-gray-700">Valider et envoyer</label>
                            </div>
                        </div>
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

