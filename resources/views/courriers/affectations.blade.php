<x-app-layout>
    <x-slot name="title">Affectations du Courrier</x-slot>

    <h2 class="text-2xl font-bold mb-4">Affectations du courrier : {{ $courrier->objet }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Affecté Par</th>
                <th>Affecté À</th>
                <th>Instruction</th>
                <th>Statut</th>
                <th>Date d'Affectation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courrier->affectations as $affectation)
                <tr>
                    <td>{{ $affectation->affectePar->name ?? 'N/A' }}</td>
                    <td>{{ $affectation->affecteA->name ?? 'N/A' }}</td>
                    <td>{{ $affectation->Instruction }}</td>
                    <td>{{ $affectation->statut_affectation }}</td>
                    <td>{{ $affectation->date_affectation }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucune affectation trouvée pour ce courrier.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
