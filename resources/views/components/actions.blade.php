<div class="relative inline-block" x-data="{ open: false }" data-courrier-id="{{ $courrier->id }}">
    <button @click="open = !open" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <span>Actions</span>
        <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Changed: Added fixed positioning and higher z-index -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="fixed bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
         style="z-index: 9999; width: 12rem; margin-top: 0.5rem;"
         x-init="
            $watch('open', value => {
                if (value) {
                    const button = $el.previousElementSibling;
                    const rect = button.getBoundingClientRect();
                    $el.style.left = (rect.right - 192) + 'px'; // 192px = 12rem width
                    $el.style.top = (rect.bottom + 8) + 'px';
                }
            })
         ">
        <div class="py-1" role="menu" aria-orientation="vertical">
            <a href="/courriers/{{ $courrier->id }}" 
               @click.prevent="open = false; window.location.href = `/courriers/${$el.closest('[data-courrier-id]').dataset.courrierId}`" 
               class="flex items-center px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-100" role="menuitem">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Voir
            </a>

            @php
                $userRole = Auth::user()->roles()->first()->name;
                $showActions = false;
                $showTraitement = false;

                if($userRole === 'admin'){
                    $showActions = true;
                }

                if($userRole === 'cab' && $courrier->statut === 'en_cours'){
                    $showActions = true;
                }
                if($userRole === 'bo' && $courrier->statut === 'en_attente'){
                    $showActions = true;
                }
                if($userRole === 'sg' && $courrier->statut === 'en_traitement'){
                    $showActions = true;
                }
                if($userRole === 'chef_division' && $courrier->statut === 'arriver'){
                    $showTraitement = true;
                }
            @endphp

            @if($showTraitement)
                @php
                    $affectation = $courrier->affectations->where('id_affecte_a_utilisateur', Auth::id())->first();
                @endphp

                @if($affectation && $courrierInstruct)
                    <a href="{{ route('division.affectations.traitement.show', $affectation->id) }}" 
                       class="flex items-center px-4 py-2 text-sm text-blue-600 hover:bg-blue-100" role="menuitem">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        {{ $affectation->traitements ? 'traiter Courrier' : 'Traiter' }}
                    </a>
                @endif
            @endif

            @if($showActions)
                <a href="/courriers/{{ $courrier->id }}/edit" 
                   @click.prevent="open = false; window.location.href = `/courriers/${$el.closest('[data-courrier-id]').dataset.courrierId}/edit`" 
                   class="flex items-center px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-100" role="menuitem">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
                
                <a href="/courriers/{{ $courrier->id }}/affectations" 
                   @click.prevent="open = false; window.location.href = `/courriers/${$el.closest('[data-courrier-id]').dataset.courrierId}/affectations`" 
                   class="flex items-center px-4 py-2 text-sm text-green-600 hover:bg-green-100" role="menuitem">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Affecter
                </a>

                <button @click="open = false; deleteCourrier($el.closest('[data-courrier-id]').dataset.courrierId)" 
                        class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-100" role="menuitem">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Supprimer
                </button>
            @endif
        </div>
    </div>
</div>

<script>
function deleteCourrier(courrierId) {
    if(confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/courriers/${courrierId}`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">