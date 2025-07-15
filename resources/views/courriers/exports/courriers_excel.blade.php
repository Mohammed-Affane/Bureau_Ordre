<table>
    <thead>
        <tr>
            <th>Référence Arrivee</th>
            <th>Reference BO</th>
            <th>Statut</th>
            <th>Date Enreg.</th>
            <th>Pièces</th>
            <th>Objet</th>
            <th>Date</th>
            <th>Expéditeur</th>
            <th>Agent</th>
            <th>Priorité</th>
            <th>Destinataires</th>
        </tr>
    </thead>
    <tbody>
    @foreach($courriers as $courrier)
        <tr>
            <td>{{ $courrier->reference_depart ?? $courrier->reference_arrive ?? $courrier->reference_dec ?? $courrier->reference_visa ?? '-' }}</td>
            <td>{{ $courrier->reference_bo ?? '-' }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $courrier->statut)) }}</td>
            <td>{{ $courrier->date_enregistrement->format('d/m/Y') }}</td>
            <td>{{ $courrier->Nbr_piece }}</td>
            <td>{{ $courrier->objet }}</td>
            <td>
                @if($courrier->date_reception)
                    {{ $courrier->date_reception->format('d/m/Y') }}
                @elseif ($courrier->date_depart)
                    {{ $courrier->date_depart->format('d/m/Y') }}
                @else
                    N/A
                @endif
            </td>
            <td>{{ $courrier->expediteur->nom ?? ($courrier->entiteExpediteur->nom ?? 'N/A') }}</td>
            <td>{{ $courrier->agent->name ?? 'N/A' }}</td>
            <td>{{ ucfirst($courrier->priorite) }}</td>
            <td>
                @if($courrier->courrierDestinatairePivot->isEmpty())
                    Aucun destinataire trouvé.
                @else
                    @foreach($courrier->courrierDestinatairePivot as $dest)
                        {{ $dest->entite ? $dest->entite->nom : ($dest->nom ?? 'Destinataire externe') }},
                    @endforeach
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
