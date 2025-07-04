<x-app-layout>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Créer un nouveau courrier</h2>
    
    <form method="POST" action="{{ route('bo.courriers.store') }}" class="space-y-6" id="courier-form" enctype="multipart/form-data">
        @csrf
        
        <!-- Sender Section -->
        <section class="sender-section">
            <h3 class="text-lg font-semibold mb-4">Expéditeur</h3>
            
            <div x-data="senderFormController()" class="space-y-6">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                    <div class="flex-1 max-w-md">
                        <label for="expediteur_id" class="block font-medium text-gray-700 mb-1">
                            Expéditeur existant
                        </label>
                        <select 
                            x-on:change="handleExistingSenderChange($event)" 
                            name="expediteur_id" 
                            id="expediteur_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"
                            aria-describedby="expediteur_id_help">
                            <option value="">Sélectionnez un expéditeur existant</option>
                            @foreach ($expediteurs as $expediteur)
                                <option value="{{ $expediteur->id }}" @selected(old('expediteur_id') == $expediteur->id)>
                                    {{ $expediteur->nom }}
                                </option>
                            @endforeach
                        </select>
                        <p id="expediteur_id_help" class="mt-1 text-xs text-gray-500">
                            Choisissez un expéditeur dans la liste ou créez-en un nouveau
                        </p>
                        @error('expediteur_id')
                            <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button 
                        type="button" 
                        x-on:click="toggleNewSenderForm()"
                        class="px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-500 border border-indigo-300 rounded-md hover:border-indigo-400 transition-colors duration-200"
                        x-text="showNewSenderForm ? 'Masquer le formulaire' : 'Ajouter un nouvel expéditeur'">
                    </button>
                </div>

                <!-- New Sender Form -->
                <div x-show="showNewSenderForm" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="new-sender-form bg-gray-50 p-4 rounded-lg border">
                    
                    <h4 class="text-md font-medium text-gray-900 mb-4">Informations du nouvel expéditeur</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="exp_nom" class="block font-medium text-gray-700 mb-1">
                                Nom de l'expéditeur <span class="text-red-500" aria-label="Champ obligatoire">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="exp_nom" 
                                id="exp_nom" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                                value="{{ old('exp_nom') }}"
                                maxlength="255"
                                x-bind:required="showNewSenderForm && !hasSelectedExistingSender">
                            @error('exp_nom')
                                <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="exp_type_source" class="block font-medium text-gray-700 mb-1">
                                Type de source <span class="text-red-500" aria-label="Champ obligatoire">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="exp_type_source" 
                                id="exp_type_source" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                                value="{{ old('exp_type_source') }}"
                                maxlength="100"
                                x-bind:required="showNewSenderForm && !hasSelectedExistingSender">
                            @error('exp_type_source')
                                <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="exp_adresse" class="block font-medium text-gray-700 mb-1">Adresse</label>
                            <input 
                                type="text" 
                                name="exp_adresse" 
                                id="exp_adresse" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                                value="{{ old('exp_adresse') }}"
                                maxlength="500">
                            @error('exp_adresse')
                                <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="exp_telephone" class="block font-medium text-gray-700 mb-1">Téléphone</label>
                            <input 
                                type="tel" 
                                name="exp_telephone" 
                                id="exp_telephone" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                                value="{{ old('exp_telephone') }}"
                                pattern="[0-9+\-\s\(\)]*"
                                maxlength="20">
                            @error('exp_telephone')
                                <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="border-gray-200">

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

                <!-- Camera Capture (for mobile devices) -->
                <div class="camera-section">
                    <button 
                        type="button"
                        x-on:click="toggleCamera()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span x-text="showCamera ? 'Fermer la caméra' : 'Prendre une photo'"></span>
                    </button>
                    
                    <!-- Camera interface -->
                    <div x-show="showCamera" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="mt-4 p-4 border rounded-lg bg-gray-50">
                        
                        <video 
                            x-ref="video" 
                            autoplay 
                            playsinline 
                            class="w-full max-w-md mx-auto rounded-lg bg-black">
                        </video>
                        
                        <canvas x-ref="canvas" class="hidden"></canvas>
                        
                        <div class="mt-4 text-center">
                            <button 
                                type="button"
                                x-on:click="capturePhoto()"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Capturer
                            </button>
                        </div>
                    </div>
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

        <hr class="border-gray-200">

        <!-- Courier Section -->
        <section class="courier-section">
            <h3 class="text-lg font-semibold mb-4">Informations du courrier</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="type_courrier" class="block font-medium text-gray-700 mb-1">
                        Type de courrier <span class="text-red-500" aria-label="Champ obligatoire">*</span>
                    </label>
                    <select 
                        name="type_courrier" 
                        id="type_courrier" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        required>
                        <option value="">Choisir le type...</option>
                        <option value="arrive" @selected(old('type_courrier') === 'arrive')>Arrivé</option>
                        <option value="depart" @selected(old('type_courrier') === 'depart')>Départ</option>
                        <option value="interne" @selected(old('type_courrier') === 'interne')>Interne</option>
                    </select>
                    @error('type_courrier')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
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
                
                <div class="form-group">
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
                
                <div class="form-group">
                    <label for="reference_BO" class="block font-medium text-gray-700 mb-1">
                        Référence BO
                    </label>
                    <input 
                        type="number" 
                        name="reference_BO" 
                        id="reference_BO" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('reference_BO') }}"
                        min="1"
                        step="1">
                    @error('reference_BO')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
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
                        <option value="" @selected(old('priorite') === '')>Normale</option>
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

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
            <a href="{{ route('bo.courriers.index') }}" 
               class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                Annuler
            </a>
            <button type="submit" 
                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                Créer le courrier
            </button>
        </div>
    </form>

    <script>
        function senderFormController() {
            return {
                showNewSenderForm: false,
                hasSelectedExistingSender: false,
                
                init() {
                    // Show new sender form if there are validation errors for sender fields
                    const senderErrors = ['exp_nom', 'exp_type_source', 'exp_adresse', 'exp_telephone'];
                    const hasErrors = senderErrors.some(field => 
                        document.querySelector(`[name="${field}"]`)?.closest('.form-group')?.querySelector('.text-red-600')
                    );
                    
                    if (hasErrors) {
                        this.showNewSenderForm = true;
                    }
                },
                
                toggleNewSenderForm() {
                    this.showNewSenderForm = !this.showNewSenderForm;
                    
                    if (this.showNewSenderForm) {
                        // Clear existing sender selection when showing new form
                        document.getElementById('expediteur_id').value = '';
                        this.hasSelectedExistingSender = false;
                    }
                },
                
                handleExistingSenderChange(event) {
                    this.hasSelectedExistingSender = event.target.value !== '';
                    
                    if (this.hasSelectedExistingSender) {
                        // Hide new sender form and clear its fields
                        this.showNewSenderForm = false;
                        this.clearNewSenderFields();
                    }
                },
                
                clearNewSenderFields() {
                    const fields = ['exp_nom', 'exp_type_source', 'exp_adresse', 'exp_telephone'];
                    fields.forEach(fieldName => {
                        const field = document.getElementById(fieldName);
                        if (field) field.value = '';
                    });
                }
            }
        }

        function documentUploadController() {
            return {
                dragover: false,
                selectedFiles: [],
                showCamera: false,
                stream: null,

                init() {
                    // Handle pre-selected files on page load (if any)
                    const fileInput = document.getElementById('document_files');
                    if (fileInput.files.length > 0) {
                        this.handleFiles(Array.from(fileInput.files));
                    }
                },

                handleDrop(event) {
                    this.dragover = false;
                    const files = Array.from(event.dataTransfer.files);
                    this.handleFiles(files);
                },

                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    this.handleFiles(files);
                },

                handleFiles(files) {
                    const validFiles = [];
                    const maxSize = 10 * 1024 * 1024; // 10MB
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff', 'image/webp', 'application/pdf'];

                    files.forEach(file => {
                        if (!allowedTypes.includes(file.type)) {
                            alert(`Le fichier "${file.name}" n'est pas un format supporté.`);
                            return;
                        }

                        if (file.size > maxSize) {
                            alert(`Le fichier "${file.name}" est trop volumineux (max 10MB).`);
                            return;
                        }

                        // Create preview for images
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                file.preview = e.target.result;
                                this.selectedFiles.push(file);
                                this.updateFileInput();
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // For PDFs, no preview needed
                            validFiles.push(file);
                        }
                    });

                    // Add PDFs directly
                    if (validFiles.length > 0) {
                        this.selectedFiles.push(...validFiles);
                        this.updateFileInput();
                    }
                },

                removeFile(index) {
                    this.selectedFiles.splice(index, 1);
                    this.updateFileInput();
                },

                updateFileInput() {
                    // Create a new DataTransfer object to update the file input
                    const dt = new DataTransfer();
                    this.selectedFiles.forEach(file => {
                        dt.items.add(file);
                    });
                    document.getElementById('document_files').files = dt.files;
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                async toggleCamera() {
                    if (this.showCamera) {
                        this.closeCamera();
                    } else {
                        await this.openCamera();
                    }
                },

                async openCamera() {
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: { 
                                facingMode: 'environment', // Use back camera on mobile
                                width: { ideal: 1280 },
                                height: { ideal: 720 }
                            }
                        });
                        
                        this.showCamera = true;
                        
                        // Wait for the video element to be rendered
                        this.$nextTick(() => {
                            if (this.$refs.video) {
                                this.$refs.video.srcObject = this.stream;
                            }
                        });
                    } catch (error) {
                        console.error('Erreur d\'accès à la caméra:', error);
                        alert('Impossible d\'accéder à la caméra. Veuillez vérifier les permissions.');
                    }
                },

                closeCamera() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.stream = null;
                    }
                    this.showCamera = false;
                },

                capturePhoto() {
                    if (!this.$refs.video || !this.$refs.canvas) return;

                    const video = this.$refs.video;
                    const canvas = this.$refs.canvas;
                    const context = canvas.getContext('2d');

                    // Set canvas dimensions to match video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // Draw the video frame to canvas
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Convert canvas to blob
                    canvas.toBlob((blob) => {
                        if (blob) {
                            // Create a file from the blob
                            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                            const file = new File([blob], `photo-${timestamp}.jpg`, {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });

                            // Add preview
                            file.preview = canvas.toDataURL('image/jpeg');
                            
                            // Add to selected files
                            this.selectedFiles.push(file);
                            this.updateFileInput();
                            
                            // Close camera
                            this.closeCamera();
                        }
                    }, 'image/jpeg', 0.8);
                },

                // Cleanup when component is destroyed
                destroy() {
                    this.closeCamera();
                }
            }
        }
        
        // Form validation and UX improvements
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('courier-form');
            
            // Auto-set registration date to today if empty
            const dateEnregistrement = document.getElementById('date_enregistrement');
            if (dateEnregistrement && !dateEnregistrement.value) {
                dateEnregistrement.value = new Date().toISOString().split('T')[0];
            }
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                const expediteurId = document.getElementById('expediteur_id').value;
                const expNom = document.getElementById('exp_nom').value.trim();
                
                // Ensure either existing sender is selected or new sender info is provided
                if (!expediteurId && !expNom) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un expéditeur existant ou remplir les informations du nouvel expéditeur.');
                    return false;
                }
            });

            // Cleanup camera streams when page is unloaded
            window.addEventListener('beforeunload', function() {
                const documentController = Alpine.getElementWithX(document.querySelector('[x-data*="documentUploadController"]'));
                if (documentController && documentController.stream) {
                    documentController.stream.getTracks().forEach(track => track.stop());
                }
            });
        });
    </script>
</x-app-layout>