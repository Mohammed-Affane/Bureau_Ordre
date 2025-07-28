@php
    $user = auth()->user();
    $dashboards = [
        'admin' => ['route' => 'admin.dashboard', 'label' => "Admin Dashboard"],
        'bo' => ['route' => 'bo.dashboard', 'label' => "Bureau d'Ordre Dashboard"],
        'cab' => ['route' => 'cab.dashboard', 'label' => "Cabinet Dashboard"],
        'sg' => ['route' => 'sg.dashboard', 'label' => "Secrétariat Général Dashboard"],
        'chef_division' => ['route' => 'division.dashboard', 'label' => "Chef Division Dashboard"],
        'dai' => ['route' => 'dai.dashboard', 'label' => "Chef Division d'Intégration et d'Analyse Dashboard"],
    ];

    $userRole = $user->getRoleNames()->first(); // Spatie method returns a collection
    $dashboard = $dashboards[$userRole] ?? null;
@endphp

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-4">
        <li>
            <div>
                @if($dashboard)
                    <a href="{{ route($dashboard['route']) }}" class="text-gray-400 hover:text-gray-500 flex items-center">
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2 text-sm font-medium">{{ $dashboard['label'] }}</span>
                    </a>
                @else
                    <span class="text-red-600">Aucun rôle assigné</span>
                @endif
            </div>
        </li>

        {{-- Dynamic breadcrumbs --}}
        @foreach($breadcrumbs as $breadcrumb)
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                    </svg>
                    @if($loop->last)
                        <span class="ml-4 text-sm font-medium text-gray-500">{{ $breadcrumb['title'] }}</span>
                    @else
                        <a href="{{ $breadcrumb['url'] }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                            {{ $breadcrumb['title'] }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
