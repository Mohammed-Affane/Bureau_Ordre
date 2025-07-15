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
            table-layout: fixed;
            word-wrap: break-word;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            word-break: break-word;
            overflow: hidden;
            vertical-align: top;
        }

        /* Column width classes */
        .col-ref { width: 10%; }
        .col-ref-bo { width: 8%; }
        .col-status { width: 8%; }
        .col-date-enreg { width: 8%; }
        .col-pieces { width: 5%; }
        .col-objet { width: 15%; }
        .col-date { width: 10%; }
        .col-expediteur { width: 12%; }
        .col-agent { width: 10%; }
        .col-priority { width: 8%; }
        .col-destinataires { width: 15%; }

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 1px solid #ddd; 
            padding-bottom: 10px; 
        }
        .header h1 { margin: 0; font-size: 18px; }
        .header .subtitle { font-size: 14px; color: #555; }
        .filters { 
            margin-bottom: 15px; 
            padding: 10px; 
            background: #f5f5f5; 
            border-radius: 5px; 
        }
        .filter-item { display: inline-block; margin-right: 15px; }
        .footer { 
            margin-top: 20px; 
            text-align: right; 
            font-size: 10px; 
            color: #666; 
        }
        .status-badge, .priority-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }
        td ul { margin: 0; padding-left: 15px; }
        td li { margin-bottom: 2px; }
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
                @elseif($type === 'visa')
                    <th class="col-ref">Réf. Visa</th>
                @endif
                
                <th class="col-status">Statut</th>
                <th class="col-date-enreg">Date Enreg.</th>
                <th class="col-pieces">Pièces</th>
                <th class="col-objet">Objet</th>
                
                @if ($type === 'depart' || $type === 'decision' || $type === 'interne')
                    <th class="col-date">Date Depart</th>
                @elseif ($type === 'arrive' || $type === 'visa')
                    <th class="col-date">Date Réception</th>
                @endif
                
                <th class="col-expediteur">Expéditeur</th>
                <th class="col-agent">Agent</th>
                <th class="col-priority">Priorité</th>
                <th class="col-destinataires">Destinataires</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courriers as $courrier)
            <tr>
                @if ($type === 'depart' || $type === 'interne')
                    <td class="col-ref">{{ $courrier->reference_depart }}</td>
                @elseif ($type === 'arrive')
                    <td class="col-ref">{{ $courrier->reference_arrive }}</td>
                    <td class="col-ref-bo">{{ $courrier->reference_bo }}</td>
                @elseif ($type === 'decision')
                    <td class="col-ref">{{ $courrier->reference_dec}}</td>
                @elseif($type === 'visa')
                    <td class="col-ref">{{ $courrier->reference_visa}}</td>
                @endif
               
                <td class="col-status">
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
                <td class="col-date-enreg">{{ $courrier->date_enregistrement->format('d/m/Y') }}</td>
                <td class="col-pieces">{{ $courrier->Nbr_piece }}</td>
                <td class="col-objet">{{ $courrier->objet }}</td>
                <td class="col-date">
                    @if($courrier->date_reception)
                        {{ $courrier->date_reception->format('d/m/Y') }}
                    @elseif ($courrier->date_depart)
                        {{ $courrier->date_depart->format('d/m/Y') }}
                    @else
                        N/A
                    @endif
                </td>
                <td class="col-expediteur">{{ $courrier->expediteur->nom ?? ($courrier->entiteExpediteur->nom ?? 'N/A') }}</td>
                <td class="col-agent">{{ $courrier->agent->name ?? 'N/A' }}</td>
                <td class="col-priority">
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
                <td class="col-destinataires">
                    @if($courrier->courrierDestinatairePivot->isEmpty())
                        Aucun destinataire trouvé.
                    @else
                        <ul>
                            @foreach($courrier->courrierDestinatairePivot as $dest)
                                <li>{{ $dest->entite ? $dest->entite->nom : ($dest->nom ?? 'Destinataire externe') }}</li>
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