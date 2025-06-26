<x-app-layout>
    <x-slot name="title">Dashboard Division</x-slot>
    <x-slot name="breadcrumbs">
        [['title' => 'Dashboard Division', 'url' => route('division.dashboard')]]
    </x-slot>

    <h2 class="text-2xl font-bold text-gray-900">Bienvenue Chef de Division</h2>
    <p class="mt-2 text-gray-600">Gérez les courriers en traitement, en cours et clôturés.</p>
    <div class="container mt-8">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Courriers en Traitement</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Objet</th>
                                    <th>Date de Réception</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courriers as $courrier)
                                    <tr>
                                        <td>{{ $courrier->objet }}</td>
                                        <td>{{ $courrier->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- End of Division Dashboard Content -->
<div class="container mt-8">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Courriers en Cours</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Objet</th>
                                <th>Date de Dépôt</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courriers as $courrier)
                                <tr>
                                    <td>{{ $courrier->objet }}</td>
                                    <td>{{ $courrier->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- End of Division Dashboard Content -->
<div class="container mt-8">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Courriers Clôturés</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Objet</th>
                                <th>Date de Clôture</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courriers as $courrier)
                                <tr>
                                    <td>{{ $courrier->objet }}</td>
                                    <td>{{ $courrier->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Objet</th>
                                <th>Date de Clôture</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- End of Division Dashboard Content -->
<div class="container mt-8">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistiques</h3>
                </div>
                <div class="card-body p-0">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500">Courriers en Traitement</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $courriers->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Courriers en Cours</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $courriers->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Courriers Clôturés</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $courriers->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-gray-900">65%</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-gray-900">25%</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-red-600">10%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- End of Division Dashboard Content -->
<div class="container mt-8">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistiques</h3>
                </div>
                <div class="card-body p-0">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-gray-900">65%</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-gray-900">25%</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-red-600">10%</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-gray-900">65%</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-gray-900">25%</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Temps moyen d'affectation</p>
                                    <p class="text-lg font-medium text-red-600">10%</p>
                                </div>
                            </div>
                        </div>
                    </div>











