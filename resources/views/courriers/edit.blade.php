<x-app-layout>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Créer un nouveau courrier</h2>

    <form method="POST" action="{{ route('courriers.update', $courrier) }}" enctype="multipart/form-data"  x-data="{
          type: '{{ old('type_courrier', $courrier->type_courrier) }}',
          showNewSenderForm: false,
          updateType() {
              console.log('Type selected: ', this.type);
          }
      }"
      x-init="updateType()">
        @csrf
        @method('PUT')
        
        @unless(auth()->user()->hasRole('cab'))
        <!-- Type de courrier -->
        <div class="form-group">
            <label for="type_courrier" class="block font-medium text-gray-700 mb-1">Type de courrier</label>
           <select
            name="type_courrier"
            id="type_courrier"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"
            x-model="type"
            x-on:change="updateType"
        >
            <option value="">Choisir...</option>
            <option value="arrive">Arrivé</option>
            <option value="depart">Départ</option>
            <option value="visa">Visa</option>
            <option value="decision">Decision</option>
            <option value="interne">Interne</option>
        </select>

        @if ($errors->has('type_courrier'))
            <div class="text-red-500 text-sm mt-1">{{ $errors->first('type_courrier') }}</div>
        @endif
        </div>
        @endunless

        @unless(auth()->user()->hasRole('cab'))
        <!-- Expéditeur -->
        <div x-show="type === 'arrive' || type === 'visa'">
            <label>Expéditeur externe</label>
            <select value="{{ old('id_expediteur', $courrier->expediteur_id) }}" name="id_expediteur" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <option value="">Sélectionner...</option>
                @foreach($expediteurs as $expediteur)
                    <option value="{{ $expediteur->id }}" @selected($courrier->expediteur && $courrier->expediteur->id === $expediteur->id)>{{ $expediteur->nom }}</option>
                @endforeach
            </select>

            <button type="button" class="mt-2 text-indigo-600" x-on:click="showNewSenderForm = !showNewSenderForm">
                Ajouter un nouvel expéditeur
            </button>

            <div x-show="showNewSenderForm" class="mt-4 space-y-2 bg-indigo-500 p-4 rounded-md shadow-md" x-data="{ typesource: '' }" >
                <input type="text" name="exp_nom" placeholder="Nom" 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <select  name="exp_type_source" placeholder="Type de source" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" x-model="typesource">
                    <option value="">Sélectionner...</option>
                    <option value="citoyen">citoyen</option>
                    <option value="administration">administration</option>
                </select>
                <input type="text" name="exp_adresse" placeholder="Adresse" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <input type="text" name="exp_telephone" placeholder="Téléphone" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">

                 <template x-if="typesource === 'citoyen'">
                    <input type="text" name="exp_CIN" 
                    placeholder="CIN" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"  >
                </template>
            </div>
        </div>

        <div x-show="type === 'depart' || type === 'decision' || type === 'interne'">
            <label>Entité expéditrice</label>
            <select name="entite_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                @foreach($entites as $entite)
                    <option value="{{ $entite->id }}">{{ $entite->nom }}</option>
                @endforeach
            </select>
        </div>
        @endunless

        <div>
            <div class="grid grid-cols-1 md:grid-cols gap-4">
                @unless(auth()->user()->hasRole('cab'))
                <div x-show="type === 'interne' || type === 'arrive' ">
                    <label>Destinataires internes</label>
                    <select id="destinataires" name="destinataires_entite[]" multiple  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                        @foreach($entites as $entite)
                            <option value="{{ $entite->id }}" @selected(in_array($entite->id, $selectedDestinatairesInterne))>
                                {{ $entite->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endunless

                <div x-show="type === 'depart' || type === 'decision' || type === 'interne' || type === 'visa'">
                 <div x-data="{ manualDestinataires: @json($destinatairesManuels) }">
                    <label>Destinataires externes</label>
                    <select id="destinataires" name="destinataires_externe[]" multiple class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                        @foreach($destinatairesExternes as $destinataire)
                            <option value="{{ $destinataire->id }}" @selected(in_array($destinataire->id, $selectedDestinatairesExterne))>
                                {{ $destinataire->nom }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex justify-between gap-4">
                        <!-- Réinitialiser button -->
                        <button
                            type="button"
                            onclick="resetSelect()"
                            class="px-4 py-2  text-red-600 font-semibold rounded hover:bg-red-200 transition">
                            Réinitialiser la sélection
                        </button>

                        <!-- Ajouter destinataire button -->
                        <button
                            type="button"
                            class="px-4 py-2 text-indigo-600 font-semibold rounded hover:bg-indigo-200 transition"
                            @click="manualDestinataires.push({ nom: '', type_source: '', adresse: '', CIN: '', telephone: '' })">
                            + Ajouter un nouveau destinataire
                        </button>
                    </div>

                    <template x-for="(dest, index) in manualDestinataires" :key="index">
                        <div class="mt-4 space-y-2 bg-indigo-100 p-4 rounded-md shadow-md relative" x-data="{ typesource: '' }">
                            <button type="button"
                                    class="absolute top-1 right-1 text-red-600"
                                    @click="manualDestinataires.splice(index, 1)">
                                ✖
                            </button>

                            <input type="text" :name="'dest_nom[]'" x-model="dest.nom" placeholder="Nom" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <select  name="dest_type_source[]" placeholder="Type de source" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" x-model="typesource">
                                    <option value="">Sélectionner...</option>
                                    <option value="citoyen">citoyen</option>
                                    <option value="administration">administration</option>
                                </select>
                            <input type="text" :name="'dest_adresse[]'" x-model="dest.adresse" placeholder="Adresse" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <template x-if="typesource === 'citoyen'">
                                    <input type="text" name="dest_CIN[]" 
                                    placeholder="CIN" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"  >
                            </template>            
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
                @unless(auth()->user()->hasRole('cab'))
                <div class="form-group">
                    <label for="objet" class="block font-medium text-gray-700 mb-1">
                        Objet <span class="text-red-500" aria-label="Champ obligatoire">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="objet" 
                        id="objet" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('objet', $courrier->objet) }}" 
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
                        value="{{ old('reference_arrive', $courrier->reference_arrive) }}"
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
                        value="{{ old('reference_depart', $courrier->reference_depart) }}"
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
                        value="{{ old('reference_visa', $courrier->reference_visa) }}"
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
                        value="{{ old('reference_dec', $courrier->reference_dec) }}"
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
                        value="{{ old('reference_bo', $courrier->reference_bo) }}"
                        min="1"
                        step="1">
                    @error('reference_bo')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group"  x-show="type === 'arrive' || type === 'visa'">
                    <label for="date_reception" class="block font-medium text-gray-700 mb-1">Date de réception</label>
                    <input 
                        type="date" 
                        name="date_reception" 
                        id="date_reception" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('date_reception', optional($courrier->date_reception)->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}">
                    @error('date_reception')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group" x-show="type === 'depart' || type === 'interne'|| type === 'decision'" >
                    <label for="date_depart" class="block font-medium text-gray-700 mb-1">Date de Depart</label>
                    <input 
                        type="date" 
                        name="date_depart" 
                        id="date_depart" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('date_depart', optional($courrier->date_depart)->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}">
                    @error('date_depart')
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
                        value="{{ old('date_enregistrement', optional($courrier->date_enregistrement)->format('Y-m-d')) }}" 
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
                        value="{{ old('Nbr_piece', $courrier->Nbr_piece) }}" 
                        min="1" 
                        max="999"
                        step="1"
                        required>
                    @error('Nbr_piece')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                @endunless
                
                <div class="form-group">
                    <label for="priorite" class="block font-medium text-gray-700 mb-1">Priorité</label>
                    <select 
                        name="priorite" 
                        id="priorite" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                        <option value="normale" @selected(old('priorite', $courrier->priorite) === 'normale')>Normale</option>
                        <option value="urgent" @selected(old('priorite', $courrier->priorite) === 'urgent')>Urgent</option>
                        <option value="confidentiel" @selected(old('priorite', $courrier->priorite) === 'confidentiel')>Confidentiel</option>
                        <option value="A reponse obligatoire" @selected(old('priorite', $courrier->priorite) === 'A reponse obligatoire')>À réponse obligatoire</option>
                    </select>
                    @error('priorite')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="delais" class="block font-medium text-gray-700 mb-1">Date de délais</label>
                    <input 
                        type="date" 
                        name="delais" 
                        id="delais" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('delais', optional($courrier->delais)->format('Y-m-d')) }}" 
                        min="{{ date('Y-m-d') }}">
                    @error('delais')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        @unless(auth()->user()->hasRole('cab'))
        <!-- Document Upload/Scan Section -->
        <section class="document-section">
            <h3 class="text-lg font-semibold mb-4">Document du courrier</h3>
            
            <div x-data="documentUploadController({{ $existingFile ? json_encode($existingFile) : 'null' }})" class="space-y-4">
                <!-- Upload Area -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                    :class="{ 'border-blue-500 bg-blue-50': dragover }"
                    x-on:dragover.prevent="dragover = true"
                    x-on:dragleave="dragover = false"
                    x-on:drop.prevent="handleDrop($event)"
                    x-show="!selectedFile">
                    
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
                                    <img :src="selectedFile.url || selectedFile.preview" 
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
                                    <p class="text-sm font-medium text-gray-900" x-text="selectedFile.name"></p>
                                    <p class="text-xs text-gray-500" x-text="selectedFile.size ? formatFileSize(selectedFile.size) : 'Fichier existant'"></p>
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
        @endunless

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Mettre à jour le courrier</button>
            <a href="{{ route("courriers.$courrier->type_courrier") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Retour
            </a>
        </div>
    </form>
</x-app-layout>

<script>
function documentUploadController(existingFile = null) {
    return {
        dragover: false,
        selectedFile: existingFile ? {
            name: existingFile.name,
            url: existingFile.url,
            type: existingFile.type,
            size: null // No size for existing files
        } : null,

        handleDrop(e) {
            this.dragover = false;
            const file = e.dataTransfer.files[0];
            this.processFile(file);
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            this.processFile(file);
        },

        processFile(file) {
            if (!file) return;

            // Validate file type and size
            const validTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Type de fichier non supporté');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Le fichier est trop volumineux (max 2MB)');
                return;
            }

            // Create preview for images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.selectedFile = {
                        name: file.name,
                        preview: e.target.result,
                        type: file.type,
                        size: file.size,
                        file: file
                    };
                    this.updateFileInput(file);
                };
                reader.readAsDataURL(file);
            } else {
                this.selectedFile = {
                    name: file.name,
                    type: file.type,
                    size: file.size,
                    file: file
                };
                this.updateFileInput(file);
            }
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
            if (!bytes) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    };
}

function resetSelect() {
    const select = document.getElementById('destinataires');
    Array.from(select.options).forEach(option => option.selected = false);
}
</script>