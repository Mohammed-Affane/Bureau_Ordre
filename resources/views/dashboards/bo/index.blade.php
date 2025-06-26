<x-app-layout>
    <x-slot name="title">Dashboard Bureau d'Ordre</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => "Dashboard Bureau d'Ordre", 'url' => route('bo.dashboard')]]
    </x-slot>

    <h2 class="text-2xl font-bold text-gray-900">Bienvenue Bureau d'Ordre</h2>
    <p class="mt-2 text-gray-600">Gérez les courriers entrants et sortants ici.</p>

            <!-- Courriers récents et en attente -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Courriers reçus récemment -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Courriers Reçus Aujourd'hui</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        12 nouveaux
                    </span>
                </div>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @for($i = 1; $i <= 8; $i++)
                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900">C{{ date('Y') }}-{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Urgent
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Demande de documentation technique</p>
                                    <p class="text-xs text-gray-500 mt-1">De: Ministère de l'Industrie • {{ now()->subHours($i)->format('H:i') }}</p>
                                </div>
                                <button class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                    Traiter
                                </button>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Statistiques hebdomadaires -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activité de la Semaine</h3>
                <div class="space-y-4">
                    @php
                        $weekDays = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven'];
                        $weekData = [15, 23, 18, 31, 12];
                    @endphp
                    @foreach($weekDays as $index => $day)
                        <div class="flex items-center">
                            <div class="w-12 text-sm text-gray-600">{{ $day }}</div>
                            <div class="flex-1 ml-4">
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($weekData[$index] / 35) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="ml-4 text-sm font-medium text-gray-900 w-8">{{ $weekData[$index] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
</x-app-layout>
