<x-app-layout>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Creation de courrier</h2>
        </div>

        <!-- Form Content -->
        <div class="p-6">
            <div class="space-y-6">
                <!-- Type Selection -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de Courrier *</label>
                        <div class="flex rounded-md shadow-sm">
                            <button type="button" @click="type = 'arrive'" :class="type === 'arrive' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="flex-1 py-2 px-4 border border-gray-300 rounded-l-md text-sm font-medium focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                Arrivée
                            </button>
                            <button type="button" @click="type = 'depart'" :class="type === 'depart' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="flex-1 py-2 px-4 border border-gray-300 rounded-r-md text-sm font-medium focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                Départ
                            </button>
                        </div>
                    </div>

                    <!-- Reference BO -->
                    <div>
                        <label for="reference_BO" class="block text-sm font-medium text-gray-700 mb-1">Référence BO *</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" id="reference_BO" name="reference_BO" required class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md" placeholder="BO-2023-001">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conditional Reference Arrive -->
                <div x-show="type === 'arrive'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <label for="reference_arrive" class="block text-sm font-medium text-gray-700 mb-1">Référence Arrivée *</label>
                    <input type="text" id="reference_arrive" name="reference_arrive" class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Expediteur -->
                <div>
                    <label for="id_expediteur" class="block text-sm font-medium text-gray-700 mb-1">Expéditeur *</label>
                    <select id="id_expediteur" name="id_expediteur" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Sélectionnez un expéditeur</option>
                        @foreach($expediteurs as $expediteur)
                            <option value="{{ $expediteur->id }}" {{ old('id_expediteur') == $expediteur->id ? 'selected' : '' }}>{{ $expediteur->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Conditional Destinataires -->
                <div x-show="type === 'depart'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Destinataires *</label>
                    <div class="mt-1 space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($entites as $entite)
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="destinataire_{{ $entite->id }}" name="destinataires[]" type="checkbox" value="{{ $entite->id }}" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="destinataire_{{ $entite->id }}" class="font-medium text-gray-700">{{ $entite->nom }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Agent en charge -->
                <div>
                    <label for="id_agent_en_charge" class="block text-sm font-medium text-gray-700 mb-1">Agent en Charge *</label>
                    <select id="id_agent_en_charge" name="id_agent_en_charge" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Sélectionnez un agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('id_agent_en_charge') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date_reception" class="block text-sm font-medium text-gray-700 mb-1">Date Réception *</label>
                        <div class="relative">
                            <input type="date" id="date_reception" name="date_reception" required class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div>
                        <label for="date_enregistrement" class="block text-sm font-medium text-gray-700 mb-1">Date Enregistrement *</label>
                        <div class="relative">
                            <input type="date" id="date_enregistrement" name="date_enregistrement" required class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>

                <!-- Priority and Pieces -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="priorite" class="block text-sm font-medium text-gray-700 mb-1">Priorité *</label>
                        <select id="priorite" name="priorite" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                            <option value="très urgent">Très urgent</option>
                        </select>
                    </div>
                    <div>
                        <label for="Nbr_piece" class="block text-sm font-medium text-gray-700 mb-1">Nombre de Pièces *</label>
                        <input type="number" id="Nbr_piece" name="Nbr_piece" min="1" required class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Objet -->
                <div>
                    <label for="objet" class="block text-sm font-medium text-gray-700 mb-1">Objet *</label>
                    <textarea id="objet" name="objet" rows="3" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('bo.courriers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>
</x-app-layout>