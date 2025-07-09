<x-app-layout>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Créer un nouveau courrier</h2>

    <form method="POST" action="{{ route('courriers.store') }}" enctype="multipart/form-data" x-data="{
        type: '{{ old('type_courrier', '') }}',
        showNewSenderForm: false,
        updateType(e) { this.type = e.target.value; }
    }">
        @csrf

        <!-- Type de courrier -->
        <div class="form-group">
            <label for="type_courrier" class="block font-medium text-gray-700 mb-1">Type de courrier</label>
            <select name="type_courrier" id="type_courrier" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" x-on:change="updateType">
                <option value="">Choisir...</option>
                <option value="arrive" :selected="type === 'arrive'">Arrivé</option>
                <option value="depart" :selected="type === 'depart'">Départ</option>
                <option value="visa" :selected="type === 'visa'">Visa</option>
                <option value="decision" :selected="type === 'decision'">decision</option>
                <option value="interne" :selected="type === 'interne'">interne</option>
            </select>
        </div>

        <!-- Expéditeur -->
        <div x-show="type === 'arrive'">
            <label>Expéditeur externe</label>
            <select name="id_expediteur" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <option value="">Sélectionner...</option>
                @foreach($expediteurs as $expediteur)
                    <option value="{{ $expediteur->id }}">{{ $expediteur->nom }}</option>
                @endforeach
            </select>

            <button type="button" class="mt-2 text-indigo-600" x-on:click="showNewSenderForm = !showNewSenderForm">
                Ajouter un nouvel expéditeur
            </button>

            <div x-show="showNewSenderForm" class="mt-4 space-y-2">
                <input type="text" name="exp_nom" placeholder="Nom" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <input type="text" name="exp_type_source" placeholder="Type de source" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <input type="text" name="exp_adresse" placeholder="Adresse" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <input type="text" name="exp_telephone" placeholder="Téléphone" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
            </div>
        </div>

        <div x-show="type === 'depart' || type === 'decision'">
            <label>Entité expéditrice</label>
            <select name="entite_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                @foreach($entites as $entite)
                    <option value="{{ $entite->id }}">{{ $entite->nom }}</option>
                @endforeach
            </select>
        </div>

        

        <div >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div x-show="type === 'interne' || type === 'arrive' ">
                    <label>Destinataires internes</label>
                    <select name="destinataires_entite[]" multiple class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                        @foreach($entites as $entite)
                            <option value="{{ $entite->id }}">{{ $entite->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="type === 'depart' || type === 'decision' || type === 'interne' || type === 'visa'">
                    <label>Destinataires externes</label>
                    <select name="destinataires_externe[]" multiple class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                        @foreach($destinataires as $destinataire)
                            <option value="{{ $destinataire->id }}">{{ $destinataire->nom }}</option>
                        @endforeach
                    </select>

                    <button type="button" class="mt-2 text-indigo-600" x-on:click="showNewSenderForm = !showNewSenderForm">
                Ajouter un nouvel Destinataire
            </button> 

            <div class="mt-4 space-y-2">
                <input type="text" name="dest_nom[]" placeholder="Nom" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <input type="text" name="dest_type_source[]" placeholder="Type de source" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <input type="text" name="dest_adresse[]" placeholder="Adresse" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
            </div>
                </div>
            </div>
        </div>

          <!-- Courier Section -->
        <section class="courier-section">
            <h3 class="text-lg font-semibold mb-4">Informations du courrier</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                
                <div class="form-group">
                    <label for="objet" class="block font-medium text-gray-700 mb-1">
                        Objet <span class="text-red-500" aria-label="Champ obligatoire">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="objet" 
                        id="objet" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('objet') }}" 
                        maxlength="255"
                        required>
                    @error('objet')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group"  x-show="type === 'arrive' || type === 'visa'">
                    <label for="reference_arrive" class="block font-medium text-gray-700 mb-1">
                        Référence d'arrivée
                    </label>
                    <input 
                        type="number" 
                        name="reference_arrive" 
                        id="reference_arrive" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('reference_arrive') }}"
                        min="1"
                        step="1">
                    @error('reference_arrive')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group"  x-show="type === 'depart' || type === 'interne'">
                    <label for="reference_depart" class="block font-medium text-gray-700 mb-1">
                        Référence depart
                    </label>
                    <input 
                        type="number" 
                        name="reference_depart" 
                        id="reference_depart" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('reference_depart') }}"
                        min="1"
                        step="1">
                    @error('reference_depart')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group"  x-show="type === 'visa'">
                    <label for="reference_visa" class="block font-medium text-gray-700 mb-1">
                        Référence Visa
                    </label>
                    <input 
                        type="number" 
                        name="reference_visa" 
                        id="reference_visa" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('reference_visa') }}"
                        min="1"
                        step="1">
                    @error('reference_visa')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group"  x-show="type === 'decision'">
                    <label for="reference_dec" class="block font-medium text-gray-700 mb-1">
                        Référence decision
                    </label>
                    <input 
                        type="number" 
                        name="reference_dec" 
                        id="reference_dec" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('reference_dec') }}"
                        min="1"
                        step="1">
                    @error('reference_dec')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group"  x-show="type === 'arrive'">
                    <label for="reference_bo" class="block font-medium text-gray-700 mb-1">
                        Référence BO
                    </label>
                    <input 
                        type="number" 
                        name="reference_bo" 
                        id="reference_bo" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('reference_bo') }}"
                        min="1"
                        step="1">
                    @error('reference_bo')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group"  x-show="type === 'arrive' || type === 'visa'|| type === 'decision'">
                    <label for="date_reception" class="block font-medium text-gray-700 mb-1">Date de réception</label>
                    <input 
                        type="date" 
                        name="date_reception" 
                        id="date_reception" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('date_reception') }}"
                        max="{{ date('Y-m-d') }}">
                    @error('date_reception')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group" x-show="type === 'depart' || type === 'interne'" >
                    <label for="date_depart" class="block font-medium text-gray-700 mb-1">Date de Depart</label>
                    <input 
                        type="date" 
                        name="date_depart" 
                        id="date_reception" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('date_reception') }}"
                        max="{{ date('Y-m-d') }}">
                    @error('date_reception')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_enregistrement" class="block font-medium text-gray-700 mb-1">
                        Date d'enregistrement <span class="text-red-500" aria-label="Champ obligatoire">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="date_enregistrement" 
                        id="date_enregistrement" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('date_enregistrement', date('Y-m-d')) }}" 
                        max="{{ date('Y-m-d') }}"
                        required>
                    @error('date_enregistrement')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="Nbr_piece" class="block font-medium text-gray-700 mb-1">
                        Nombre de pièces <span class="text-red-500" aria-label="Champ obligatoire">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="Nbr_piece" 
                        id="Nbr_piece" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('Nbr_piece', 1) }}" 
                        min="1" 
                        max="999"
                        step="1"
                        required>
                    @error('Nbr_piece')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="priorite" class="block font-medium text-gray-700 mb-1">Priorité</label>
                    <select 
                        name="priorite" 
                        id="priorite" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                        <option value="normale" @selected(old('priorite') === 'normale')>Normale</option>
                        <option value="urgent" @selected(old('priorite') === 'urgent')>Urgent</option>
                        <option value="confidentiel" @selected(old('priorite') === 'confidentiel')>Confidentiel</option>
                        <option value="A reponse obligatoire" @selected(old('priorite') === 'A reponse obligatoire')>À réponse obligatoire</option>
                    </select>
                    @error('priorite')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group md:col-span-2">
                    <label for="id_agent_en_charge" class="block font-medium text-gray-700 mb-1">Agent en charge</label>
                    <select 
                        name="id_agent_en_charge" 
                        id="id_agent_en_charge" 
                        class="block w-full max-w-md rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                        <option value="">Aucun agent assigné</option>
                        @if(isset($agents) && count($agents) > 0)
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" @selected(old('id_agent_en_charge') == $agent->id)>
                                    {{ $agent->nom_complet ?? $agent->name ?? 'Agent #' . $agent->id }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('id_agent_en_charge')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <!-- Document Upload/Scan Section -->
        <section class="document-section">
            <h3 class="text-lg font-semibold mb-4">Document du courrier</h3>
            
            <div x-data="documentUploadController()" class="space-y-4">
                <!-- Upload Area -->
                <div class="upload-area">
                    <label for="document_files" class="block font-medium text-gray-700 mb-2">
                        Scanner ou télécharger le document
                    </label>
                    
                    <!-- Drop zone -->
                    <div 
                        x-on:drop.prevent="handleDrop($event)"
                        x-on:dragover.prevent="dragover = true"
                        x-on:dragleave.prevent="dragover = false"
                        x-bind:class="dragover ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'"
                        class="border-2 border-dashed rounded-lg p-6 text-center transition-colors duration-200">
                        
                        <div class="space-y-4">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            
                            <div class="text-sm text-gray-600">
                                <label for="document_files" class="cursor-pointer">
                                    <span class="font-medium text-indigo-600 hover:text-indigo-500">Cliquez pour télécharger</span>
                                    ou glissez-déposez vos fichiers
                                </label>
                                <input 
                                    type="file" 
                                    id="document_files" 
                                    name="document_files[]" 
                                    multiple 
                                    accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.webp"
                                    class="sr-only"
                                    x-on:change="handleFileSelect($event)">
                            </div>
                            
                            <p class="text-xs text-gray-500">
                                PDF, JPG, PNG, GIF, BMP, TIFF, WebP (max 10MB par fichier)
                            </p>
                        </div>
                    </div>
                    
                    @error('document_files')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                    @error('document_files.*')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Preview -->
                <div x-show="selectedFiles.length > 0" class="file-preview">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Fichiers sélectionnés</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="(file, index) in selectedFiles" :key="index">
                            <div class="relative border rounded-lg p-3 bg-white shadow-sm">
                                <!-- Image preview -->
                                <div x-show="file.type.startsWith('image/')" class="mb-2">
                                    <img :src="file.preview" :alt="file.name" class="w-full h-32 object-cover rounded">
                                </div>
                                
                                <!-- PDF preview -->
                                <div x-show="file.type === 'application/pdf'" class="mb-2 flex items-center justify-center h-32 bg-red-50 rounded">
                                    <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                
                                <!-- File info -->
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900 truncate" x-text="file.name"></p>
                                    <p class="text-gray-500" x-text="formatFileSize(file.size)"></p>
                                </div>
                                
                                <!-- Remove button -->
                                <button 
                                    type="button"
                                    x-on:click="removeFile(index)"
                                    class="absolute top-1 right-1 p-1 bg-red-100 hover:bg-red-200 rounded-full text-red-600 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>


        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Créer le courrier</button>
        </div>
    </form>
</x-app-layout>