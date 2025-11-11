<x-app-layout>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Créer un nouveau courrier</h2>
    {{-- Global error and flash display --}}
    @if(session('error'))
        <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded bg-yellow-100 text-yellow-800 px-4 py-2">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @php
        // Determine prefix based on user's email (simple, easy to adjust)
        $prefix = '';
        if (auth()->check()) {
            $email = auth()->user()->email ?? '';
            // Special exact mapping requested by the user
            if ($email === 'halimaBo_cab@gmail.com') {
                $prefix = 'cab\\';
            } elseif (str_contains($email, '_bo')) {
                $prefix = 'bo\\';
            } elseif (str_contains($email, '_sjc')) {
                $prefix = 'sjc\\';
            }elseif(str_contains($email, '_trans')){
                $prefix = 'trans\\';
            }elseif (str_contains($email,'_indh')) {
                $prefix = 'indh\\';
            }elseif(str_contains($email,'_dai')) {
                $prefix = 'dai\\';
            }
        }
    @endphp
    <form method="POST" action="{{ route('courriers.store') }}" enctype="multipart/form-data" x-data="{ type: '{{ old('type_courrier', '') }}', showNewSenderForm: false, updateType(e) { this.type = e.target.value; } }">
        @csrf
        <!-- Type de courrier -->
        @php
            $currentEmail = auth()->check() ? (auth()->user()->email ?? '') : '';
            $allowBoSelect = str_contains($currentEmail, '_bo');
        @endphp
        <div class="form-group">
            <label for="type_courrier" class="block font-medium text-gray-700 mb-1">Type de courrier</label>
            <select name="type_courrier" id="type_courrier" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" x-on:change="updateType" x-model="type">
                <option value="">Choisir...</option>
                <option value="arrive" :selected="type === 'arrive'">Arrivé</option>
                <option value="depart" :selected="type === 'depart'" @if(!$allowBoSelect) disabled @endif>Départ</option>
                <option value="visa" :selected="type === 'visa'" @if(!$allowBoSelect) disabled @endif>Visa</option>
                <option value="decision" :selected="type === 'decision'" @if(!$allowBoSelect) disabled @endif>decision</option>
                <option value="interne" :selected="type === 'interne'" @if(!$allowBoSelect) disabled @endif>interne</option>
            </select>
            @if(!$allowBoSelect)
                <p class="text-xs text-gray-500 mt-1">Votre compte n'autorise que le type « Arrivé ».</p>
            @endif
            @error('type_courrier')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Expéditeur -->
        <div x-show="type === 'arrive' || type === 'visa'">
            <label>Expéditeur externe</label>
            <select name="id_expediteur" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                <option value="">Sélectionner...</option>
                @foreach($expediteurs as $expediteur)
                    <option value="{{ $expediteur->id }}">{{ $expediteur->nom }}</option>
                @endforeach
            </select>
            @error('id_expediteur')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

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
        <div >
            <div class="grid grid-cols-1 md:grid-cols gap-4">
                <div x-show="type === 'interne'">
                    <label>Destinataires internes</label>
                    <select id="destinataires_internes" name="destinataires_entite[]" multiple class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                        @foreach($entites as $entite)
                            <option value="{{ $entite->id }}">{{ $entite->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-between gap-4" x-show="type ==='interne'">
                    <button
                        type="button"
                        onclick="resetSelectint()"
                        class="px-4 py-2  text-red-600 font-semibold rounded hover:bg-red-200 transition">
                        Réinitialiser la sélection
                    </button>
                </div>
                <div x-show="type === 'depart' || type === 'decision' || type === 'interne' || type === 'visa'">
                 <div x-data="{ manualDestinataires: [] }">
    <label>Destinataires externes</label>
    <select id="destinataires_externes" name="destinataires_externe[]" multiple class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
        @foreach($destinataires as $destinataire)
        
            <option value="{{ $destinataire->id }}">{{ $destinataire->nom }}</option>
        @endforeach

    </select>
    @error('destinataires')
        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
    @enderror

    <div class="flex justify-between gap-4">
    <!-- Réinitialiser button -->
    <button
        type="button"
        onclick="resetSelectext()"
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


            <!-- Bouton de réinitialisation -->

    <template x-for="(dest, index) in manualDestinataires" :key="index">
        <div class="mt-4 space-y-2 bg-indigo-100 p-4 rounded-md shadow-md relative" x-data="{ typesource: '' }">
            <button type="button"
                    class="absolute top-1 right-1 text-red-600"
                    @click="manualDestinataires.splice(index, 1)">
                ✖
            </button>

            <input type="text" :name="'dest_nom[]'" x-model="dest.nom" placeholder="Nom" class="block w-full rounded-md border-gray-300 shadow-sm">
            @error('dest_nom.*')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
            <select  name="dest_type_source[]" placeholder="Type de source" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" x-model="typesource">
                    <option value="">Sélectionner...</option>
                    <option value="citoyen">citoyen</option>
                    <option value="administration">administration</option>
                </select>
            <input type="text" :name="'dest_adresse[]'" x-model="dest.adresse" placeholder="Adresse" class="block w-full rounded-md border-gray-300 shadow-sm">
            @error('dest_adresse.*')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
            <template x-if="typesource === 'citoyen'">
                    <input type="text" name="dest_CIN[]" 
                    placeholder="CIN" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"  >
                    @error('dest_CIN.*')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
            </template>            <input type="text" :name="'dest_telephone[]'" x-model="dest.telephone" placeholder="Téléphone" class="block w-full rounded-md border-gray-300 shadow-sm">
            @error('dest_telephone.*')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
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
                        >
                    @error('objet')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group"  x-show="type === 'arrive' || type === 'visa'">
                    <label for="reference_arrive" class="block font-medium text-gray-700 mb-1">
                        Numero Courrier
                    </label>
                        <input 
                            type="text" 
                            name="reference_arrive" 
                            id="reference_arrive" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                            value="{{ old('reference_arrive') }}"
                            pattern="[A-Za-z0-9\/\-\._]+"
                            title="Only letters, numbers, /, -, _, and . are allowed">
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

                <div class="form-group"  x-show="type === 'arrive' || type === 'visa'">
                    <label for="date_reception" class="block font-medium text-gray-700 mb-1">Date de Courrier</label>
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
                
                <template x-if="type === 'arrive'">
                <div class="form-group">
                    <label for="reference_bo" class="block font-medium text-gray-700 mb-1">
                        Numero Arrivée
                    </label>
                    @php $dataPrefix = $prefix; @endphp
                    <input
                        type="text"
                        name="reference_bo"
                        id="reference_bo"
                        value="{{ old('reference_bo', $dataPrefix) }}"
                        data-prefix="{{ $dataPrefix }}"
                        oninput="ensurePrefix(this)"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"
                        placeholder="Ex: cab\\1234"
                    >
                    @if($dataPrefix)
                        <p class="text-xs text-gray-500 mt-1">Préfixe requis: <strong>{{ $dataPrefix }}</strong>. Seuls des chiffres sont autorisés après le préfixe.</p>
                    @endif
                    @error('reference_bo')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                </template>
                

                
                <div class="form-group" x-show="type === 'depart' || type === 'interne'|| type === 'decision'" >
                    <label for="date_depart" class="block font-medium text-gray-700 mb-1">Date de Depart</label>
                    <input 
                        type="date" 
                        name="date_depart" 
                        id="date_reception" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('date_depart') }}"
                        max="{{ date('Y-m-d') }}">
                    @error('date_depart')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_enregistrement" class="block font-medium text-gray-700 mb-1">
                   <span x-text="type === 'arrive' ? 'Date Arrivee' : 'Date Enregistrement'"></span>
 

                    

                       
                    <span class="text-red-500" aria-label="Champ obligatoire">*</span>
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
                        step="1"
                        required>
                    @error('Nbr_piece')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
               <!--  <div class="form-group">
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
                </div> -->
