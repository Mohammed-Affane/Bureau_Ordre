<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-semibold text-gray-900">Détails de l'entité</h1>
                                <p class="text-sm text-gray-500 mt-1">Afficher les informations de l'entité</p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.entites.edit', $entite) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Modifier
                                </a>
                                <a href="{{ route('admin.entites.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Nom</h3>
                            <div class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md">
                                {{ $entite->nom }}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Type</h3>
                            <div class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md">
                                {{ $entite->type }}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Code</h3>
                            <div class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md">
                                {{ $entite->code }}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Entité parente</h3>
                            <div class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md">
                                {{ $entite->parent ? $entite->parent->nom : 'Aucune' }}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Responsable</h3>
                            <div class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md">
                                {{ $entite->responsable ? ($entite->responsable->nom_complet ?? $entite->responsable->name) : 'Aucun' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>