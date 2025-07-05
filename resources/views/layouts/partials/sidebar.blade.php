<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4">
        <!-- Logo -->
        <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 text-red-600">
                    <path fill-rule="evenodd" d="M4.5 9.75a6 6 0 0111.573-2.226 3.75 3.75 0 014.133 4.303A4.5 4.5 0 0118 20.25H6.75a5.25 5.25 0 01-2.23-10.004 6.072 6.072 0 01-.02-.496z" clip-rule="evenodd" />
                </svg>
                <span class="text-white font-semibold text-lg">Bureau d'Ordre</span>
            </div>
        
        <!-- Navigation -->
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        @php
                            $userRole = auth()->user()->getRoleNames()->first();
                            $menuItems = [];
                            
                            switch($userRole) {
                                case 'admin':
                                    $menuItems = [
                                        ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
                                        ['name' => 'Utilisateurs', 'route' => 'admin.users.index', 'icon' => 'users'],
                                        ['name' => 'Rôles', 'route' => 'admin.roles.index', 'icon' => 'user-shield'],
                                        ['name' => 'Entités', 'route' => 'admin.entites.index', 'icon' => 'eye'],
                                        ['name' => 'Courriers', 'route' => 'admin.couriers', 'icon' => 'mail'],
                                        ['name' => 'Rapports', 'route' => 'admin.reports', 'icon' => 'chart-bar'],
                                        ['name' => 'Paramètres', 'route' => 'admin.settings', 'icon' => 'cog'],
                                    ];
                                    break;
                                case 'bo':
                                    $menuItems = [
                                        ['name' => 'Dashboard', 'route' => 'bo.dashboard', 'icon' => 'home'],
                                        ['name' => 'Nouveau Courrier', 'route' => 'bo.couriers.create', 'icon' => 'plus'],
                                        ['name' => 'Courriers Reçus', 'route' => 'bo.couriers.index', 'icon' => 'inbox'],
                                        ['name' => 'Historique', 'route' => 'bo.history', 'icon' => 'clock'],
                                    ];
                                    break;
                                case 'cab':
                                    $menuItems = [
                                        ['name' => 'Dashboard', 'route' => 'cab.dashboard', 'icon' => 'home'],
                                        ['name' => 'À Traiter', 'route' => 'cab.pending', 'icon' => 'exclamation-circle'],
                                        ['name' => 'Affectations', 'route' => 'cab.assignments', 'icon' => 'arrow-right'],
                                        ['name' => 'Historique', 'route' => 'cab.history', 'icon' => 'clock'],
                                    ];
                                    break;
                                case 'dai':
                                    $menuItems = [
                                        ['name' => 'Dashboard', 'route' => 'dai.dashboard', 'icon' => 'home'],
                                        ['name' => 'À Traiter', 'route' => 'dai.pending', 'icon' => 'exclamation-circle'],
                                        ['name' => 'Clôturés', 'route' => 'dai.closed', 'icon' => 'check-circle'],
                                    ];
                                    break;
                                case 'sg':
                                    $menuItems = [
                                        ['name' => 'Dashboard', 'route' => 'sg.dashboard', 'icon' => 'home'],
                                        ['name' => 'À Affecter', 'route' => 'sg.pending', 'icon' => 'exclamation-circle'],
                                        ['name' => 'Divisions', 'route' => 'sg.divisions', 'icon' => 'building-office'],
                                        ['name' => 'Suivi', 'route' => 'sg.tracking', 'icon' => 'eye'],
                                    ];
                                    break;
                                default: // Chef de division
                                    $menuItems = [
                                        ['name' => 'Dashboard', 'route' => 'division.dashboard', 'icon' => 'home'],
                                        ['name' => 'À Traiter', 'route' => 'division.pending', 'icon' => 'exclamation-circle'],
                                        ['name' => 'En Cours', 'route' => 'division.progress', 'icon' => 'clock'],
                                        ['name' => 'Terminés', 'route' => 'division.completed', 'icon' => 'check-circle'],
                                    ];
                            }
                        @endphp
                        
                        @foreach($menuItems as $item)
                            <li>
                                <a href="{{ route($item['route']) }}" 
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs($item['route']) ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    @include('components.icons.' . $item['icon'], ['class' => 'h-6 w-6 shrink-0'])
                                    {{ $item['name'] }}
                                    @if($item['name'] === 'À Traiter' || $item['name'] === 'À Affecter')
                                        <span class="ml-auto w-5 h-5 text-xs bg-red-600 text-white rounded-full flex items-center justify-center">
                                            {{ rand(1, 9) }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                
                <!-- User Section -->
                <li class="mt-auto">
                    <div class="flex items-center gap-x-4 px-2 py-3 text-sm font-semibold leading-6 text-white">
                        <img class="h-8 w-8 rounded-full bg-gray-800" 
                             src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" 
                             alt="{{ auth()->user()->name }}">
                        <span class="sr-only">Your profile</span>
                        <div class="flex flex-col">
                            <span aria-hidden="true">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-400">{{ ucfirst($userRole) }}</span>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>