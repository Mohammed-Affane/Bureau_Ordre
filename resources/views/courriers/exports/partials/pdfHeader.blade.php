<!DOCTYPE html>
<html>
<head>
    <title>Liste des Courriers - {{ ucfirst($type) }}</title>
    <style>
        body {
            font-family: 'Almarai', sans-serif;
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
                <span class="filter-item">Période: {{ $dateRangeLabel }}</span>
            @endif
        </div>
    @endif
