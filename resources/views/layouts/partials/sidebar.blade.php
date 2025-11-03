<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4">
        <!-- Logo -->
        <div class="flex justify-center items-center">
                <div class="bg-indigo-600 p-2 rounded-lg m-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                </div>
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
                                    ['name' => 'Permissions', 'route' => 'admin.permissions.index', 'icon' => 'lock'],
                                    [
                                        'name' => 'Courriers',
                                        'icon' => 'mail',
                                        'submenu' => [
                                            ['name' => 'Arrivés', 'route' => 'courriers.arrive'],
                                            ['name' => 'Départs', 'route' => 'courriers.depart'],
                                            ['name' => 'Internes', 'route' => 'courriers.interne'],
                                            ['name' => 'Visas', 'route' => 'courriers.visa'],
                                            ['name' => 'Décisions', 'route' => 'courriers.decision'],
                                        ]
                                    ],
                                    ['name' => 'Entités', 'route' => 'admin.entites.index', 'icon' => 'building'],
                                ];
                                    break;
                                case 'bo':
                                    $menuItems = [
                                        ['name' => 'Nouveau Courrier', 'route' => 'courriers.create', 'icon' => 'plus'],
                                        ['name' => 'Courriers Arrivés', 'route' => 'courriers.arrive', 'icon' => 'inbox'],
                                        ['name' => 'Courriers Départs', 'route' => 'courriers.depart', 'icon' => 'inbox'],
                                        ['name' => 'Courriers Internes', 'route' => 'courriers.interne', 'icon' => 'inbox'],
                                        ['name' => 'Courriers Décisions', 'route' => 'courriers.decision', 'icon' => 'inbox'],
                                        ['name' => 'Courriers Visas', 'route' => 'courriers.visa', 'icon' => 'inbox']
                                        
                                    ];  
                                    break;
                                case 'cab':
                                    $menuItems = [
                                        ['name' => 'Dashboard', 'route' => 'cab.dashboard', 'icon' => 'home'],
                                        ['name'=>'Mes Courrier internes','route'=>'cab.courriers.interne','icon'=>'inbox'],
                                        ['name'=>'Mes Courrier arrives','route'=>'cab.courriers.arrive','icon'=>'inbox'],
                                        ['name'=>'Courriers','route'=>'courriers.index','icon'=>'inbox','submenu'=>[
                                            ['name' => 'Arrivés', 'route' => 'courriers.arrive'],
                                            ['name' => 'Départs', 'route' => 'courriers.depart'],
                                            ['name' => 'Internes', 'route' => 'courriers.interne'],
                                            ['name' => 'Visas', 'route' => 'courriers.visa'],
                                            ['name' => 'Décisions', 'route' => 'courriers.decision'],
                                        ]],
                                    ];
                                    break;
                                case 'dai':
                                    $menuItems = [
                                        ['name'=>'Mes Courrier arrives','route'=>'dai.courriers.arrive','icon'=>'inbox'],
                                        ['name'=>'Mes Courrier internes','route'=>'dai.courriers.interne','icon'=>'inbox'],
                                    ];
                                    break;
                                case 'sg':
                                    $menuItems = [
                                        ['name' => 'Dashboard', 'route' => 'sg.dashboard', 'icon' => 'home'],
                                        ['name'=>'Mes Courrier internes','route'=>'sg.courriers.interne','icon'=>'inbox'],
                                        ['name'=>'Mes Courrier arrives','route'=>'sg.courriers.arrive','icon'=>'inbox'],
                                        ['name'=>'Courriers','route'=>'courriers.index','icon'=>'inbox','submenu'=>[
                                            ['name' => 'Arrivés', 'route' => 'courriers.arrive'],
                                            ['name' => 'Départs', 'route' => 'courriers.depart'],
                                            ['name' => 'Internes', 'route' => 'courriers.interne'],
                                            ['name' => 'Visas', 'route' => 'courriers.visa'],
                                            ['name' => 'Décisions', 'route' => 'courriers.decision'],
                                        ]],
                                       ['name'=>'Traitements','route'=>'sg.traitements.arrive','icon'=>'inbox'],
                                    ];

                                    
                                    break;
                                default: // Chef de division
                                    $menuItems = [
                                        ['name'=>'Mes Courrier internes','route'=>'division.courriers.interne','icon'=>'inbox'],
                                        ['name'=>'Mes Courrier arrives','route'=>'division.courriers.arrive','icon'=>'inbox'],
                                    ];
                            }
                        @endphp
                        
                        @foreach($menuItems as $item)
                           @if(isset($item['submenu']))
                            <div x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="group flex w-full gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:bg-gray-800 hover:text-white">
                                    @include('components.icons.' . $item['icon'], ['class' => 'h-6 w-6 shrink-0'])
                                    {{ $item['name'] }}
                                    <svg :class="open ? 'rotate-90' : ''" class="ml-auto h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="mt-1 space-y-1 pl-8">
                                    @foreach($item['submenu'] as $subitem)
                                        <a href="{{ route($subitem['route']) }}"
                                        class="block rounded-md p-2 text-sm leading-6 font-medium {{ request()->routeIs($subitem['route']) ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                            {{ $subitem['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route($item['route']) }}"
                            class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs($item['route']) ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                @include('components.icons.' . $item['icon'], ['class' => 'h-6 w-6 shrink-0'])
                                {{ $item['name'] }}
                            </a>
                        @endif
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