<!--                 <div class="form-group">
                    <label for="delais" class="block font-medium text-gray-700 mb-1">Date de délais</label>
                    <input 
                        type="date" 
                        name="delais" 
                        id="delais" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                        value="{{ old('delais') }}"
                        min="{{ date('Y-m-d') }}">
                    @error('delais')
                        <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div> -->
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
                accept=".pdf"
                class="sr-only"
                x-on:change="handleFileSelect($event)">
        </div>
        
        <p class="text-xs text-gray-500 mt-2">
            PDF (max 2MB)
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
                'application/pdf'
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
    function resetSelectext() {
            const select = document.getElementById('destinataires_externes');
            Array.from(select.options).forEach(option => option.selected = false);
        }
    function resetSelectint() {
        const select = document.getElementById('destinataires_internes');
        Array.from(select.options).forEach(option => option.selected = false);
    }

    // Ensure the reference input starts with the required prefix and only digits follow
    function ensurePrefix(el) {
        if (!el) return;
        const prefix = el.dataset.prefix || '';
        if (!prefix) return; // no enforcement if no prefix
        let val = el.value || '';
        // If empty, set prefix
        if (!val || val === '') {
            el.value = prefix;
            return;
        }
        // If user pasted something with a backslash, strip everything up to it
        const idx = val.indexOf('\\');
        if (idx !== -1) {
            val = val.slice(idx + 1);
        }
        // Keep only digits for the suffix
        const digits = val.replace(/[^0-9]/g, '');
        el.value = prefix + digits;
    }
</script>