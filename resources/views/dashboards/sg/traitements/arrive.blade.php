<x-app-layout>
    <style>
        /* Variables CSS pour la cohérence */
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1d4ed8;
            --success-color: #10b981;
            --success-light: #d1fae5;
            --warning-color: #f59e0b;
            --warning-light: #fef3c7;
            --danger-color: #ef4444;
            --danger-light: #fee2e2;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --border-radius-sm: 8px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--gray-800);
            line-height: 1.6;
        }

        /* Page header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
        }

        .page-title svg {
            width: 2rem;
            height: 2rem;
            opacity: 0.9;
        }

        .page-subtitle {
            margin-top: 0.5rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        .stat-content .stat-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
        }

        .stat-content .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        /* Controls Bar */
        .controls-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .search-container {
            flex: 1;
            max-width: 400px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
        }

        .search-icon svg {
            width: 1rem;
            height: 1rem;
        }

        .filter-controls {
            display: flex;
            gap: 0.75rem;
        }

        .filter-btn {
            padding: 0.75rem 1.25rem;
            background: var(--gray-100);
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .filter-btn:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .filter-btn.active {
            background: var(--primary-color) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
        }

        .filter-btn svg {
            width: 1rem;
            height: 1rem;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .courrier-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
        }

        .table-header th {
            padding: 1.25rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--gray-200);
        }

        .table-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--gray-200);
        }

        .table-row:hover {
            background: var(--gray-50);
        }

        .table-row.completed {
            background: rgba(16, 185, 129, 0.05);
        }

        .table-row td {
            padding: 1.5rem 1rem;
            vertical-align: top;
        }

        /* Reference Column */
        .reference-cell {
            font-weight: 600;
            color: var(--primary-color);
            min-width: 140px;
        }

        /* Object Column */
        .object-cell {
            max-width: 300px;
            font-weight: 500;
            color: var(--gray-800);
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 2px solid;
            transition: all 0.3s ease;
        }

        .status-success {
            background: var(--success-light);
            color: var(--success-color);
            border-color: var(--success-color);
        }
        .status-cloture {
            background: rgba(139, 92, 246, 0.15);
            color: #8b5cf6;
            border-color: #8b5cf6;
        }

        .status-warning {
            background: var(--warning-light);
            color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .status-badge svg {
            width: 0.875rem;
            height: 0.875rem;
        }

        /* Divisions Column */
        .divisions-cell {
            min-width: 200px;
        }

        .division-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: var(--gray-50);
            border-radius: var(--border-radius-sm);
            border-left: 3px solid var(--gray-300);
            transition: all 0.3s ease;
        }

        .division-item.completed {
            background: var(--success-light);
            border-left-color: var(--success-color);
        }

        .division-item.pending {
            background: var(--warning-light);
            border-left-color: var(--warning-color);
        }

        .division-name {
            font-weight: 500;
            color: var(--gray-700);
            font-size: 0.875rem;
        }

        .division-status {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .division-status.completed {
            color: var(--success-color);
        }

        .division-status.pending {
            color: var(--warning-color);
        }

        .division-status.untreated {
            color: var(--danger-color);
        }

        .division-status svg {
            width: 0.75rem;
            height: 0.75rem;
        }
        
        .treatment-actions {
    max-width: 380px; /* Largeur augmentée */
    min-width: 250px; /* Largeur minimale */
}

.action-item {
    display: flex;
    padding: 0.6rem;
    margin-bottom: 0.6rem;
    background: #f8fafc;
    border-radius: 0.3rem;
    border-left: 3px solid #3b82f6;
}

.action-number {
    background: #3b82f6;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
    margin-right: 0.6rem;
    flex-shrink: 0;
}

.action-content {
    flex: 1;
    min-width: 0;
}

.action-details {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.action-name {
    font-weight: 600;
    font-size: 0.85rem;
    color: #374151;
    word-break: break-word; /* Casse les mots longs */
    overflow-wrap: break-word; /* Assurance pour casser les mots */
    line-height: 1.4;
}

.action-date {
    font-size: 0.75rem;
    color: #6b7280;
    align-self: flex-start;
}

/* Séparateur entre les actions */
.action-item:not(:last-child) {
    border-bottom: 1px dashed #e2e8f0;
    padding-bottom: 0.6rem;
}
.history-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #3b82f6;
    display: inline-block;
}

/* Adaptation pour les très longs textes */
@media (min-width: 768px) {
    .treatment-actions {
        max-width: 450px; /* Encore plus large sur les grands écrans */
    }
}
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-primary svg {
            width: 1rem;
            height: 1rem;
        }

        .btn-secondary {
            background: var(--success-color);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Progress Indicator */
        .progress-container {
            margin-top: 0.5rem;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
            transition: width 0.3s ease;
        }

        .progress-text {
            font-size: 0.75rem;
            color: var(--gray-600);
            margin-top: 0.25rem;
        }

        /* Expandable Details */
        .expandable-row {
            display: none;
        }

        .expandable-row.show {
            display: table-row;
        }

        .expandable-content {
            padding: 1rem;
            background: var(--gray-50);
            border-left: 4px solid var(--primary-color);
        }

        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .controls-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-container {
                max-width: none;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .page-header {
                padding: 1.5rem 0;
            }
            
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .table-wrapper {
                font-size: 0.875rem;
            }
            
            .table-header th,
            .table-row td {
                padding: 0.75rem 0.5rem;
            }
            
            .object-cell {
                max-width: 200px;
            }
        }

        /* Print Styles */
        @media print {
            .controls-bar,
            .btn-primary {
                display: none;
            }
            
            .table-container {
                box-shadow: none;
            }
            
            .table-row:hover {
                background: transparent;
            }
        }

        /* Animation keyframes */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="header-content">
                <div>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                        </svg>
                        Gestion des Courriers
                    </h1>
                    <p class="page-subtitle">Suivi et validation des affectations divisionnaires</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $courriers->total() }}</div>
                        <div class="stat-label">Total Courriers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="main-container">
            <!-- Controls Bar -->
            <div class="controls-bar">
                <div class="search-container">
                    <form id="searchForm" method="GET" action="{{ url()->current() }}">
                        <span class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input type="text" class="search-input" id="searchInput" name="search" 
                               value="{{ request('search', '') }}" placeholder="Rechercher un courrier...">
                        <!-- Hidden input to preserve status filter -->
                        <input type="hidden" id="statusFilter" name="status" value="{{ request('status', 'all') }}">
                    </form>
                </div>
                
                <div class="filter-controls">
                    <button class="filter-btn {{ request('status', 'all') === 'all' ? 'active' : '' }}" onclick="filterByStatus('all')" data-status="all">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Tous
                    </button>
                    <button class="filter-btn {{ request('status') === 'completed' ? 'active' : '' }}" onclick="filterByStatus('completed')" data-status="completed">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Complétés
                    </button>
                    <button class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }}" onclick="filterByStatus('pending')" data-status="pending">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        En cours
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <div class="table-wrapper">
                    <table class="courrier-table">
                        <thead class="table-header">
                            <tr>
                                <th>Référence</th>
                                <th>Objet</th>
                                <th>Statut Global</th>
                                <th>Divisions/Services</th>
                                <th>Actions</th>
                                <th>Progression</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courriers as $courrier)
                                @php
                                    $affectationsSG = $courrier->affectations->filter(function($affectation) {
                                        return $affectation->affectePar && $affectation->affectePar->name === 'SG Responsable';
                                    });

                                    $allDivisionsTreated = $affectationsSG->every(function($affectation) {
                                        return $affectation->traitements->isNotEmpty() &&
                                               $affectation->traitements->every(fn($t) => $t->statut === 'valide');
                                    });

                                    $totalDivisions = $affectationsSG->count();
                                    $completedDivisions = $affectationsSG->filter(function($affectation) {
                                        return $affectation->traitements->isNotEmpty() &&
                                               $affectation->traitements->every(fn($t) => $t->statut === 'valide');
                                    })->count();
                                    
                                    $progressPercent = $totalDivisions > 0 ? ($completedDivisions / $totalDivisions) * 100 : 0;
                                @endphp

                                <tr class="table-row {{ $allDivisionsTreated ? 'completed' : '' }}" data-status="{{ $allDivisionsTreated ? 'completed' : 'pending' }}">
                                    <td class="reference-cell">
                                        {{ $courrier->reference_arrive }}
                                    </td>
                                    
                                    <td class="object-cell">
                                        {{ Str::limit($courrier->objet, 60) }}
                                    </td>
                                    
                                    <td>
                                        @if($courrier->statut == 'cloture')
                                            <div class="status-badge status-cloture">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Clôturé
                                            </div>
                                        @elseif($allDivisionsTreated)
                                            <div class="status-badge status-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Validé
                                            </div>
                                        @else
                                            <div class="status-badge status-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                </svg>
                                                En cours
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td class="divisions-cell">
                                        @foreach($affectationsSG->take(2) as $affectation)
                                            @php
                                                $isCompleted = $affectation->traitements->isNotEmpty() && 
                                                             $affectation->traitements->every(fn($t) => $t->statut === 'valide');
                                                $hasDraft = $affectation->traitements->where('statut', 'brouillon')->isNotEmpty();
                                                $isUntreated = $affectation->traitements->isEmpty();
                                            @endphp
                                            
                                            <div class="division-item {{ $isCompleted ? 'completed' : ($hasDraft || !$isUntreated ? 'pending' : '') }}">
                                                <span class="division-name">
                                                    {{ Str::limit($affectation->affecteA->name, 20) }}
                                                </span>
                                                <div class="division-status {{ $isCompleted ? 'completed' : ($hasDraft ? 'pending' : 'untreated') }}">
                                                    @if($isCompleted)
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                        Validé
                                                    @elseif($hasDraft)
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                        Brouillon
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                        Non traité
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if($affectationsSG->count() > 2)
                                            <div class="division-item">
                                                <span class="division-name">+{{ $affectationsSG->count() - 2 }} autres...</span>
                                                <button onclick="toggleDetails({{ $courrier->id }})" class="division-status pending" style="background: none; border: none; cursor: pointer;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>

                                                                                                    </button>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
