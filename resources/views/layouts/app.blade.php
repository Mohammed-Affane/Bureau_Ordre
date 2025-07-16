<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Bureau d\'Ordre') }}</title>
    <meta name="description" content="{{ $description ?? 'Système de gestion de bureau d\'ordre' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @auth
            <!-- Sidebar pour utilisateurs connectés -->
            @include('layouts.partials.sidebar')
            
            <!-- Main Content -->
            <div class="lg:pl-64">
                <!-- Header -->
                @include('layouts.partials.header')
                
                <!-- Page Content -->
                <main class="p-6">
                    <!-- Breadcrumbs -->
                    @if(isset($breadcrumbs))
                        @include('layouts.partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                    @endif
                    
                    <!-- Notifications -->
                    @if(session('success') || session('error') || session('warning'))
                        <div class="mb-6">
                            @include('shared.notifications')
                        </div>
                    @endif
                    
                    {{ $slot }}
                </main>
            </div>
        @else
            <!-- Layout pour invités (pages d'auth) -->
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                {{ $slot }}
            </div>
        @endauth
    </div>
    
    <!-- Scripts -->
    @stack('scripts')

    {{-- code for component actions {create-modifier-supprimer-affecter  --}}
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.action-dropdown').forEach(select => {
        select.addEventListener('change', function() {
            const action = this.value;
            const container = this.closest('.action-container');
            const courrierId = container.dataset.courrierId;
            
            this.selectedIndex = 0;
            
            switch(action) {
                case 'show':
                    window.location.href = `/courriers/${courrierId}`;
                    break;
                case 'edit':
                    window.location.href = `/courriers/${courrierId}/edit`;
                    break;
                case 'affecte':
                    window.location.href = `/courriers/${courrierId}/affecte`;
                break;
                case 'delete':
                    if(confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/courriers/${courrierId}`;
                        
                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                        
                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        
                        form.appendChild(csrf);
                        form.appendChild(method);
                        document.body.appendChild(form);
                        form.submit();
                    }
                    break;
            }
        });
    });
});
</script>
</body>
</html>