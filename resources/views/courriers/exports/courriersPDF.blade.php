<!DOCTYPE html>
<html>
<head>
    <title>Liste des Courriers - {{ ucfirst($type) }}</title>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            font-size: 12px;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed; /* This is crucial */
            word-wrap: break-word;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            word-break: break-word; /* Ensure text breaks to fit */
            overflow: hidden; /* Hide overflow */
            overflow-wrap: break-word;
        }

        /* Column Widths */
        .col-ref { width: 8%; }
        .col-ref-bo { width: 8%; }
        .col-statut { width: 7%; }
        .col-date-enreg { width: 7%; }
        .col-pieces { width: 5%; }
        .col-objet { width: 15%; } /* Reduced from 20% */
        .col-date-depart, 
        .col-date-reception { width: 12%; } /* Increased from 7% */
        .col-expediteur { width: 12%; }
        .col-agent { width: 10%; }
        .col-priorite { width: 8%; }
        .col-destinataires { width: 15%; }

        /* For PDF printing */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        /* Adjust the list in destinataires column */
        td ul {
            margin: 0;
            padding-left: 15px;
        }

        td li {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Liste des Courriers - {{ ucfirst($type) }}</h1>
        <div class="subtitle">Généré le: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    @if(count($filters) > 0)
    <div class="filters">
        <strong>Filtres appliqués:</strong>
        @if(isset($filters['search']))
            <span class="filter-item">Recherche: "{{ $filters['search'] }}"</span>
        @endif
        @if(isset($filters['statut']))
            <span class="filter-item">Statut: {{ ucfirst(str_replace('_', ' ', $filters['statut'])) }}</span>
        @endif
        @if(isset($filters['priorite']))
            <span class="filter-item">Priorité: {{ ucfirst($filters['priorite']) }}</span>
        @endif
        @if(isset($filters['date_range']))
            <span class="filter-item">Période: {{ $this->getDateRangeLabel($filters) }}</span>
        @endif
    </div>
    @endif

    <table>
        <thead>
    <tr>
        @if ($type === 'depart' || $type === 'interne')
            <th class="col-ref">Réf.Depart</th>
        @elseif ($type === 'arrive')
            <th class="col-ref">Réf. Arrivée</th>
            <th class="col-ref-bo">Réf. BO</th>
        @elseif ($type === 'decision')
            <th class="col-ref">Réf. Decision</th>
        @elseif ($type === 'visa')
            <th class="col-ref">Réf. Visa</th>
        @endif
        
        <th class="col-statut">Statut</th>
        <th class="col-date-enreg">Date Enreg.</th>
        <th class="col-pieces">Pièces</th>
        <th class="col-objet">Objet</th>
        
        @if ($type === 'depart' || $type === 'decision' || $type === 'interne')
            <th class="col-date-depart">Date Depart</th>
        @elseif ($type === 'arrive' || $type === 'visa')
            <th class="col-date-reception">Date Réception</th>
        @endif
        
        <th class="col-expediteur">Expéditeur</th>
        <th class="col-agent">Agent</th>
        <th class="col-priorite">Priorité</th>
        <th class="col-destinataires">Destinataires</th>
    </tr>
</thead>
        <tbody>
            @foreach($courriers as $courrier)
            <tr>

                 @if ($type === 'depart' || $type === 'interne')

                <td>{{ $courrier->reference_depart }}</td>
                    
                @elseif ($type === 'arrive' )
               <td>{{ $courrier->reference_arrive }}</td>
                <td>{{ $courrier->reference_bo }}</td>
                
                @elseif ($type === 'decision' )
               <td>{{ $courrier->reference_dec}}</td>
               @elseif($type === 'visa')
               <td>{{ $courrier->reference_visa}}</td>

                @endif
               
                <td>
                    @php
                        $statusClasses = [
                            'en_attente' => 'background-color: #fef08a; color: #854d0e',
                            'en_cours' => 'background-color: #bfdbfe; color: #1e40af',
                            'arriver' => 'background-color: #bbf7d0; color: #166534',
                            'cloture' => 'background-color: #e5e5e5; color: #404040',
                            'archiver' => 'background-color: #e9d5ff; color: #6b21a8',
                        ];
                    @endphp
                    <span class="status-badge" style="{{ $statusClasses[$courrier->statut] ?? '' }}">
                        {{ ucfirst(str_replace('_', ' ', $courrier->statut)) }}
                    </span>
                </td>
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
                <td>
                    @php
                        $priorityClasses = [
                            'normale' => 'background-color: #e5e5e5; color: #404040',
                            'urgent' => 'background-color: #fecaca; color: #991b1b',
                            'confidentiel' => 'background-color: #c7d2fe; color: #3730a3',
                            'A reponse obligatoire' => 'background-color: #fed7aa; color: #9a3412',
                        ];
                    @endphp
                    <span class="priority-badge" style="{{ $priorityClasses[$courrier->priorite] ?? '' }}">
                        {{ ucfirst($courrier->priorite) }}
                    </span>
                </td>
                <td >
                    @if($courrier->courrierDestinatairePivot->isEmpty())
                        Aucun destinataire trouvé.
                    @else
                        <ul>
                            @foreach($courrier->courrierDestinatairePivot as $dest)
                                <li>
                                    {{ $dest->entite ? $dest->entite->nom : ($dest->nom ?? 'Destinataire externe') }}

                                </li>
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page 1 sur 1 • {{ count($courriers) }} courrier(s)
    </div>
</body>
</html>