<div class="treatment-actions">
    <h5 class="history-title">Historique des actions:</h5>
    @foreach($affectation->traitements as $index => $traitement)
        <div class="action-item">
            <div class="action-number">{{ $index + 1 }}</div>
            <div class="action-content">
                <div class="action-details">
                    <div class="action-name">{{ ucfirst($traitement->action) }}</div>
                    <div class="action-date">{{ $traitement->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
                                    </td>
                                    
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                            <div class="progress-text">
                                                {{ $completedDivisions }}/{{ $totalDivisions }} divisions traitées
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="action-cell">
                                        @if(auth()->user()->name == 'SG Responsable' && $courrier->statut != 'cloture' && $allDivisionsTreated)
                                            <form action="{{ route('sg.courriers.cloturer', $courrier->id) }}" method="POST" onsubmit="return confirmCloture(event)">
                                                @csrf
                                                <button type="submit" class="btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Clôturer
                                                </button>
                                            </form>
                                        @elseif($courrier->statut == 'cloture')
                                            <div class="btn-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Clôturé
                                            </div>
                                        @else
                                            <button class="btn-primary" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                </svg>
                                                En attente
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- Expandable row for additional details -->
                                <tr class="expandable-row" id="details-{{ $courrier->id }}">
                                    <td colspan="6">
                                        <div class="expandable-content">
                                            <h4 style="margin-bottom: 1rem; color: var(--gray-700);">Détails des traitements par division</h4>
                                            <div class="treatment-details">
                                                @foreach($affectationsSG as $affectation)
                                                    @php
                                                        $isCompleted = $affectation->traitements->isNotEmpty() && 
                                                                     $affectation->traitements->every(fn($t) => $t->statut === 'valide');
                                                        $hasDraft = $affectation->traitements->where('statut', 'brouillon')->isNotEmpty();
                                                    @endphp
                                                    
                                                    <div class="treatment-item {{ $isCompleted ? 'completed' : ($hasDraft ? 'draft' : '') }}">
                                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                                            <strong>{{ $affectation->affecteA->name }}</strong>
                                                            <span class="division-status {{ $isCompleted ? 'completed' : ($hasDraft ? 'pending' : 'untreated') }}">
                                                                @if($isCompleted)
                                                                    Validé
                                                                @elseif($hasDraft)
                                                                    Brouillon
                                                                @else
                                                                    Non traité
                                                                @endif
                                                            </span>
                                                        </div>
                                                        
                                                        @if($affectation->traitements->isNotEmpty())
                                                            @foreach($affectation->traitements as $traitement)
                                                                <div style="margin-top: 0.5rem; padding: 0.5rem; background: var(--gray-100); border-radius: 4px;">
                                                                    <div><strong>Action:</strong> {{ $traitement->action }}</div>
                                                                    @if($traitement->commentaire)
                                                                        <div><strong>Commentaire:</strong> {{ $traitement->commentaire }}</div>
                                                                    @endif
                                                                    <div><strong>Statut:</strong> {{ ucfirst($traitement->statut) }}</div>
                                                                    <div><small>Dernière modification: {{ $traitement->updated_at->format('d/m/Y H:i') }}</small></div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div style="font-style: italic; color: var(--gray-600);">
                                                                Aucun traitement effectué
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $courriers->links() }}
            </div>
        </div>
    </div>

    <script>
        // Fonctionnalité de recherche avec délai
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        // Filtrage par statut
        function filterByStatus(status) {
            document.getElementById('statusFilter').value = status;
            document.getElementById('searchForm').submit();
        }

        // Fonction pour afficher/masquer les détails
        function toggleDetails(courrierId) {
            const detailsRow = document.getElementById('details-' + courrierId);
            detailsRow.classList.toggle('show');
        }

        // Fonction de confirmation pour la clôture
        function confirmCloture(event) {
            if (!confirm('Êtes-vous sûr de vouloir clôturer ce courrier? Cette action est irréversible.')) {
                event.preventDefault();
                return false;
            }
            
            // Afficher un indicateur de chargement
            const button = event.target.querySelector('button[type="submit"]') || event.target;
            const originalText = button.innerHTML;
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="animate-spin" style="animation: spin 1s linear infinite;">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                Traitement...
            `;
            button.disabled = true;
            
            return true;
        }

        // Filtrer les lignes du tableau en fonction du statut
        function filterTableRows(status) {
            const rows = document.querySelectorAll('.table-row');
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                if (status === 'all' || rowStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Mettre à jour les boutons de filtre
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.getAttribute('data-status') === status) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Appliquer le filtre initial si présent dans l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const statusParam = urlParams.get('status');
        if (statusParam) {
            filterTableRows(statusParam);
        }

        // Animation de spin pour les SVG
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .animate-spin {
                animation: spin 1s linear infinite;
            }
        `;
        document.head.appendChild(style);

        // Debug: Vérification que le JavaScript est chargé
        console.log('Script de gestion des courriers chargé');
    </script>
</x-app-layout>