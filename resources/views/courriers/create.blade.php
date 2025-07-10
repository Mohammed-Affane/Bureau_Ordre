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

            <div x-show="showNewSenderForm" class="mt-4 space-y-2 bg-indigo-500 p-4 rounded-md shadow-md" x-data="{ typesource: '' }" x-on:change="typesource = $event.target.value">
                <input type="text" name="exp_nom" placeholder="Nom" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <select  name="exp_type_source" placeholder="Type de source" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                    <option value="">Sélectionner...</option>
                    <option value="citoyen">citoyen</option>
                    <option value="administration">administration</option>
                </select>
                <input type="text" name="exp_adresse" placeholder="Adresse" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <input type="text" name="exp_telephone" placeholder="Téléphone" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <input type="text" name="exp_CIN" placeholder="CIN" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" x-show="typesource=='citoyen'">
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
                 <div x-data="{ manualDestinataires: [] }">
    <label>Destinataires externes</label>
    <select name="destinataires_externe[]" multiple class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
        @foreach($destinataires as $destinataire)
            <option value="{{ $destinataire->id }}">{{ $destinataire->nom }}</option>
        @endforeach
    </select>

    <button type="button"
            class="mt-2 text-indigo-600"
            @click="manualDestinataires.push({ nom: '', type_source: '', adresse: '', CIN: '', telephone: '' })">
        + Ajouter un nouveau destinataire
    </button>

    <template x-for="(dest, index) in manualDestinataires" :key="index">
        <div class="mt-4 space-y-2 bg-indigo-100 p-4 rounded-md shadow-md relative">
            <button type="button"
                    class="absolute top-1 right-1 text-red-600"
                    @click="manualDestinataires.splice(index, 1)">
                ✖
            </button>

            <input type="text" :name="'dest_nom[]'" x-model="dest.nom" placeholder="Nom" class="block w-full rounded-md border-gray-300 shadow-sm">
            <input type="text" :name="'dest_type_source[]'" x-model="dest.type_source" placeholder="Type de source" class="block w-full rounded-md border-gray-300 shadow-sm">
            <input type="text" :name="'dest_adresse[]'" x-model="dest.adresse" placeholder="Adresse" class="block w-full rounded-md border-gray-300 shadow-sm">
            <input type="text" :name="'dest_CIN[]'" x-model="dest.CIN" placeholder="CIN" class="block w-full rounded-md border-gray-300 shadow-sm">
            <input type="text" :name="'dest_telephone[]'" x-model="dest.telephone" placeholder="Téléphone" class="block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </template>
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
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
         :class="{ 'border-blue-500 bg-blue-50': dragover }"
         x-on:dragover.prevent="dragover = true"
         x-on:dragleave="dragover = false"
         x-on:drop.prevent="handleDrop($event)">
        
        <div class="text-sm text-gray-600">
            <label for="document_files" class="cursor-pointer">
                <span class="font-medium text-indigo-600 hover:text-indigo-500">Cliquez pour télécharger</span>
                ou glissez-déposez votre fichier
            </label>
            <input 
                type="file" 
                id="document_files" 
                name="fichier_scan" 
                accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.webp"
                class="sr-only"
                x-on:change="handleFileSelect($event)">
        </div>
        
        <p class="text-xs text-gray-500 mt-2">
            PDF, JPG, PNG, GIF, BMP, TIFF, WebP (max 2MB)
        </p>
    </div>
    
    <!-- Selected File Preview -->
    <div x-show="selectedFile" class="mt-4">
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <!-- Image preview -->
                    <template x-if="selectedFile && selectedFile.type.startsWith('image/')">
                        <img :src="selectedFile.preview" 
                             class="w-16 h-16 object-cover rounded-lg border">
                    </template>
                    
                    <!-- PDF icon -->
                    <template x-if="selectedFile && selectedFile.type === 'application/pdf'">
                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z"/>
                            </svg>
                        </div>
                    </template>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-900" x-text="selectedFile?.name"></p>
                        <p class="text-xs text-gray-500" x-text="formatFileSize(selectedFile?.size)"></p>
                    </div>
                </div>
                
                <button type="button" 
                        x-on:click="removeFile()"
                        class="text-red-600 hover:text-red-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Error Messages -->
    @error('fichier_scan')
        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
    @enderror
</div>
        </section>


        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Créer le courrier</button>
        </div>
    </form>
</x-app-layout>
<script>

function documentUploadController() {
    return {
        selectedFile: null,
        dragover: false,
        
        handleDrop(e) {
            this.dragover = false;
            const files = Array.from(e.dataTransfer.files);
            if (files.length > 0) {
                this.processFile(files[0]); // Only take the first file
            }
        },
        
        handleFileSelect(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                this.processFile(files[0]); // Only take the first file
            }
        },
        
        processFile(file) {
            const validTypes = [
                'application/pdf',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
                'image/tiff',
                'image/webp'
            ];
            
            if (!validTypes.includes(file.type)) {
                alert(`Le fichier ${file.name} n'est pas d'un type valide.`);
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) { // 2MB to match backend validation
                alert(`Le fichier ${file.name} dépasse la taille maximale de 2MB.`);
                return;
            }
            
            // Create preview for images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    file.preview = e.target.result;
                    this.selectedFile = file;
                };
                reader.readAsDataURL(file);
            } else {
                this.selectedFile = file;
            }
            
            // Set the file input value programmatically
            this.updateFileInput(file);
        },
        
        updateFileInput(file) {
            const fileInput = document.getElementById('document_files');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        },
        
        removeFile() {
            this.selectedFile = null;
            const fileInput = document.getElementById('document_files');
            fileInput.value = '';
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    };
}
</script>