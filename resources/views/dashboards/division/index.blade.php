<x-app-layout>
    <x-slot name="title">Dashboard Division</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Secrétariat Général', 'url' => route('sg.dashboard')]]
    </x-slot>


        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions Rapides</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    @include('components.icons.document-plus', ['class' => 'w-8 h-8 text-indigo-600 mb-2'])
                    <span class="text-sm font-medium text-gray-900">Nouveau Courrier</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    @include('components.icons.user-plus', ['class' => 'w-8 h-8 text-indigo-600 mb-2'])
                    <span class="text-sm font-medium text-gray-900">Assigner un Agent</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    @include('components.icons.archive-box', ['class' => 'w-8 h-8 text-indigo-600 mb-2'])
                    <span class="text-sm font-medium text-gray-900">Archiver Documents</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    @include('components.icons.chart-bar', ['class' => 'w-8 h-8 text-indigo-600 mb-2'])
                    <span class="text-sm font-medium text-gray-900">Générer Rapport</span>
                </button>
            </div>
        </div>
    </div>

</x-app-layout>
