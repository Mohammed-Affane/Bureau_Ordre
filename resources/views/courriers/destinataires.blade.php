<x-app-layout>
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-md p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Destinataires du courrier : {{ $courrier->objet }}</h2>

    @if($courrier->courrierDestinatairePivot->isEmpty())
        <p class="text-gray-500">Aucun destinataire trouvé pour ce courrier.</p>
    @else
        <ul class="divide-y divide-gray-200">
            @foreach($courrier->courrierDestinatairePivot as $dest)
                <li class="py-4">
                    <div class="text-lg font-medium text-gray-800">
                        @if($dest->entite)
                            {{ $dest->entite->nom }}
                        @else
                            {{ $dest->nom ?? 'Destinataire externe' }}
                        @endif
                    </div>
                    @if($dest->adresse)
                        <div class="text-sm text-gray-500">Adresse : {{ $dest->adresse }}</div>
                    @endif
                    @if($dest->telephone)
                        <div class="text-sm text-gray-500">Téléphone : {{ $dest->telephone }}</div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    @if ($courrier->type_courrier==='arrive')   
    
    <a href="{{ route('courriers.arrive') }}" class="mt-4 inline-block text-indigo-600 hover:underline">← Retour à la liste des courriers</a>

   
    @endif
    @if ($courrier->type_courrier==='depart')   
    
    <a href="{{ route('courriers.depart') }}" class="mt-4 inline-block text-indigo-600 hover:underline">← Retour à la liste des courriers</a>

   
    @endif

    @if ($courrier->type_courrier==='decision')   
    
    <a href="{{ route('courriers.decision') }}" class="mt-4 inline-block text-indigo-600 hover:underline">← Retour à la liste des courriers</a>

   
    @endif

    @if ($courrier->type_courrier==='visa')   
    
    <a href="{{ route('courriers.visa') }}" class="mt-4 inline-block text-indigo-600 hover:underline">← Retour à la liste des courriers</a>

   
    @endif
     @if ($courrier->type_courrier==='interne')   
    
    <a href="{{ route('courriers.interne') }}" class="mt-4 inline-block text-indigo-600 hover:underline">← Retour à la liste des courriers</a>

   
    @endif

</div>
</x-app-layout>