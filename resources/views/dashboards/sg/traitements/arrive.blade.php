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
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.2);
            --border-radius: 16px;
            --border-radius-sm: 8px;
        }

        * {
            box-sizing: border-box;
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

        /* Stats overview */
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-card.success {
            border-left-color: var(--success-color);
        }

        .stat-card.warning {
            border-left-color: var(--warning-color);
        }

        .stat-card.info {
            border-left-color: var(--primary-color);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .stat-icon svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        .stat-icon.success {
            background: var(--success-color);
        }

        .stat-icon.warning {
            background: var(--warning-color);
        }

        .stat-icon.info {
            background: var(--primary-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-800);
        }

        .stat-label {
            color: var(--gray-600);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Main content */
        .courrier-container {
            background: transparent;
            min-height: 100vh;
        }

        .courrier-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .courrier-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
            border-color: var(--primary-color);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .card-header-content {
            position: relative;
            z-index: 1;
        }

        .card-title {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 1.5rem 0;
            line-height: 1.4;
        }

        .card-title svg {
            margin-top: 0.2rem;
            opacity: 0.8;
            width: 1.5rem;
            height: 1.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Status badges enhanced */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 2px solid;
            transition: all 0.3s ease;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }

        .status-success {
            background: var(--success-light);
            color: var(--success-color);
            border-color: var(--success-color);
        }

        .status-warning {
            background: var(--warning-light);
            color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .status-badge svg {
            width: 1rem;
            height: 1rem;
        }

        /* Enhanced button */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: var(--shadow-sm);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        /* Enhanced affectation list */
        .affectation-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 1rem;
        }

        .affectation-item {
            background: var(--gray-50);
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .affectation-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .affectation-item:hover {
            background: white;
            border-color: var(--primary-color);
            transform: translateX(8px);
            box-shadow: var(--shadow-sm);
        }

        .affectation-item:hover::before {
            transform: scaleY(1);
        }

        .division-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .division-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .division-icon svg {
            width: 1.2rem;
            height: 1.2rem;
        }

        .division-name {
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
            font-size: 1.1rem;
        }

        .treatment-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: var(--border-radius-sm);
            border: 1px solid var(--gray-200);
        }

        .action-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .action-icon {
            width: 32px;
            height: 32px;
            background: var(--gray-100);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
        }

        .action-icon svg {
            width: 1rem;
            height: 1rem;
        }

        .action-text {
            color: var(--gray-700);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Enhanced treatment status badges */
        .treatment-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2px solid;
            transition: all 0.3s ease;
        }

        .treatment-status:hover {
            transform: scale(1.05);
        }

        .status-valide {
            background: var(--success-light);
            color: var(--success-color);
            border-color: var(--success-color);
        }

        .status-brouillon {
            background: var(--warning-light);
            color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .status-non-traite {
            background: var(--danger-light);
            color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .treatment-status svg {
            width: 0.875rem;
            height: 0.875rem;
        }

        /* Filter and search bar */
        .controls-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .search-box {
            flex: 1;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            font-size: 1rem;
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

        .filter-btn {
            padding: 0.75rem 1.5rem;
            background: var(--gray-100);
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .filter-btn svg {
            width: 1rem;
            height: 1rem;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .stats-overview {
                grid-template-columns: 1fr;
            }
            
            .card-header,
            .card-body {
                padding: 1.5rem;
            }
            
            .card-title {
                font-size: 1.125rem;
            }
            
            .treatment-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .controls-bar {
                flex-direction: column;
            }
            
            .search-box {
                order: -1;
            }
        }

        /* Loading animation for dynamic content */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .loading {
            animation: pulse 2s ease-in-out infinite;
        }

        /* Print styles */
        @media print {
            .btn-primary,
            .controls-bar {
                display: none;
            }
            
            .courrier-card {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid var(--gray-300);
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="page-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                </svg>
                Gestion des Courriers
            </h1>
            <p class="page-subtitle">Suivi et validation des affectations divisionnaires</p>
        </div>
    </div>

    <div class="courrier-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            
                
                <div class="stat-card info max-w-xl">
                    <div class="stat-header">
                        <div class="stat-icon info">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-number">{{ $courriers->total() }}</div>
                    </div>
                    <div class="stat-label">Total Courriers</div>
                </div>
            </div>

            <!-- Controls Bar -->
            <div class="controls-bar">
                <div class="search-box">
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input type="text" class="search-input" placeholder="Rechercher un courrier...">
                </div>
                <button class="filter-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                    Filtrer
                </button>
            
            </div>

            @foreach($courriers as $courrier)
                @php
                    // On filtre uniquement les affectations faites par SG Responsable
                    $affectationsSG = $courrier->affectations->filter(function($affectation) {
                        return $affectation->affectePar && $affectation->affectePar->name === 'SG Responsable';
                    });

                    // Vérifie si toutes les affectations SG -> divisions sont traitées et validées
                    $allDivisionsTreated = $affectationsSG->every(function($affectation) {
                        return $affectation->traitements->isNotEmpty() &&
                               $affectation->traitements->every(fn($t) => $t->statut === 'valide');
                    });
                @endphp

                <div class="courrier-card">
                    <div class="card-header">
                        <div class="card-header-content">
                            <h2 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <div>{{ $courrier->reference_arrive }}</div>
                                    <div style="font-weight: 400; opacity: 0.9; font-size: 1rem; margin-top: 0.5rem;">
                                        {{ $courrier->objet }}
                                    </div>
                                </div>
                            </h2>

                            {{-- Indicateur global --}}
                            @if($allDivisionsTreated)
                                <div class="status-badge status-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Divisions/Services validé
                                </div>
                            @else
                                <div class="status-badge status-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    En attente de validation
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Bouton Clôturer (uniquement si SG et tout validé) --}}
                        @if(auth()->user()->name == 'SG Responsable' && $courrier->statut != 'cloture' && $allDivisionsTreated)
                            <form action="" method="POST">
                                @csrf
                                <button type="submit" class="btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Clôturer le courrier
                                </button>
                            </form>
                        @endif

                        

                        {{-- Liste des affectations SG -> divisions --}}
                        <ul class="affectation-list">
                            @foreach($affectationsSG as $affectation)
        
                                <li class="affectation-item">
                                    <div class="division-header">
                                        <div class="division-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <h3 class="division-name">
                                            {{ $affectation->affecteA->name }}
                                        </h3>
                                    </div>

                                    @if($affectation->traitements->isNotEmpty())
                                        @foreach($affectation->traitements as $traitement)
                                            <div class="treatment-info">
                                                <div class="action-content">
                                                    <div class="action-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                                        </svg>
                                                    </div>
                                                    <span class="action-text">
                                                        <strong>Action:</strong>
                                                        <p>{{ $traitement->action }}</p> 
                                                    </span>
                                                </div>
                                                <span class="treatment-status 
                                                    @if($traitement->statut == 'valide') status-valide
                                                    @elseif($traitement->statut == 'brouillon') status-brouillon
                                                    @else status-non-traite @endif">
                                                    @if($traitement->statut == 'valide')
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    @elseif($traitement->statut == 'brouillon')
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                    {{ ucfirst($traitement->statut) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="treatment-info">
                                            <div class="action-content">
                                                <div class="action-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <span class="action-text">
                                                    Aucun traitement effectué
                                                </span>
                                            </div>
                                            <span class="treatment-status status-non-traite">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                                Non traité
                                            </span>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
                        {{ $courriers->links() }}
                    </div>
    </div>

    <script>
        // Simple search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.courrier-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                if (title.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Add loading state to buttons
        document.querySelectorAll('.btn-primary').forEach(button => {
            button.addEventListener('click', function() {
                const originalContent = this.innerHTML;
                this.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="animate-spin">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Traitement...
                `;
                this.disabled = true;
                
                // Restore original content after 3 seconds for demo purposes
                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }, 3000);
            });
        });
    </script>
</x-app-layout>